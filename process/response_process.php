<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk menawarkan bantuan']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $request_id = intval($_POST['request_id']);
    $message = sanitizeInput($_POST['message']);

    // Validation
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Pesan bantuan harus diisi']);
        exit;
    }

    // Check if request exists and is open
    $check_sql = "SELECT id, user_id, status FROM requests WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $request_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Permintaan bantuan tidak ditemukan']);
        exit;
    }

    $request = $check_result->fetch_assoc();

    // Check if request is open
    if ($request['status'] !== 'open') {
        echo json_encode(['success' => false, 'message' => 'Permintaan bantuan ini sudah tidak terbuka']);
        exit;
    }

    // Check if user is not the request owner
    if ($request['user_id'] === $user_id) {
        echo json_encode(['success' => false, 'message' => 'Anda tidak dapat menawarkan bantuan pada permintaan sendiri']);
        exit;
    }

    // Check if user already responded
    $existing_sql = "SELECT id FROM help_responses WHERE request_id = ? AND helper_id = ?";
    $existing_stmt = $conn->prepare($existing_sql);
    $existing_stmt->bind_param("ii", $request_id, $user_id);
    $existing_stmt->execute();
    $existing_result = $existing_stmt->get_result();

    if ($existing_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Anda sudah menawarkan bantuan untuk permintaan ini']);
        exit;
    }

    // Insert response
    $sql = "INSERT INTO help_responses (request_id, helper_id, message, status, created_at) 
            VALUES (?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $request_id, $user_id, $message);

    if ($stmt->execute()) {
        $response_id = $stmt->insert_id;

        // Create notification for request owner
        $notification_sql = "INSERT INTO notifications (user_id, title, message, type, related_id, related_type) 
                            VALUES (?, 'Tawaran Bantuan Baru', 'Pengguna " . $_SESSION['name'] . " menawarkan bantuan untuk permintaan Anda', 'info', ?, 'response')";
        $notification_stmt = $conn->prepare($notification_sql);
        $notification_stmt->bind_param("ii", $request['user_id'], $response_id);
        $notification_stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Tawaran bantuan berhasil dikirim!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat mengirim tawaran bantuan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid']);
}
?>