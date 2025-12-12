<?php
// Include database configuration and functions
require_once '../config/database.php';

// Check if user is logged in for protected pages
$currentPage = basename($_SERVER['PHP_SELF']);
$publicPages = ['login.php', 'logout.php'];

// For pages other than login/logout, require login
if (!in_array($currentPage, $publicPages) && !isLoggedIn()) {
    requireLogin();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result System - Student Result Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <div class="container">
        <nav class="sidebar">
            <div class="logo">
                <h2><i class="fas fa-graduation-cap"></i> Result System</h2>
                <div class="admin-info">
                    <i class="fas fa-user-circle"></i>
                    <div>
                        <strong><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?></strong>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
            
            <ul class="nav-links">
                <li>
                    <a href="../pages/dashboard.php" class="<?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="../pages/students.php" class="<?php echo $currentPage == 'students.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Students
                    </a>
                </li>
                <li>
                    <a href="../pages/add_student.php" class="<?php echo $currentPage == 'add_student.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-plus"></i> Add Student
                    </a>
                </li>
                <li>
                    <a href="../pages/results.php" class="<?php echo $currentPage == 'results.php' ? 'active' : ''; ?>">
                        <i class="fas fa-poll"></i> Results
                    </a>
                </li>
                <li>
                    <a href="../pages/add_result.php" class="<?php echo $currentPage == 'add_result.php' ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i> Add Result
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="../admin/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <p>© <?php echo date('Y'); ?> Result System</p>
                <p>v1.0.0</p>
            </div>
        </nav>
        
        <div class="main-content">
    <?php endif; ?>
    
    <!-- Main content container -->
    <div class="content-wrapper">
        <?php 
        // Display session messages if any
        if (isset($_SESSION['message'])) {
            echo showMessage($_SESSION['message_type'] ?? 'success', $_SESSION['message']);
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>