<?php
require_once '../includes/header.php';
requireLogin();

$student = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$_GET['edit']]);
    $student = $stmt->fetch();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $class = $_POST['class'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    if($student) {
        // Update student
        $stmt = $pdo->prepare("UPDATE students SET full_name=?, class=?, date_of_birth=?, gender=?, email=?, phone=?, address=? WHERE student_id=?");
        $stmt->execute([$full_name, $class, $dob, $gender, $email, $phone, $address, $student['student_id']]);
        $message = "Student updated successfully!";
    } else {
        // Insert new student
        $stmt = $pdo->prepare("INSERT INTO students (full_name, class, date_of_birth, gender, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $class, $dob, $gender, $email, $phone, $address]);
        $message = "Student added successfully!";
    }
    
    header('Location: students.php');
    exit();
}
?>

<div class="content">
    <h1><?php echo $student ? 'Edit Student' : 'Add New Student'; ?></h1>
    
    <div class="form-container">
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $student ? htmlspecialchars($student['full_name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="class">Class *</label>
                    <select id="class" name="class" required>
                        <option value="">Select Class</option>
                        <option value="Class 10" <?php echo ($student && $student['class'] == 'Class 10') ? 'selected' : ''; ?>>Class 10</option>
                        <option value="Class 11" <?php echo ($student && $student['class'] == 'Class 11') ? 'selected' : ''; ?>>Class 11</option>
                        <option value="Class 12" <?php echo ($student && $student['class'] == 'Class 12') ? 'selected' : ''; ?>>Class 12</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $student ? $student['date_of_birth'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo ($student && $student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student && $student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($student && $student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo $student ? htmlspecialchars($student['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $student ? htmlspecialchars($student['phone']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?php echo $student ? htmlspecialchars($student['address']) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary"><?php echo $student ? 'Update Student' : 'Add Student'; ?></button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>