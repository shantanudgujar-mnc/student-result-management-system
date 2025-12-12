<?php
require_once '../includes/header.php';
requireLogin();

// Handle delete result
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM results WHERE result_id = ?");
    $stmt->execute([$id]);
    header('Location: results.php');
    exit();
}

// Fetch all results
$stmt = $pdo->query("SELECT r.*, s.student_id FROM results r LEFT JOIN students s ON r.student_name = s.full_name ORDER BY r.result_id DESC");
$results = $stmt->fetchAll();
?>

<div class="content">
    <h1>Result Management</h1>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Result ID</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $result): ?>
                <tr>
                    <td>R<?php echo str_pad($result['result_id'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo htmlspecialchars($result['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($result['class']); ?></td>
                    <td><?php echo htmlspecialchars($result['subject']); ?></td>
                    <td><?php echo $result['marks_obtained']; ?></td>
                    <td><?php echo $result['total_marks']; ?></td>
                    <td><?php echo $result['percentage']; ?>%</td>
                    <td><span class="grade grade-<?php echo strtolower($result['grade']); ?>"><?php echo $result['grade']; ?></span></td>
                    <td><span class="status status-<?php echo strtolower($result['status']); ?>"><?php echo $result['status']; ?></span></td>
                    <td class="actions">
                        <a href="add_result.php?edit=<?php echo $result['result_id']; ?>" class="btn-edit">Edit</a>
                        <a href="results.php?delete=<?php echo $result['result_id']; ?>" class="btn-delete" onclick="return confirm('Delete this result?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>