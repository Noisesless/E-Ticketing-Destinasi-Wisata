<?php
/**
 * Tiered Rate Limiter Module
 * Standards: OWASP A07:2021/2025, NIST SP 800-63B, SP-014, SP-015
 * 
 * Supports multi-dimensional tracking:
 * 1. IP Address (Mitigates credential stuffing, bot spam, and automated tools)
 * 2. User Identifier / Email (Mitigates distributed brute-force targeting single accounts)
 * 
 * Progressive Tier Escalation:
 * - Tier 1: Initial Warning & Brief Cooldown
 * - Tier 2: Elevated Cooldown & Temporary Throttle
 * - Tier 3: Extended Lockout & Security Freeze
 */

// Tier Configuration Matrix
if (!defined('RATE_LIMIT_CONFIG')) {
    define('RATE_LIMIT_CONFIG', [
        'login' => [
            'identifier' => [ // Email / Username
                1 => ['threshold' => 5,  'window' => 900,  'lockout' => 60,   'label' => 'Tier 1 (Peringatan Awal)'],
                2 => ['threshold' => 10, 'window' => 900,  'lockout' => 900,  'label' => 'Tier 2 (Pembatasan Akun)'],
                3 => ['threshold' => 15, 'window' => 3600, 'lockout' => 3600, 'label' => 'Tier 3 (Penguncian Akun 1 Jam)'],
            ],
            'ip' => [ // Client IP
                1 => ['threshold' => 10, 'window' => 900,  'lockout' => 120,  'label' => 'Tier 1 (Peringatan Perangkat)'],
                2 => ['threshold' => 20, 'window' => 900,  'lockout' => 1800, 'label' => 'Tier 2 (Pembatasan IP 30 Menit)'],
                3 => ['threshold' => 30, 'window' => 3600, 'lockout' => 7200, 'label' => 'Tier 3 (Blokir IP 2 Jam)'],
            ]
        ],
        'register' => [
            'email' => [ // Specific email address
                1 => ['threshold' => 3, 'window' => 900,  'lockout' => 900,  'label' => 'Tier 1 (Email Cooldown 15 Menit)'],
                2 => ['threshold' => 6, 'window' => 3600, 'lockout' => 7200, 'label' => 'Tier 2 (Email Suspended 2 Jam)'],
            ],
            'ip' => [ // Client IP registration flood
                1 => ['threshold' => 4,  'window' => 600,   'lockout' => 600,   'label' => 'Tier 1 (Jeda Pendaftaran 10 Menit)'],
                2 => ['threshold' => 10, 'window' => 3600,  'lockout' => 3600,  'label' => 'Tier 2 (Limit Perangkat 1 Jam)'],
                3 => ['threshold' => 25, 'window' => 86400, 'lockout' => 86400, 'label' => 'Tier 3 (Limit Harian Perangkat 24 Jam)'],
            ]
        ]
    ]);
}

/**
 * Resolves reliable client IP address
 */
function get_client_ip(): string {
    $candidates = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR'
    ];

    foreach ($candidates as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                // Prefer public IPs if behind proxy
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
            // Fallback to first IP even if private (e.g. localhost development)
            $first = trim($ips[0]);
            if (filter_var($first, FILTER_VALIDATE_IP)) {
                return $first;
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Ensures rate_limits table exists in database
 */
function rate_limiter_init_db(?PDO $pdo = null): bool {
    static $ready = false;
    if ($ready) return true;

    if (!$pdo) {
        global $pdo;
    }
    if (!$pdo) return false;

    $sql = "CREATE TABLE IF NOT EXISTS `rate_limits` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `rate_key` VARCHAR(191) NOT NULL,
        `action` VARCHAR(32) NOT NULL,
        `identifier_type` VARCHAR(32) NOT NULL,
        `identifier_value` VARCHAR(191) NOT NULL,
        `attempts` INT UNSIGNED NOT NULL DEFAULT 1,
        `tier` TINYINT UNSIGNED NOT NULL DEFAULT 1,
        `locked_until` DATETIME NULL,
        `first_attempt_at` DATETIME NOT NULL,
        `last_attempt_at` DATETIME NOT NULL,
        UNIQUE KEY `uniq_rate_key` (`rate_key`),
        INDEX `idx_action_id` (`action`, `identifier_type`, `identifier_value`),
        INDEX `idx_locked_until` (`locked_until`),
        INDEX `idx_last_attempt` (`last_attempt_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    try {
        $pdo->exec($sql);
        $ready = true;
        return true;
    } catch (\Throwable $e) {
        error_log("RateLimiter Table Init Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Builds normalized rate key
 */
function rate_limiter_make_key(string $action, string $type, string $value): string {
    $normValue = strtolower(trim($value));
    return "rl:{$action}:{$type}:" . hash('sha256', $normValue);
}

/**
 * Checks whether an action for a given IP/identifier is currently allowed or locked
 * 
 * @return array{
 *     allowed: bool,
 *     tier: int,
 *     retry_after: int,
 *     attempts: int,
 *     message: string
 * }
 */
function rate_limiter_check(string $action, string $type, string $value, ?PDO $pdo = null): array {
    if (empty($value)) {
        return ['allowed' => true, 'tier' => 0, 'retry_after' => 0, 'attempts' => 0, 'message' => ''];
    }

    if (!$pdo) {
        global $pdo;
    }

    $key = rate_limiter_make_key($action, $type, $value);

    // Fallback if PDO not available
    if (!$pdo || !rate_limiter_init_db($pdo)) {
        return rate_limiter_check_session_fallback($action, $type, $value);
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM rate_limits WHERE rate_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $record = $stmt->fetch();

        if (!$record) {
            return ['allowed' => true, 'tier' => 0, 'retry_after' => 0, 'attempts' => 0, 'message' => ''];
        }

        $now = time();
        $lockedUntil = !empty($record['locked_until']) ? strtotime($record['locked_until']) : null;

        // Check if currently locked
        if ($lockedUntil && $lockedUntil > $now) {
            $retryAfter = $lockedUntil - $now;
            $tier = (int)$record['tier'];
            $timeText = rate_limiter_format_duration($retryAfter);
            
            $msg = rate_limiter_get_block_message($action, $type, $tier, $timeText, $retryAfter);

            return [
                'allowed' => false,
                'tier' => $tier,
                'retry_after' => $retryAfter,
                'attempts' => (int)$record['attempts'],
                'message' => $msg
            ];
        }

        // Check if observation window expired
        $lastAttempt = strtotime($record['last_attempt_at']);
        $maxWindow = rate_limiter_get_max_window($action, $type);
        if (($now - $lastAttempt) > $maxWindow) {
            // Window expired, record is stale -> reset
            $delStmt = $pdo->prepare("DELETE FROM rate_limits WHERE rate_key = ?");
            $delStmt->execute([$key]);
            return ['allowed' => true, 'tier' => 0, 'retry_after' => 0, 'attempts' => 0, 'message' => ''];
        }

        return [
            'allowed' => true,
            'tier' => 0,
            'retry_after' => 0,
            'attempts' => (int)$record['attempts'],
            'message' => ''
        ];
    } catch (\Throwable $e) {
        error_log("RateLimiter Check Error: " . $e->getMessage());
        return rate_limiter_check_session_fallback($action, $type, $value);
    }
}

/**
 * Records a failure or action occurrence and applies tiered escalation if threshold breached
 * 
 * @return array{
 *     locked: bool,
 *     tier: int,
 *     attempts: int,
 *     retry_after: int,
 *     remaining_before_lock: int,
 *     message: string
 * }
 */
function rate_limiter_record_failure(string $action, string $type, string $value, ?PDO $pdo = null): array {
    if (empty($value)) {
        return ['locked' => false, 'tier' => 0, 'attempts' => 0, 'retry_after' => 0, 'remaining_before_lock' => 99, 'message' => ''];
    }

    if (!$pdo) {
        global $pdo;
    }

    // Probabilistic cleanup (1% chance)
    if (mt_rand(1, 100) === 1 && $pdo) {
        rate_limiter_cleanup($pdo);
    }

    $key = rate_limiter_make_key($action, $type, $value);
    $normValue = strtolower(trim($value));

    if (!$pdo || !rate_limiter_init_db($pdo)) {
        return rate_limiter_record_failure_session_fallback($action, $type, $value);
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM rate_limits WHERE rate_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $record = $stmt->fetch();

        $now = time();
        $nowStr = date('Y-m-d H:i:s', $now);
        $maxWindow = rate_limiter_get_max_window($action, $type);

        if (!$record) {
            $attempts = 1;
            $firstAttempt = $nowStr;
        } else {
            $lastAttempt = strtotime($record['last_attempt_at']);
            if (($now - $lastAttempt) > $maxWindow) {
                // Stale window reset
                $attempts = 1;
                $firstAttempt = $nowStr;
            } else {
                $attempts = (int)$record['attempts'] + 1;
                $firstAttempt = $record['first_attempt_at'];
            }
        }

        // Determine active tier based on attempts
        $tierConfig = RATE_LIMIT_CONFIG[$action][$type] ?? [];
        $activeTier = 0;
        $lockoutSec = 0;

        // Evaluate from highest tier to lowest
        krsort($tierConfig);
        foreach ($tierConfig as $tierLevel => $cfg) {
            if ($attempts >= $cfg['threshold']) {
                $activeTier = $tierLevel;
                $lockoutSec = $cfg['lockout'];
                break;
            }
        }

        $lockedUntilStr = null;
        $isLocked = false;
        if ($activeTier > 0 && $lockoutSec > 0) {
            $isLocked = true;
            $lockedUntilStr = date('Y-m-d H:i:s', $now + $lockoutSec);
        }

        // Upsert rate limit record
        $upsertSql = "INSERT INTO rate_limits 
            (rate_key, action, identifier_type, identifier_value, attempts, tier, locked_until, first_attempt_at, last_attempt_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                attempts = VALUES(attempts),
                tier = VALUES(tier),
                locked_until = VALUES(locked_until),
                first_attempt_at = VALUES(first_attempt_at),
                last_attempt_at = VALUES(last_attempt_at)";

        $upStmt = $pdo->prepare($upsertSql);
        $upStmt->execute([
            $key,
            $action,
            $type,
            $normValue,
            $attempts,
            $activeTier > 0 ? $activeTier : 1,
            $lockedUntilStr,
            $firstAttempt,
            $nowStr
        ]);

        if ($isLocked) {
            $timeText = rate_limiter_format_duration($lockoutSec);
            $msg = rate_limiter_get_block_message($action, $type, $activeTier, $timeText, $lockoutSec);
            return [
                'locked' => true,
                'tier' => $activeTier,
                'attempts' => $attempts,
                'retry_after' => $lockoutSec,
                'remaining_before_lock' => 0,
                'message' => $msg
            ];
        }

        // Calculate remaining attempts before Tier 1 lock
        $t1Threshold = $tierConfig[1]['threshold'] ?? 5;
        $remaining = max(0, $t1Threshold - $attempts);

        return [
            'locked' => false,
            'tier' => 0,
            'attempts' => $attempts,
            'retry_after' => 0,
            'remaining_before_lock' => $remaining,
            'message' => ''
        ];
    } catch (\Throwable $e) {
        error_log("RateLimiter Record Error: " . $e->getMessage());
        return rate_limiter_record_failure_session_fallback($action, $type, $value);
    }
}

/**
 * Records a successful registration from an IP to prevent mass automated account creation
 */
function rate_limiter_record_success(string $action, string $type, string $value, ?PDO $pdo = null): void {
    if ($action === 'register' && $type === 'ip') {
        rate_limiter_record_failure($action, $type, $value, $pdo);
    }
}

/**
 * Clears or resets rate limits for a given action and identifier (e.g. on successful login)
 */
function rate_limiter_reset(string $action, string $type, string $value, ?PDO $pdo = null): void {
    if (empty($value)) return;
    if (!$pdo) {
        global $pdo;
    }

    $key = rate_limiter_make_key($action, $type, $value);

    // Clear session fallback if any
    unset($_SESSION['_rl'][$key]);

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("DELETE FROM rate_limits WHERE rate_key = ?");
            $stmt->execute([$key]);
        } catch (\Throwable $e) {
            error_log("RateLimiter Reset Error: " . $e->getMessage());
        }
    }
}

/**
 * Helper to determine maximum observation window for an action/type
 */
function rate_limiter_get_max_window(string $action, string $type): int {
    $config = RATE_LIMIT_CONFIG[$action][$type] ?? [];
    $max = 900;
    foreach ($config as $c) {
        if (isset($c['window']) && $c['window'] > $max) {
            $max = $c['window'];
        }
    }
    return $max;
}

/**
 * Human-friendly duration string
 */
function rate_limiter_format_duration(int $seconds): string {
    if ($seconds <= 0) return "1 detik";
    if ($seconds < 60) {
        return "$seconds detik";
    }
    $minutes = ceil($seconds / 60);
    if ($minutes < 60) {
        return "$minutes menit";
    }
    $hours = ceil($minutes / 60);
    return "$hours jam";
}

/**
 * Builds user-facing notification messages respecting copy standards
 */
function rate_limiter_get_block_message(string $action, string $type, int $tier, string $timeText, int $seconds): string {
    if ($action === 'login') {
        if ($type === 'identifier') {
            switch ($tier) {
                case 1:
                    return trans(
                        "Too many failed login attempts (Tier 1). Please wait $timeText before trying again.",
                        "Terlalu banyak percobaan login gagal (Tier 1). Harap tunggu $timeText sebelum mencoba lagi."
                    );
                case 2:
                    return trans(
                        "Login attempts exceeded (Tier 2). Account temporarily restricted for $timeText.",
                        "Batas percobaan login terlampaui (Tier 2). Akun dibatasi sementara selama $timeText demi keamanan."
                    );
                case 3:
                default:
                    return trans(
                        "Suspicious activity detected (Tier 3). Access suspended for $timeText. Please contact the administrator.",
                        "Aktivitas mencurigakan terdeteksi (Tier 3). Akses ditangguhkan selama $timeText. Silakan hubungi administrator."
                    );
            }
        } else {
            // IP type
            switch ($tier) {
                case 1:
                    return trans(
                        "Multiple failed logins from this device (Tier 1). Please wait $timeText.",
                        "Terlalu banyak login gagal dari perangkat ini (Tier 1). Harap tunggu $timeText."
                    );
                case 2:
                    return trans(
                        "Device temporarily restricted (Tier 2). Cooldown active for $timeText.",
                        "Perangkat dibatasi sementara (Tier 2). Akses dibekukan selama $timeText."
                    );
                case 3:
                default:
                    return trans(
                        "IP address blocked due to excessive failures (Tier 3). Please wait $timeText.",
                        "Alamat IP diblokir penuh (Tier 3) selama $timeText demi keamanan sistem."
                    );
            }
        }
    } elseif ($action === 'register') {
        if ($type === 'email') {
            return trans(
                "This email was recently used for registration (Tier $tier). Please wait $timeText before trying again.",
                "Alamat email ini baru saja digunakan untuk pendaftaran (Tier $tier). Harap tunggu $timeText sebelum mendaftar kembali."
            );
        } else {
            // IP
            switch ($tier) {
                case 1:
                    return trans(
                        "Registration rate limit reached (Tier 1). Please wait $timeText.",
                        "Batas pendaftaran tercapai untuk perangkat ini (Tier 1). Harap tunggu $timeText."
                    );
                case 2:
                    return trans(
                        "Registration temporarily suspended (Tier 2). Cooldown: $timeText.",
                        "Pendaftaran dari perangkat ini ditangguhkan sementara (Tier 2). Harap tunggu $timeText."
                    );
                case 3:
                default:
                    return trans(
                        "Daily registration limit exceeded (Tier 3). Please try again tomorrow ($timeText).",
                        "Batas harian pendaftaran terlampaui (Tier 3). Pendaftaran dari perangkat ini ditutup selama $timeText."
                    );
            }
        }
    }

    return trans("Too many requests. Please wait $timeText.", "Terlalu banyak permintaan. Silakan tunggu $timeText.");
}

/**
 * Periodically clears expired records to prevent database bloat
 */
function rate_limiter_cleanup(PDO $pdo): void {
    try {
        $stmt = $pdo->prepare("DELETE FROM rate_limits 
            WHERE last_attempt_at < (NOW() - INTERVAL 1 DAY) 
            AND (locked_until IS NULL OR locked_until < NOW())");
        $stmt->execute();
    } catch (\Throwable $e) {
        error_log("RateLimiter Cleanup Error: " . $e->getMessage());
    }
}

/**
 * Session-based fallback for environments where database is unreachable
 */
function rate_limiter_check_session_fallback(string $action, string $type, string $value): array {
    $key = rate_limiter_make_key($action, $type, $value);
    $data = $_SESSION['_rl'][$key] ?? null;

    if (!$data) {
        return ['allowed' => true, 'tier' => 0, 'retry_after' => 0, 'attempts' => 0, 'message' => ''];
    }

    $now = time();
    if (!empty($data['locked_until']) && $data['locked_until'] > $now) {
        $retryAfter = $data['locked_until'] - $now;
        $tier = (int)($data['tier'] ?? 1);
        $timeText = rate_limiter_format_duration($retryAfter);
        return [
            'allowed' => false,
            'tier' => $tier,
            'retry_after' => $retryAfter,
            'attempts' => (int)($data['attempts'] ?? 1),
            'message' => rate_limiter_get_block_message($action, $type, $tier, $timeText, $retryAfter)
        ];
    }

    return ['allowed' => true, 'tier' => 0, 'retry_after' => 0, 'attempts' => (int)($data['attempts'] ?? 0), 'message' => ''];
}

function rate_limiter_record_failure_session_fallback(string $action, string $type, string $value): array {
    $key = rate_limiter_make_key($action, $type, $value);
    $now = time();
    $data = $_SESSION['_rl'][$key] ?? ['attempts' => 0, 'tier' => 1, 'locked_until' => 0, 'last_attempt' => $now];

    $tierConfig = RATE_LIMIT_CONFIG[$action][$type] ?? [];
    $maxWindow = rate_limiter_get_max_window($action, $type);

    if (($now - ($data['last_attempt'] ?? 0)) > $maxWindow) {
        $data['attempts'] = 1;
    } else {
        $data['attempts']++;
    }
    $data['last_attempt'] = $now;

    krsort($tierConfig);
    $activeTier = 0;
    $lockoutSec = 0;
    foreach ($tierConfig as $tierLevel => $cfg) {
        if ($data['attempts'] >= $cfg['threshold']) {
            $activeTier = $tierLevel;
            $lockoutSec = $cfg['lockout'];
            break;
        }
    }

    if ($activeTier > 0) {
        $data['tier'] = $activeTier;
        $data['locked_until'] = $now + $lockoutSec;
        $_SESSION['_rl'][$key] = $data;
        $timeText = rate_limiter_format_duration($lockoutSec);
        return [
            'locked' => true,
            'tier' => $activeTier,
            'attempts' => $data['attempts'],
            'retry_after' => $lockoutSec,
            'remaining_before_lock' => 0,
            'message' => rate_limiter_get_block_message($action, $type, $activeTier, $timeText, $lockoutSec)
        ];
    }

    $_SESSION['_rl'][$key] = $data;
    $t1Threshold = $tierConfig[1]['threshold'] ?? 5;
    return [
        'locked' => false,
        'tier' => 0,
        'attempts' => $data['attempts'],
        'retry_after' => 0,
        'remaining_before_lock' => max(0, $t1Threshold - $data['attempts']),
        'message' => ''
    ];
}
