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
    $errors = [];

    switch ($action) {
        case 'add':
            $name = sanitizeInput($_POST['name']);
            $description = sanitizeInput($_POST['description']);
            $color = sanitizeInput($_POST['color']);
            $icon = sanitizeInput($_POST['icon']);

            // Validation
            if (empty($name)) {
                $errors[] = "Nama kategori harus diisi";
            }

            if (empty($color)) {
                $errors[] = "Warna kategori harus diisi";
            }

            // Check if tag name already exists
            $check_sql = "SELECT id FROM tags WHERE name = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $name);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $errors[] = "Nama kategori sudah ada";
            }

            if (empty($errors)) {
                // Format icon
                if (!empty($icon) && !str_starts_with($icon, 'fa-')) {
                    $icon = 'fa-' . $icon;
                }

                $sql = "INSERT INTO tags (name, description, color, icon, created_at) VALUES (?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $description, $color, $icon);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Kategori berhasil ditambahkan";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat menambahkan kategori";
                }
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
            break;

        case 'edit':
            $tag_id = intval($_POST['tag_id']);
            $name = sanitizeInput($_POST['name']);
            $description = sanitizeInput($_POST['description']);
            $color = sanitizeInput($_POST['color']);
            $icon = sanitizeInput($_POST['icon']);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Validation
            if (empty($name)) {
                $errors[] = "Nama kategori harus diisi";
            }

            if (empty($color)) {
                $errors[] = "Warna kategori harus diisi";
            }

            // Check if tag name already exists (excluding current tag)
            $check_sql = "SELECT id FROM tags WHERE name = ? AND id != ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("si", $name, $tag_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $errors[] = "Nama kategori sudah ada";
            }

            if (empty($errors)) {
                // Format icon
                if (!empty($icon) && !str_starts_with($icon, 'fa-')) {
                    $icon = 'fa-' . $icon;
                }

                $sql = "UPDATE tags SET name = ?, description = ?, color = ?, icon = ?, is_active = ?, updated_at = NOW() WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssii", $name, $description, $color, $icon, $is_active, $tag_id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Kategori berhasil diperbarui";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui kategori";
                }
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
            break;

        case 'delete':
            $tag_id = intval($_POST['tag_id']);

            // Check if tag is being used in any requests
            $check_sql = "SELECT id FROM request_tags WHERE tag_id = ? LIMIT 1";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $tag_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $_SESSION['error'] = "Tidak dapat menghapus kategori karena sedang digunakan dalam permintaan bantuan";
            } else {
                $sql = "DELETE FROM tags WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $tag_id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Kategori berhasil dihapus";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat menghapus kategori";
                }
            }
            break;

        default:
            $_SESSION['error'] = "Aksi tidak valid";
            break;
    }

    redirect('/admin/tags.php');
} else {
    redirect('/admin/tags.php');
}
?>