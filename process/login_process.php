<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan password harus diisi";
        redirect('/auth/login.php');
    }

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login
            $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            
            // Set remember me cookie if checked
            if ($remember_me) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                
                setcookie('remember_token', $token, $expiry, '/');
                
                // Store token in database
                $token_sql = "UPDATE users SET remember_token = ? WHERE id = ?";
                $token_stmt = $conn->prepare($token_sql);
                $token_stmt->bind_param("si", $token, $user['id']);
                $token_stmt->execute();
            }
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                $_SESSION['success'] = "Selamat datang, " . $user['name'] . "!";
                redirect('/admin/dashboard.php');
            } else {
                $_SESSION['success'] = "Selamat datang, " . $user['name'] . "!";
                redirect('/user/dashboard.php');
            }
        } else {
            $_SESSION['error'] = "Password yang Anda masukkan salah";
            redirect('/auth/login.php');
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan atau akun tidak aktif";
        redirect('/auth/login.php');
    }
} else {
    redirect('/auth/login.php');
}
?>