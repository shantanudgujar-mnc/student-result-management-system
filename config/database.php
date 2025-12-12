<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$host = 'localhost';
$dbname = 'student_result_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Include functions
require_once __DIR__ . '/../includes/functions.php';

// Check admin authentication
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_name']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: /student_result_system/admin/login.php');
        exit();
    }
}

// Check if user has permission (for future role-based access)
function hasPermission($permission = '') {
    // For now, all admins have all permissions
    // Can be extended for role-based access control
    return isLoggedIn();
}

// Set timezone
date_default_timezone_set('Asia/Kolkata'); // Change as needed
?>