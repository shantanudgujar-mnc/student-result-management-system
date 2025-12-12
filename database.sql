-- Create database
CREATE DATABASE IF NOT EXISTS student_result_system;
USE student_result_system;

-- Admin table
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    class VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Results table
CREATE TABLE results (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    student_name VARCHAR(100) NOT NULL,
    class VARCHAR(20) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    marks_obtained INT NOT NULL,
    total_marks INT DEFAULT 100,
    percentage DECIMAL(5,2),
    grade VARCHAR(2),
    status VARCHAR(10),
    exam_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Insert default admin (password: admin123)
INSERT INTO admin (username, password, email) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'admin@school.com');

-- Insert sample data
INSERT INTO students (full_name, class, date_of_birth, gender, email, phone) VALUES
('Alex Johnson', 'Class 10', '2007-05-15', 'Male', 'alex@school.com', '1234567890'),
('Maria Garcia', 'Class 11', '2006-08-22', 'Female', 'maria@school.com', '1234567891'),
('David Chen', 'Class 12', '2005-11-30', 'Male', 'david@school.com', '1234567892');

INSERT INTO results (student_id, student_name, class, subject, marks_obtained, total_marks, percentage, grade, status) VALUES
(1, 'Alex Johnson', 'Class 10', 'Mathematics', 85, 100, 85.00, 'A', 'PASS'),
(1, 'Alex Johnson', 'Class 10', 'Physics', 78, 100, 78.00, 'B', 'PASS'),
(2, 'Maria Garcia', 'Class 11', 'Chemistry', 92, 100, 92.00, 'A', 'PASS');