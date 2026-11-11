<?php
// Lightweight .env loader (Inline-First Dependency Gate)
function load_env($filePath = null) {
    if ($filePath === null) {
        $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    }
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove wrapping quotes if present
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

function env($key, $default = null) {
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    $val = getenv($key);
    return $val !== false ? $val : $default;
}

// Automatically load .env once
load_env();

// Tiered Rate Limiter Module (SP-014 / SP-015 / OWASP A07)
require_once __DIR__ . '/rate_limiter.php';

// Environment-aware Error Handling (Anti-Information Disclosure)
if (env('APP_ENV') === 'production') {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    $current_save_path = session_save_path();
    if (empty($current_save_path) || !is_dir($current_save_path)) {
        if (is_dir('C:/xampp/tmp')) {
            session_save_path('C:/xampp/tmp');
        } else {
            session_save_path(sys_get_temp_dir());
        }
    }
    // Hardened Session Cookie Parameters (Anti-Hijacking / Anti-XSS)
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// Input Validation & Sanitization Helpers
function validate_int($value, $default = null) {
    $val = filter_var($value, FILTER_VALIDATE_INT);
    return $val !== false ? $val : $default;
}

function sanitize_string($value) {
    return trim(strip_tags((string)($value ?? '')));
}

// XSS Escaper Helper
function e($string) {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

// CSRF Protection Helpers
function csrf_token() {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['_csrf_token'] ?? '';
        if (empty($token) || !isset($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
            http_response_code(403);
            die("Error 403: Invalid or expired CSRF token.");
        }
    }
}

function verify_csrf_token($token) {
    if (empty($token) || !isset($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
        http_response_code(403);
        die("Error 403: Invalid or expired CSRF token.");
    }
}

// Secure Image Upload Helper
function secure_upload_image($file, $targetDir = 'img/', $maxSize = 5242880) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > $maxSize) {
        return null;
    }

    // Whitelist extension check
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return null;
    }

    // Whitelist MIME type check via finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mimeType, $allowedMimes, true)) {
        return null;
    }

    // Prevent directory traversal & generate cryptographically secure unique filename
    $targetDir = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $safeFileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = $targetDir . $safeFileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $safeFileName;
    }

    return null;
}

function trans($en, $id) {
    $lang = $_SESSION['lang'] ?? 'id'; // Default to Indonesian
    return $lang == 'en' ? $en : $id;
}

function formatRupiah($amount) {
    return "Rp " . number_format($amount, 0, ',', '.');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function isOperator() {
    return isset($_SESSION['role']) && ($_SESSION['role'] == 'operator' || $_SESSION['role'] == 'admin');
}

function isMonitor() {
    return isset($_SESSION['role']) && ($_SESSION['role'] == 'monitor' || $_SESSION['role'] == 'admin');
}

function redirect($path) {
    header("Location: $path");
    exit;
}

// Simple Asset helper
function asset($path) {
    return $path; // In root deployment, this is just the path
}

function getYoutubeEmbedUrl($url) {
    if (empty($url)) return null;
    
    // Pattern to extract video ID from various YouTube URL formats
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    
    if (preg_match($pattern, $url, $matches)) {
        return "https://www.youtube.com/embed/" . $matches[1];
    }
    
    return null;
}
