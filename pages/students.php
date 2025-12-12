<?php
require_once '../includes/header.php';
requireLogin();

// Handle delete student
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    header('Location: students.php');
    exit();
}

// Fetch all students
$stmt = $pdo->query("SELECT * FROM students ORDER BY student_id DESC");
$students = $stmt->fetchAll();
?>

<div class="content">
    <h1>Students</h1>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Class</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $student): ?>
                <tr>
                    <td>S<?php echo str_pad($student['student_id'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['class']); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($student['date_of_birth'])); ?></td>
                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                    <td class="actions">
                        <a href="add_student.php?edit=<?php echo $student['student_id']; ?>" class="btn-edit">Edit</a>
                        <a href="students.php?delete=<?php echo $student['student_id']; ?>" class="btn-delete" onclick="return confirm('Delete this student?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>