<?php
/**
 * Authentication Controller (Login & Register)
 * Hardened with Tiered Rate Limiting (IP & Email/Username)
 * Standards: OWASP A07:2021/2025, NIST SP 800-63B, SP-002, SP-014, SP-015
 */

$action = $_GET['page'] ?? 'login';
$clientIp = get_client_ip();
$retryAfter = 0;
$rateLimitWarning = null;

// Initial Pre-Flight Check on GET (Informational feedback for user)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ipGate = rate_limiter_check($action, 'ip', $clientIp, $pdo);
    if (!$ipGate['allowed']) {
        $rateLimitWarning = $ipGate['message'];
        $retryAfter = $ipGate['retry_after'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action === 'login') {
        $identifier = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // 1. Pre-Check: Client IP Rate Limit
        $ipCheck = rate_limiter_check('login', 'ip', $clientIp, $pdo);
        if (!$ipCheck['allowed']) {
            http_response_code(429);
            $error = $ipCheck['message'];
            $retryAfter = $ipCheck['retry_after'];
            require "views/auth/login.php";
            exit;
        }

        // 2. Pre-Check: Account Identifier (Username/Email) Rate Limit
        if (!empty($identifier)) {
            $idCheck = rate_limiter_check('login', 'identifier', $identifier, $pdo);
            if (!$idCheck['allowed']) {
                http_response_code(429);
                $error = $idCheck['message'];
                $retryAfter = $idCheck['retry_after'];
                require "views/auth/login.php";
                exit;
            }
        }

        // 3. Input Validation
        if (empty($identifier) || empty($password)) {
            $error = trans("Please enter both username/email and password.", "Username/email dan password wajib diisi.");
        } else {
            // 4. Query user by username OR email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch();

            // Pre-check canonical email/username if user exists
            if ($user) {
                if (!empty($user['email']) && strtolower($user['email']) !== strtolower($identifier)) {
                    $emailCheck = rate_limiter_check('login', 'identifier', $user['email'], $pdo);
                    if (!$emailCheck['allowed']) {
                        http_response_code(429);
                        $error = $emailCheck['message'];
                        $retryAfter = $emailCheck['retry_after'];
                        require "views/auth/login.php";
                        exit;
                    }
                }
                if (strtolower($user['username']) !== strtolower($identifier)) {
                    $unameCheck = rate_limiter_check('login', 'identifier', $user['username'], $pdo);
                    if (!$unameCheck['allowed']) {
                        http_response_code(429);
                        $error = $unameCheck['message'];
                        $retryAfter = $unameCheck['retry_after'];
                        require "views/auth/login.php";
                        exit;
                    }
                }
            }

            // 5. Verify Password
            if ($user && password_verify($password, $user['password'])) {
                // AUTH SUCCESSFUL!
                // Reset rate limiters on successful login
                rate_limiter_reset('login', 'ip', $clientIp, $pdo);
                rate_limiter_reset('login', 'identifier', $identifier, $pdo);
                rate_limiter_reset('login', 'identifier', $user['username'], $pdo);
                if (!empty($user['email'])) {
                    rate_limiter_reset('login', 'identifier', $user['email'], $pdo);
                }

                unset($_SESSION['login_fail_count'], $_SESSION['login_last_fail']);

                // Anti-Session Fixation (SP-002)
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    redirect('index.php?page=admin');
                } elseif ($user['role'] === 'operator') {
                    redirect('index.php?page=operator');
                } elseif ($user['role'] === 'monitor') {
                    redirect('index.php?page=monitor');
                } else {
                    redirect('index.php');
                }
            } else {
                // AUTH FAILED!
                // Escalate failure on IP and Identifier
                $ipFail = rate_limiter_record_failure('login', 'ip', $clientIp, $pdo);
                $idFail = rate_limiter_record_failure('login', 'identifier', $identifier, $pdo);

                if ($user) {
                    if (!empty($user['email']) && strtolower($user['email']) !== strtolower($identifier)) {
                        rate_limiter_record_failure('login', 'identifier', $user['email'], $pdo);
                    }
                    if (strtolower($user['username']) !== strtolower($identifier)) {
                        rate_limiter_record_failure('login', 'identifier', $user['username'], $pdo);
                    }
                }

                if ($idFail['locked']) {
                    http_response_code(429);
                    $error = $idFail['message'];
                    $retryAfter = $idFail['retry_after'];
                } elseif ($ipFail['locked']) {
                    http_response_code(429);
                    $error = $ipFail['message'];
                    $retryAfter = $ipFail['retry_after'];
                } else {
                    $remaining = min($idFail['remaining_before_lock'], $ipFail['remaining_before_lock']);
                    if ($remaining <= 3 && $remaining > 0) {
                        $error = trans(
                            "Invalid credentials. Warning: $remaining attempt(s) remaining before temporary cooldown.",
                            "Username/email atau password salah. Peringatan: sisa $remaining percobaan sebelum pembatasan sementara."
                        );
                    } else {
                        $error = trans("Invalid credentials", "Username/email atau password salah.");
                    }
                }
            }
        }
    } elseif ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        $rawPassword = $_POST['password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // 1. Pre-Check: Client IP Rate Limit for Registration
        $ipCheck = rate_limiter_check('register', 'ip', $clientIp, $pdo);
        if (!$ipCheck['allowed']) {
            http_response_code(429);
            $error = $ipCheck['message'];
            $retryAfter = $ipCheck['retry_after'];
            require "views/auth/register.php";
            exit;
        }

        // 2. Pre-Check: Target Email Rate Limit
        if (!empty($email)) {
            $emailCheck = rate_limiter_check('register', 'email', $email, $pdo);
            if (!$emailCheck['allowed']) {
                http_response_code(429);
                $error = $emailCheck['message'];
                $retryAfter = $emailCheck['retry_after'];
                require "views/auth/register.php";
                exit;
            }
        }

        // 3. Validation Checks
        if (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            rate_limiter_record_failure('register', 'ip', $clientIp, $pdo);
            $error = trans("Username must be at least 3 alphanumeric characters.", "Username minimal 3 karakter (huruf, angka, garis bawah).");
        } elseif (strlen($rawPassword) < 6) {
            rate_limiter_record_failure('register', 'ip', $clientIp, $pdo);
            $error = trans("Password must be at least 6 characters.", "Password minimal harus 6 karakter.");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            rate_limiter_record_failure('register', 'ip', $clientIp, $pdo);
            $error = trans("Please enter a valid email address.", "Format alamat email tidak valid.");
        } else {
            // 4. Duplicate Check
            $stmtCheck = $pdo->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmtCheck->execute([$username, $email]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                rate_limiter_record_failure('register', 'ip', $clientIp, $pdo);
                rate_limiter_record_failure('register', 'email', $email, $pdo);
                if (strtolower($existing['username']) === strtolower($username)) {
                    $error = trans("Username already taken.", "Username sudah digunakan oleh akun lain.");
                } else {
                    $error = trans("Email already registered. Please sign in.", "Alamat email sudah terdaftar. Silakan masuk.");
                }
            } else {
                // 5. Create User
                $password = password_hash($rawPassword, PASSWORD_DEFAULT);
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $password, $full_name, $email]);

                    // Record IP registration frequency & clear email limiter
                    rate_limiter_record_success('register', 'ip', $clientIp, $pdo);
                    rate_limiter_reset('register', 'email', $email, $pdo);

                    redirect('index.php?page=login&registered=1');
                } catch (PDOException $e) {
                    error_log("User registration error: " . $e->getMessage());
                    $error = trans("Registration failed. Please try again.", "Pendaftaran gagal. Silakan coba kembali.");
                }
            }
        }
    }
}

require "views/auth/$action.php";
