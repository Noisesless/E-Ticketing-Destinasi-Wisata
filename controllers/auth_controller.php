<?php
$action = $_GET['page'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action == 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
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
            $error = trans("Invalid credentials", "Username atau password salah");
        }
    } elseif ($action == 'register') {
        $username = trim($_POST['username'] ?? '');
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $password, $full_name, $email]);
            redirect('index.php?page=login');
        } catch (PDOException $e) {
            $error = "Username already taken";
        }
    }
}

require "views/auth/$action.php";
