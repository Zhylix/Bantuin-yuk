<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini";
    redirect('/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = intval($_POST['user_id']);

    // Prevent admin from modifying themselves
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Anda tidak dapat mengubah status akun sendiri";
        redirect('/admin/users.php');
    }

    switch ($action) {
        case 'activate':
            $sql = "UPDATE users SET is_active = 1, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Pengguna berhasil diaktifkan";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat mengaktifkan pengguna";
            }
            break;

        case 'deactivate':
            $sql = "UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Pengguna berhasil dinonaktifkan";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat menonaktifkan pengguna";
            }
            break;

        default:
            $_SESSION['error'] = "Aksi tidak valid";
            break;
    }

    redirect('/admin/users.php');
} else {
    redirect('/admin/users.php');
}
?>