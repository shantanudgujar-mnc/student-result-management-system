<?php
require_once '../includes/header.php';
requireLogin();

// Fetch students for dropdown
$stmt = $pdo->query("SELECT * FROM students ORDER BY full_name");
$students = $stmt->fetchAll();

$result = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM results WHERE result_id = ?");
    $stmt->execute([$_GET['edit']]);
    $result = $stmt->fetch();
}

// Calculate grade and status
function calculateGrade($percentage) {
    if($percentage >= 90) return 'A';
    if($percentage >= 80) return 'B';
    if($percentage >= 70) return 'C';
    if($percentage >= 60) return 'D';
    return 'F';
}

function calculateStatus($percentage) {
    return $percentage >= 40 ? 'PASS' : 'FAIL';
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $exam_type = $_POST['exam_type'];
    $marks_obtained = $_POST['marks_obtained'];
    $total_marks = $_POST['total_marks'];
    
    // Get student info
    $stmt = $pdo->prepare("SELECT full_name, class FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    // Calculate
    $percentage = ($marks_obtained / $total_marks) * 100;
    $grade = calculateGrade($percentage);
    $status = calculateStatus($percentage);
    
    if($result) {
        // Update result
        $stmt = $pdo->prepare("UPDATE results SET student_id=?, student_name=?, class=?, subject=?, exam_type=?, marks_obtained=?, total_marks=?, percentage=?, grade=?, status=? WHERE result_id=?");
        $stmt->execute([$student_id, $student['full_name'], $student['class'], $subject, $exam_type, $marks_obtained, $total_marks, $percentage, $grade, $status, $result['result_id']]);
    } else {
        // Insert new result
        $stmt = $pdo->prepare("INSERT INTO results (student_id, student_name, class, subject, exam_type, marks_obtained, total_marks, percentage, grade, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $student['full_name'], $student['class'], $subject, $exam_type, $marks_obtained, $total_marks, $percentage, $grade, $status]);
    }
    
    header('Location: results.php');
    exit();
}
?>

<div class="content">
    <h1><?php echo $result ? 'Edit Result' : 'Add New Result'; ?></h1>
    
    <div class="form-container">
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="student_id">Student *</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                        <?php foreach($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>" 
                            <?php echo ($result && $result['student_id'] == $student['student_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['full_name']) . ' (' . $student['class'] . ')'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="class_display">Class</label>
                    <input type="text" id="class_display" readonly placeholder="Auto-filled based on student">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select Subject</option>
                        <option value="Mathematics" <?php echo ($result && $result['subject'] == 'Mathematics') ? 'selected' : ''; ?>>Mathematics</option>
                        <option value="Physics" <?php echo ($result && $result['subject'] == 'Physics') ? 'selected' : ''; ?>>Physics</option>
                        <option value="Chemistry" <?php echo ($result && $result['subject'] == 'Chemistry') ? 'selected' : ''; ?>>Chemistry</option>
                        <option value="Biology" <?php echo ($result && $result['subject'] == 'Biology') ? 'selected' : ''; ?>>Biology</option>
                        <option value="English" <?php echo ($result && $result['subject'] == 'English') ? 'selected' : ''; ?>>English</option>
                        <option value="Computer Science" <?php echo ($result && $result['subject'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exam_type">Exam Type</label>
                    <select id="exam_type" name="exam_type">
                        <option value="">Select Exam Type</option>
                        <option value="Midterm" <?php echo ($result && $result['exam_type'] == 'Midterm') ? 'selected' : ''; ?>>Midterm</option>
                        <option value="Final" <?php echo ($result && $result['exam_type'] == 'Final') ? 'selected' : ''; ?>>Final</option>
                        <option value="Quiz" <?php echo ($result && $result['exam_type'] == 'Quiz') ? 'selected' : ''; ?>>Quiz</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="marks_obtained">Marks Obtained *</label>
                    <input type="number" id="marks_obtained" name="marks_obtained" min="0" max="100" value="<?php echo $result ? $result['marks_obtained'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="total_marks">Total Marks</label>
                    <input type="number" id="total_marks" name="total_marks" value="<?php echo $result ? $result['total_marks'] : '100'; ?>" required>
                </div>
            </div>
            
            <div class="preview-box">
                <h3>Result Preview:</h3>
                <p>Percentage: <span id="preview_percentage">0</span>%</p>
                <p>Grade: <span id="preview_grade">-</span></p>
                <p>Status: <span id="preview_status">-</span></p>
            </div>
            
            <button type="submit" class="btn btn-primary"><?php echo $result ? 'Update Result' : 'Save Result'; ?></button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const classDisplay = document.getElementById('class_display');
    const marksInput = document.getElementById('marks_obtained');
    const totalMarksInput = document.getElementById('total_marks');
    
    // Update class when student is selected
    studentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const className = selectedOption.text.match(/\(([^)]+)\)/);
        if(className) {
            classDisplay.value = className[1];
        }
    });
    
    // Calculate preview
    function calculatePreview() {
        const marks = parseFloat(marksInput.value) || 0;
        const total = parseFloat(totalMarksInput.value) || 100;
        const percentage = total > 0 ? (marks / total * 100).toFixed(2) : 0;
        
        document.getElementById('preview_percentage').textContent = percentage;
        
        // Calculate grade
        let grade = 'F';
        if(percentage >= 90) grade = 'A';
        else if(percentage >= 80) grade = 'B';
        else if(percentage >= 70) grade = 'C';
        else if(percentage >= 60) grade = 'D';
        
        document.getElementById('preview_grade').textContent = grade;
        document.getElementById('preview_status').textContent = percentage >= 40 ? 'PASS' : 'FAIL';
    }
    
    marksInput.addEventListener('input', calculatePreview);
    totalMarksInput.addEventListener('input', calculatePreview);
    
    // Initial calculation
    calculatePreview();
});
</script>

<?php require_once '../includes/footer.php'; ?>