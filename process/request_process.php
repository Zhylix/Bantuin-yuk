<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['error'] = "Anda harus login untuk mengakses halaman ini";
    redirect('/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $request_id = intval($_POST['request_id']);

    switch ($action) {
        case 'delete':
            // Check if user owns the request or is admin
            $check_sql = "SELECT user_id FROM requests WHERE id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $request_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows === 0) {
                $_SESSION['error'] = "Permintaan tidak ditemukan";
                redirect('/user/requests.php');
            }

            $request = $check_result->fetch_assoc();

            if ($request['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
                $_SESSION['error'] = "Anda tidak memiliki izin untuk menghapus permintaan ini";
                redirect('/user/requests.php');
            }

            // Start transaction
            $conn->begin_transaction();

            try {
                // Delete request tags
                $delete_tags_sql = "DELETE FROM request_tags WHERE request_id = ?";
                $delete_tags_stmt = $conn->prepare($delete_tags_sql);
                $delete_tags_stmt->bind_param("i", $request_id);
                $delete_tags_stmt->execute();

                // Delete help responses
                $delete_responses_sql = "DELETE FROM help_responses WHERE request_id = ?";
                $delete_responses_stmt = $conn->prepare($delete_responses_sql);
                $delete_responses_stmt->bind_param("i", $request_id);
                $delete_responses_stmt->execute();

                // Delete request
                $delete_sql = "DELETE FROM requests WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $request_id);
                $delete_stmt->execute();

                // Commit transaction
                $conn->commit();

                $_SESSION['success'] = "Permintaan berhasil dihapus";
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $_SESSION['error'] = "Terjadi kesalahan saat menghapus permintaan";
            }
            break;

        case 'update_status':
            $status = sanitizeInput($_POST['status']);

            // Check if user owns the request or is admin
            $check_sql = "SELECT user_id FROM requests WHERE id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $request_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows === 0) {
                $_SESSION['error'] = "Permintaan tidak ditemukan";
                redirect('/user/requests.php');
            }

            $request = $check_result->fetch_assoc();

            if ($request['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
                $_SESSION['error'] = "Anda tidak memiliki izin untuk mengubah status permintaan ini";
                redirect('/user/requests.php');
            }

            $sql = "UPDATE requests SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $request_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Status permintaan berhasil diperbarui";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat memperbarui status permintaan";
            }
            break;

        default:
            $_SESSION['error'] = "Aksi tidak valid";
            break;
    }

    if (isAdmin()) {
        redirect('/admin/requests.php');
    } else {
        redirect('/user/requests.php');
    }
} else {
    if (isAdmin()) {
        redirect('/admin/requests.php');
    } else {
        redirect('/user/requests.php');
    }
}
?>