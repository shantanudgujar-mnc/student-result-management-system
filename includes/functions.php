<?php
/**
 * Student Result Management System - Common Functions
 */

// Include this file in your pages after database.php

/**
 * Sanitize input data to prevent XSS attacks
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Calculate grade based on percentage
 */
function calculateGrade($percentage) {
    if ($percentage >= 90) return 'A';
    if ($percentage >= 80) return 'B';
    if ($percentage >= 70) return 'C';
    if ($percentage >= 60) return 'D';
    if ($percentage >= 40) return 'E';
    return 'F';
}

/**
 * Calculate status based on percentage
 */
function calculateStatus($percentage) {
    return $percentage >= 40 ? 'PASS' : 'FAIL';
}

/**
 * Calculate percentage
 */
function calculatePercentage($marks, $total) {
    if ($total == 0) return 0;
    return round(($marks / $total) * 100, 2);
}

/**
 * Format date to display format
 */
function formatDate($date, $format = 'd-m-Y') {
    if (empty($date) || $date == '0000-00-00') return 'N/A';
    return date($format, strtotime($date));
}

/**
 * Display success/error message
 */
function showMessage($type = 'success', $message = '') {
    if (empty($message)) {
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            return '';
        }
    }
    
    $alertClass = '';
    switch ($type) {
        case 'success':
            $alertClass = 'alert-success';
            break;
        case 'error':
            $alertClass = 'alert-error';
            break;
        case 'warning':
            $alertClass = 'alert-warning';
            break;
        case 'info':
            $alertClass = 'alert-info';
            break;
        default:
            $alertClass = 'alert-info';
    }
    
    return '<div class="alert ' . $alertClass . '">' . htmlspecialchars($message) . '</div>';
}

/**
 * Set session message
 */
function setMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

/**
 * Generate unique result ID
 */
function generateResultID($pdo) {
    $prefix = 'R';
    $stmt = $pdo->query("SELECT MAX(result_id) as max_id FROM results");
    $row = $stmt->fetch();
    
    if ($row['max_id']) {
        $lastNum = (int) substr($row['max_id'], 1);
        $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNum = '2001'; // Starting number
    }
    
    return $prefix . $newNum;
}

/**
 * Generate unique student ID
 */
function generateStudentID($pdo) {
    $prefix = 'S';
    $stmt = $pdo->query("SELECT MAX(student_id) as max_id FROM students");
    $row = $stmt->fetch();
    
    if ($row['max_id']) {
        $newNum = str_pad($row['max_id'] + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNum = '1001'; // Starting number
    }
    
    return $prefix . $newNum;
}

/**
 * Get class list for dropdown
 */
function getClassList($selected = '') {
    $classes = ['Class 10', 'Class 11', 'Class 12'];
    $html = '';
    
    foreach ($classes as $class) {
        $selectedAttr = ($selected == $class) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($class) . '" ' . $selectedAttr . '>' . htmlspecialchars($class) . '</option>';
    }
    
    return $html;
}

/**
 * Get subject list for dropdown
 */
function getSubjectList($selected = '') {
    $subjects = [
        'Mathematics',
        'Physics',
        'Chemistry',
        'Biology',
        'English',
        'Computer Science',
        'History',
        'Geography',
        'Economics',
        'Business Studies'
    ];
    
    $html = '<option value="">Select Subject</option>';
    
    foreach ($subjects as $subject) {
        $selectedAttr = ($selected == $subject) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($subject) . '" ' . $selectedAttr . '>' . htmlspecialchars($subject) . '</option>';
    }
    
    return $html;
}

/**
 * Get exam type list for dropdown
 */
function getExamTypeList($selected = '') {
    $examTypes = [
        'Midterm',
        'Final',
        'Quiz',
        'Assignment',
        'Practical'
    ];
    
    $html = '<option value="">Select Exam Type</option>';
    
    foreach ($examTypes as $type) {
        $selectedAttr = ($selected == $type) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($type) . '" ' . $selectedAttr . '>' . htmlspecialchars($type) . '</option>';
    }
    
    return $html;
}

/**
 * Get grade color class
 */
function getGradeColor($grade) {
    $colors = [
        'A' => 'grade-a',
        'B' => 'grade-b',
        'C' => 'grade-c',
        'D' => 'grade-d',
        'E' => 'grade-e',
        'F' => 'grade-f'
    ];
    
    return isset($colors[$grade]) ? $colors[$grade] : 'grade-default';
}

/**
 * Get status color class
 */
function getStatusColor($status) {
    $colors = [
        'PASS' => 'status-pass',
        'FAIL' => 'status-fail'
    ];
    
    return isset($colors[$status]) ? $colors[$status] : 'status-default';
}

/**
 * Check if student exists by email or phone
 */
function studentExists($pdo, $email, $phone, $excludeId = null) {
    $sql = "SELECT student_id FROM students WHERE (email = ? OR phone = ?)";
    $params = [$email, $phone];
    
    if ($excludeId) {
        $sql .= " AND student_id != ?";
        $params[] = $excludeId;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetch() ? true : false;
}

/**
 * Get dashboard statistics
 */
function getDashboardStats($pdo) {
    $stats = [];
    
    // Total students
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
    $stats['total_students'] = $stmt->fetch()['total'];
    
    // Total results
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM results");
    $stats['total_results'] = $stmt->fetch()['total'];
    
    // Total classes
    $stmt = $pdo->query("SELECT COUNT(DISTINCT class) as total FROM students");
    $stats['total_classes'] = $stmt->fetch()['total'];
    
    // Pass rate
    $stmt = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM results WHERE status = 'PASS') * 100.0 / 
        GREATEST((SELECT COUNT(*) FROM results), 1) as pass_rate");
    $stats['pass_rate'] = round($stmt->fetch()['pass_rate'], 1);
    
    // Grade distribution
    $stmt = $pdo->query("SELECT 
        SUM(CASE WHEN grade = 'A' THEN 1 ELSE 0 END) as a_count,
        SUM(CASE WHEN grade = 'B' THEN 1 ELSE 0 END) as b_count,
        SUM(CASE WHEN grade = 'C' THEN 1 ELSE 0 END) as c_count,
        SUM(CASE WHEN grade = 'D' THEN 1 ELSE 0 END) as d_count,
        SUM(CASE WHEN grade IN ('E', 'F') THEN 1 ELSE 0 END) as f_count,
        COUNT(*) as total
        FROM results");
    $gradeData = $stmt->fetch();
    
    if ($gradeData['total'] > 0) {
        $stats['grade_a'] = round(($gradeData['a_count'] / $gradeData['total']) * 100, 1);
        $stats['grade_b'] = round(($gradeData['b_count'] / $gradeData['total']) * 100, 1);
        $stats['grade_c'] = round(($gradeData['c_count'] / $gradeData['total']) * 100, 1);
        $stats['grade_d'] = round(($gradeData['d_count'] / $gradeData['total']) * 100, 1);
        $stats['grade_f'] = round(($gradeData['f_count'] / $gradeData['total']) * 100, 1);
    } else {
        $stats['grade_a'] = $stats['grade_b'] = $stats['grade_c'] = 
        $stats['grade_d'] = $stats['grade_f'] = 0;
    }
    
    // Class-wise average
    $stmt = $pdo->query("SELECT class, AVG(percentage) as avg_percentage 
                         FROM results GROUP BY class ORDER BY class");
    $stats['class_avg'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $stats;
}

/**
 * Export data to CSV
 */
function exportToCSV($data, $filename = 'export.csv', $headers = null) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add headers if provided
    if ($headers) {
        fputcsv($output, $headers);
    }
    
    // Add data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

/**
 * Log admin activity
 */
function logActivity($pdo, $adminId, $action, $details = '') {
    // Create logs table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
        log_id INT PRIMARY KEY AUTO_INCREMENT,
        admin_id INT,
        action VARCHAR(255),
        details TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, action, details, ip_address, user_agent) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $adminId,
        $action,
        $details,
        $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
}

/**
 * Upload file with validation
 */
function uploadFile($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'], $maxSize = 2097152) {
    if ($file['error'] != UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error: ' . $file['error']];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds limit'];
    }
    
    // Check file type
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Generate unique filename
    $newFilename = uniqid() . '_' . time() . '.' . $fileExt;
    $uploadPath = '../uploads/' . $newFilename;
    
    // Create uploads directory if not exists
    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $newFilename, 'path' => $uploadPath];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

/**
 * Paginate results
 */
function paginateResults($pdo, $sql, $params = [], $perPage = 10) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $perPage;
    
    // Get total count
    $countSql = preg_replace('/SELECT.*FROM/i', 'SELECT COUNT(*) as total FROM', $sql, 1);
    $countSql = preg_replace('/ORDER BY.*/i', '', $countSql);
    
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $totalRows = $stmt->fetch()['total'];
    $totalPages = ceil($totalRows / $perPage);
    
    // Add limit to original query
    $sql .= " LIMIT :offset, :perPage";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'results' => $results,
        'page' => $page,
        'totalPages' => $totalPages,
        'totalRows' => $totalRows,
        'perPage' => $perPage
    ];
}

/**
 * Generate pagination links
 */
function generatePagination($currentPage, $totalPages, $url = '') {
    if ($totalPages <= 1) return '';
    
    $html = '<div class="pagination">';
    
    // Previous link
    if ($currentPage > 1) {
        $html .= '<a href="' . $url . '?page=' . ($currentPage - 1) . '">&laquo; Previous</a>';
    }
    
    // Page links
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $html .= '<a class="active" href="#">' . $i . '</a>';
        } else {
            $html .= '<a href="' . $url . '?page=' . $i . '">' . $i . '</a>';
        }
    }
    
    // Next link
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . $url . '?page=' . ($currentPage + 1) . '">Next &raquo;</a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Generate password hash
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random password
 */
function generateRandomPassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    
    return $password;
}

/**
 * Send email notification
 */
function sendEmail($to, $subject, $message, $headers = '') {
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Result System <noreply@school.edu>\r\n";
    }
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Check if string is JSON
 */
function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Get current academic year
 */
function getAcademicYear() {
    $month = date('n');
    $year = date('Y');
    
    if ($month >= 8) { // August to December
        return $year . '-' . ($year + 1);
    } else { // January to July
        return ($year - 1) . '-' . $year;
    }
}

/**
 * Get student age from date of birth
 */
function getAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

/**
 * Format phone number
 */
function formatPhoneNumber($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format based on length
    if (strlen($phone) == 10) {
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $phone);
    }
    
    return $phone;
}

/**
 * Debug function (development only)
 */
function debug($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}