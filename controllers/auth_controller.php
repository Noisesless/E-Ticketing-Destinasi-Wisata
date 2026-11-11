<?php
$action = $_GET['page'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action == 'login') {
        $attempts = $_SESSION['login_fail_count'] ?? 0;
        $lastFail = $_SESSION['login_last_fail'] ?? 0;

        // Anti-Brute Force Throttle (SP-035)
        if ($attempts >= 5 && (time() - $lastFail) < 300) {
            $diff = 300 - (time() - $lastFail);
            $error = trans("Too many login attempts. Please wait $diff seconds.", "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam $diff detik.");
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                unset($_SESSION['login_fail_count']);
                unset($_SESSION['login_last_fail']);
                session_regenerate_id(true); // Anti-Session Fixation (SP-002)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] == 'admin') {
                    redirect('index.php?page=admin');
                } elseif ($user['role'] == 'operator') {
                    redirect('index.php?page=operator');
                } elseif ($user['role'] == 'monitor') {
                    redirect('index.php?page=monitor');
                } else {
                    redirect('index.php');
                }
            } else {
                $_SESSION['login_fail_count'] = $attempts + 1;
                $_SESSION['login_last_fail'] = time();
                $error = trans("Invalid credentials", "Username atau password salah");
            }
        }
    } elseif ($action == 'register') {
        $username = trim($_POST['username'] ?? '');
        $rawPassword = $_POST['password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validation Checks
        if (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = trans("Username must be at least 3 alphanumeric characters.", "Username minimal 3 karakter (huruf, angka, garis bawah).");
        } elseif (strlen($rawPassword) < 6) {
            $error = trans("Password must be at least 6 characters.", "Password minimal harus 6 karakter.");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = trans("Please enter a valid email address.", "Format alamat email tidak valid.");
        } else {
            $password = password_hash($rawPassword, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $password, $full_name, $email]);
                redirect('index.php?page=login');
            } catch (PDOException $e) {
                $error = "Username already taken";
            }
        }
    }
}

require "views/auth/$action.php";
