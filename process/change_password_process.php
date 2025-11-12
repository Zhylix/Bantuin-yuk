<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['error'] = "Anda harus login untuk mengakses halaman ini";
    redirect('/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    // Validation
    if (empty($current_password)) {
        $errors[] = "Password saat ini harus diisi";
    }

    if (empty($new_password)) {
        $errors[] = "Password baru harus diisi";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "Password baru minimal 6 karakter";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak sesuai";
    }

    if (empty($errors)) {
        // Get current user data
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify current password
            if (password_verify($current_password, $user['password'])) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password
                $update_sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $_SESSION['success'] = "Password berhasil diubah";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat mengubah password";
                }
            } else {
                $_SESSION['error'] = "Password saat ini salah";
            }
        } else {
            $_SESSION['error'] = "Pengguna tidak ditemukan";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }

    redirect('/user/profile.php');
} else {
    redirect('/user/profile.php');
}
?>