<?php
// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    if ($conn) {
        return mysqli_real_escape_string($conn, trim($data));
    }
    return trim($data);
}

// Function to get user by ID
function getUserById($id) {
    global $conn;
    if (!$conn) return null;
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

// Function to get all tags
function getAllTags() {
    global $conn;
    if (!$conn) return [];
    
    $sql = "SELECT * FROM tags WHERE is_active = 1 ORDER BY name";
    $result = $conn->query($sql);
    $tags = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $tags[] = $row;
        }
    }
    return $tags;
}

// Function to get requests with filters
function getRequests($filters = []) {
    global $conn;
    if (!$conn) return [];
    
    $sql = "SELECT r.*, u.name as user_name, GROUP_CONCAT(t.name) as tag_names 
            FROM requests r 
            LEFT JOIN users u ON r.user_id = u.id 
            LEFT JOIN request_tags rt ON r.id = rt.request_id 
            LEFT JOIN tags t ON rt.tag_id = t.id 
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if (!empty($filters['tags'])) {
        $placeholders = implode(',', array_fill(0, count($filters['tags']), '?'));
        $sql .= " AND t.id IN ($placeholders)";
        $params = array_merge($params, $filters['tags']);
        $types .= str_repeat('i', count($filters['tags']));
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND r.status = ?";
        $params[] = $filters['status'];
        $types .= 's';
    }
    
    if (!empty($filters['help_type'])) {
        $sql .= " AND r.help_type = ?";
        $params[] = $filters['help_type'];
        $types .= 's';
    }
    
    if (!empty($filters['urgency'])) {
        $sql .= " AND r.urgency = ?";
        $params[] = $filters['urgency'];
        $types .= 's';
    }
    
    $sql .= " GROUP BY r.id ORDER BY r.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if ($stmt && !empty($params)) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    $requests = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }
    return $requests;
}

// Function to get urgency color
function getUrgencyColor($urgency) {
    switch ($urgency) {
        case 'low': return 'green';
        case 'medium': return 'blue';
        case 'high': return 'orange';
        case 'critical': return 'red';
        default: return 'gray';
    }
}

// Function to get urgency icon
function getUrgencyIcon($urgency) {
    switch ($urgency) {
        case 'low': return 'clock';
        case 'medium': return 'exclamation';
        case 'high': return 'exclamation-circle';
        case 'critical': return 'skull-crossbones';
        default: return 'question';
    }
}

// Function to format time
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
}

// Simple debug function
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// Function to check if user can edit request
function canEditRequest($request_id) {
    if (!isLoggedIn()) return false;
    
    global $conn;
    $sql = "SELECT user_id FROM requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $request = $result->fetch_assoc();
        return $request['user_id'] == $_SESSION['user_id'] || isAdmin();
    }
    
    return false;
}
?>