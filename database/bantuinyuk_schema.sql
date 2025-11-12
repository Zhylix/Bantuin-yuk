-- Bantuin Yuk Database Schema
-- File: database/bantuinyuk_schema.sql

-- Create Database
CREATE DATABASE IF NOT EXISTS bantuinyuk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bantuinyuk_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    avatar VARCHAR(255),
    email_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100),
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- Tags Table
CREATE TABLE IF NOT EXISTS tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_active (is_active)
);

-- Requests Table
CREATE TABLE IF NOT EXISTS requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    urgency ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'completed', 'cancelled') DEFAULT 'open',
    help_type ENUM('offer', 'request') DEFAULT 'request',
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_urgency (urgency),
    INDEX idx_help_type (help_type),
    INDEX idx_created_at (created_at),
    INDEX idx_location (location(100)),
    FULLTEXT idx_search (title, description, location)
);

-- Request Tags Junction Table
CREATE TABLE IF NOT EXISTS request_tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    tag_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    UNIQUE KEY unique_request_tag (request_id, tag_id),
    INDEX idx_request_id (request_id),
    INDEX idx_tag_id (tag_id)
);

-- Help Responses Table
CREATE TABLE IF NOT EXISTS help_responses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    helper_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending',
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (helper_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_request_id (request_id),
    INDEX idx_helper_id (helper_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Notifications Table
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    related_id INT,
    related_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at),
    INDEX idx_related (related_type, related_id)
);

-- Insert Default Tags
INSERT INTO tags (name, description, color, icon) VALUES
('Kesehatan', 'Bantuan terkait kesehatan dan medis', '#EF4444', 'fa-heartbeat'),
('Pendidikan', 'Bantuan pendidikan dan belajar', '#3B82F6', 'fa-graduation-cap'),
('Makanan', 'Bantuan bahan makanan dan sembako', '#10B981', 'fa-utensils'),
('Pakaian', 'Bantuan pakaian dan fashion', '#8B5CF6', 'fa-tshirt'),
('Transportasi', 'Bantuan transportasi dan perjalanan', '#F59E0B', 'fa-car'),
('Teknologi', 'Bantuan teknologi dan digital', '#6366F1', 'fa-laptop'),
('Rumah Tangga', 'Bantuan kebutuhan rumah tangga', '#84CC16', 'fa-home'),
('Hewan Peliharaan', 'Bantuan untuk hewan peliharaan', '#F97316', 'fa-paw'),
('Legal', 'Bantuan hukum dan legal', '#6B7280', 'fa-gavel'),
('Psikologis', 'Bantuan konsultasi psikologis', '#EC4899', 'fa-brain');

-- Insert Default Admin User (password: admin123)
INSERT INTO users (name, email, password, role, email_verified, is_active) VALUES 
('Admin Bantuin Yuk', 'admin@bantuinyuk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Insert Sample Users (password: user123)
INSERT INTO users (name, email, password, phone, address, role, email_verified, is_active) VALUES 
('Ahmad Kurniawan', 'ahmad@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'Jakarta Selatan, DKI Jakarta', 'user', TRUE, TRUE),
('Sari Dewi', 'sari@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'Bandung, Jawa Barat', 'user', TRUE, TRUE),
('Rizky Maulana', 'rizky@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'Surabaya, Jawa Timur', 'user', TRUE, TRUE);

-- Insert Sample Requests
INSERT INTO requests (user_id, title, description, location, urgency, help_type, status) VALUES
(2, 'Butuh donor darah untuk operasi jantung', 'Keluarga saya membutuhkan donor darah golongan A+ untuk operasi jantung yang akan dilakukan minggu depan. Lokasi di RS Jantung Jakarta.', 'Jakarta Selatan, DKI Jakarta', 'critical', 'request', 'open'),
(3, 'Bimbingan belajar matematika untuk SMP', 'Membutuhkan guru privat untuk anak saya kelas 8 SMP yang kesulitan dengan pelajaran matematika. Bisa online atau offline di daerah Bandung.', 'Bandung, Jawa Barat', 'medium', 'request', 'open'),
(4, 'Menawarkan bantuan sembako untuk yang membutuhkan', 'Saya memiliki kelebihan sembako dan ingin membagikan kepada keluarga yang membutuhkan di sekitar Surabaya. Silakan hubungi jika membutuhkan.', 'Surabaya, Jawa Timur', 'low', 'offer', 'open'),
(2, 'Perbaikan laptop rusak tidak bisa nyala', 'Laptop saya tiba-tiba mati total dan tidak bisa dinyalakan. Membutuhkan bantuan teknisi yang berpengalaman untuk memperbaikinya.', 'Jakarta Selatan, DKI Jakarta', 'high', 'request', 'in_progress');

-- Insert Sample Request Tags
INSERT INTO request_tags (request_id, tag_id) VALUES
(1, 1), (1, 4),  -- Kesehatan, Pakaian
(2, 2), (2, 6),  -- Pendidikan, Teknologi
(3, 3), (3, 7),  -- Makanan, Rumah Tangga
(4, 6);          -- Teknologi

-- Insert Sample Help Responses
INSERT INTO help_responses (request_id, helper_id, message, status) VALUES
(1, 3, 'Saya memiliki golongan darah A+ dan bersedia mendonorkan darah. Bisa diatur jadwalnya?', 'pending'),
(2, 4, 'Saya lulusan matematika UI dan berpengalaman mengajar SMP. Bisa membantu via online.', 'accepted'),
(4, 2, 'Saya teknisi laptop berpengalaman. Bisa saya bantu diagnosa masalahnya.', 'completed');

-- Insert Sample Notifications
INSERT INTO notifications (user_id, title, message, type, related_id, related_type) VALUES
(1, 'Pengguna Baru', 'Ahmad Kurniawan baru saja bergabung dengan platform', 'info', 2, 'user'),
(1, 'Permintaan Baru', 'Sari Dewi membuat permintaan bantuan baru: Butuh donor darah untuk operasi jantung', 'info', 1, 'request'),
(2, 'Tawaran Bantuan', 'Rizky Maulana menawarkan bantuan untuk permintaan Anda', 'info', 1, 'response');

-- Create Views for Common Queries
CREATE VIEW request_details AS
SELECT 
    r.*,
    u.name as user_name,
    u.email as user_email,
    u.phone as user_phone,
    GROUP_CONCAT(DISTINCT t.name) as tag_names,
    GROUP_CONCAT(DISTINCT t.id) as tag_ids,
    COUNT(DISTINCT hr.id) as response_count
FROM requests r
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN request_tags rt ON r.id = rt.request_id
LEFT JOIN tags t ON rt.tag_id = t.id
LEFT JOIN help_responses hr ON r.id = hr.request_id
GROUP BY r.id;

CREATE VIEW user_stats AS
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(DISTINCT r.id) as total_requests,
    COUNT(DISTINCT hr.id) as total_responses,
    COUNT(DISTINCT CASE WHEN r.status = 'open' THEN r.id END) as open_requests,
    COUNT(DISTINCT CASE WHEN r.status = 'completed' THEN r.id END) as completed_requests
FROM users u
LEFT JOIN requests r ON u.id = r.user_id
LEFT JOIN help_responses hr ON u.id = hr.helper_id
GROUP BY u.id, u.name, u.email;

-- Create Stored Procedures
DELIMITER //

CREATE PROCEDURE GetRequestsByFilters(
    IN p_status VARCHAR(20),
    IN p_urgency VARCHAR(20),
    IN p_help_type VARCHAR(20),
    IN p_tag_id INT,
    IN p_search VARCHAR(255)
)
BEGIN
    SET @sql = '
        SELECT DISTINCT r.*, u.name as user_name, GROUP_CONCAT(t.name) as tag_names
        FROM requests r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN request_tags rt ON r.id = rt.request_id
        LEFT JOIN tags t ON rt.tag_id = t.id
        WHERE 1=1';
    
    IF p_status IS NOT NULL AND p_status != '' THEN
        SET @sql = CONCAT(@sql, ' AND r.status = ?');
    END IF;
    
    IF p_urgency IS NOT NULL AND p_urgency != '' THEN
        SET @sql = CONCAT(@sql, ' AND r.urgency = ?');
    END IF;
    
    IF p_help_type IS NOT NULL AND p_help_type != '' THEN
        SET @sql = CONCAT(@sql, ' AND r.help_type = ?');
    END IF;
    
    IF p_tag_id IS NOT NULL THEN
        SET @sql = CONCAT(@sql, ' AND rt.tag_id = ?');
    END IF;
    
    IF p_search IS NOT NULL AND p_search != '' THEN
        SET @sql = CONCAT(@sql, ' AND (r.title LIKE ? OR r.description LIKE ? OR r.location LIKE ?)');
    END IF;
    
    SET @sql = CONCAT(@sql, ' GROUP BY r.id ORDER BY r.created_at DESC');
    
    PREPARE stmt FROM @sql;
    
    IF p_search IS NOT NULL AND p_search != '' THEN
        SET @search_term = CONCAT('%', p_search, '%');
        IF p_tag_id IS NOT NULL THEN
            EXECUTE stmt USING p_status, p_urgency, p_help_type, p_tag_id, @search_term, @search_term, @search_term;
        ELSE
            EXECUTE stmt USING p_status, p_urgency, p_help_type, @search_term, @search_term, @search_term;
        END IF;
    ELSE
        IF p_tag_id IS NOT NULL THEN
            EXECUTE stmt USING p_status, p_urgency, p_help_type, p_tag_id;
        ELSE
            EXECUTE stmt USING p_status, p_urgency, p_help_type;
        END IF;
    END IF;
    
    DEALLOCATE PREPARE stmt;
END //

DELIMITER ;

-- Create Triggers for Auditing
DELIMITER //

CREATE TRIGGER after_request_insert
    AFTER INSERT ON requests
    FOR EACH ROW
BEGIN
    INSERT INTO notifications (user_id, title, message, type, related_id, related_type)
    SELECT 1, 'Permintaan Baru', CONCAT('Pengguna membuat permintaan bantuan baru: ', NEW.title), 'info', NEW.id, 'request'
    FROM users WHERE role = 'admin';
END //

CREATE TRIGGER after_response_insert
    AFTER INSERT ON help_responses
    FOR EACH ROW
BEGIN
    DECLARE request_user_id INT;
    DECLARE helper_name VARCHAR(100);
    
    SELECT user_id INTO request_user_id FROM requests WHERE id = NEW.request_id;
    SELECT name INTO helper_name FROM users WHERE id = NEW.helper_id;
    
    INSERT INTO notifications (user_id, title, message, type, related_id, related_type)
    VALUES (request_user_id, 'Tawaran Bantuan', CONCAT(helper_name, ' menawarkan bantuan untuk permintaan Anda'), 'info', NEW.id, 'response');
END //

DELIMITER ;