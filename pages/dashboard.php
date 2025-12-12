<?php
require_once '../includes/header.php';
requireLogin();

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_students FROM students");
$total_students = $stmt->fetch()['total_students'];

$stmt = $pdo->query("SELECT COUNT(*) as total_results FROM results");
$total_results = $stmt->fetch()['total_results'];

$stmt = $pdo->query("SELECT COUNT(DISTINCT class) as total_classes FROM students");
$total_classes = $stmt->fetch()['total_classes'];

$stmt = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM results WHERE status = 'PASS') * 100.0 / 
    (SELECT COUNT(*) FROM results) as pass_rate");
$pass_rate = $stmt->fetch()['pass_rate'];
?>

<div class="content">
    <h1>Dashboard</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3><?php echo $total_students; ?></h3>
            <p>Total Students</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $total_results; ?></h3>
            <p>Results Published</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $total_classes; ?></h3>
            <p>Classes</p>
        </div>
        <div class="stat-card">
            <h3><?php echo number_format($pass_rate, 1); ?>%</h3>
            <p>Overall Pass Rate</p>
        </div>
    </div>
    
    <div class="chart-container">
        <h2>Overall Performance by Class</h2>
        <div class="chart">
            <!-- Chart would be implemented with Chart.js -->
            <p>Average Percentage Chart</p>
            <div class="bar-chart">
                <div class="bar" style="height: 75%;" title="Class 10: 75%"></div>
                <div class="bar" style="height: 82%;" title="Class 11: 82%"></div>
                <div class="bar" style="height: 80%;" title="Class 12: 80%"></div>
            </div>
            <div class="chart-labels">
                <span>Class 10</span>
                <span>Class 11</span>
                <span>Class 12</span>
            </div>
        </div>
    </div>
    
    <div class="grade-distribution">
        <h2>Grade Distribution</h2>
        <div class="grade-bars">
            <div class="grade-bar grade-a">A: 35%</div>
            <div class="grade-bar grade-b">B: 25%</div>
            <div class="grade-bar grade-c">C: 20%</div>
            <div class="grade-bar grade-d">D: 15%</div>
            <div class="grade-bar grade-f">F: 5%</div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>