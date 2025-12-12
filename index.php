<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional styles for the welcome page */
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .welcome-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            display: flex;
            min-height: 500px;
        }

        .welcome-left {
            flex: 1;
            background: #2c3e50;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .features {
            list-style: none;
            margin: 20px 0;
        }

        .features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .features li:before {
            content: "✓";
            color: #2ecc71;
            font-weight: bold;
            margin-right: 10px;
        }

        .system-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .login-info-box {
            background: #e8f4fc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .btn-admin-login {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
            margin-top: 20px;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-admin-login:hover {
            background: #2980b9;
        }

        .quick-links {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .quick-link {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.3s;
        }

        .quick-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .welcome-box {
                flex-direction: column;
            }
            
            .welcome-left, .welcome-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-box">
            <div class="welcome-left">
                <h1 style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Result System</h1>
                <h2 style="color: #bdc3c7; margin-bottom: 30px;">Student Result Management System</h2>
                
                <div style="margin-top: 30px;">
                    <h3 style="color: #ecf0f1; margin-bottom: 15px;">System Features:</h3>
                    <ul class="features">
                        <li>Student Management</li>
                        <li>Result Management</li>
                        <li>Automatic Grade Calculation</li>
                        <li>Performance Analytics</li>
                        <li>Responsive Design</li>
                        <li>Secure Admin Panel</li>
                    </ul>
                </div>
                
                <div style="margin-top: auto; color: #bdc3c7; font-size: 0.9rem;">
                    <p>Version 1.0 | © <?php echo date('Y'); ?> School Administration</p>
                </div>
            </div>
            
            <div class="welcome-right">
                <h1 class="welcome-title">Welcome</h1>
                <p class="welcome-subtitle">Manage student results efficiently with our comprehensive system</p>
                
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <div class="system-info">
                        <h3>Welcome back, Admin!</h3>
                        <p>You are already logged in. Access the dashboard to manage the system.</p>
                        <a href="pages/dashboard.php" class="btn-admin-login">Go to Dashboard</a>
                    </div>
                <?php else: ?>
                    <div class="login-info-box">
                        <h4>Admin Access Required</h4>
                        <p>To access the management system, please log in with your admin credentials.</p>
                        
                        <div style="margin-top: 15px; background: white; padding: 10px; border-radius: 5px;">
                            <p><strong>Default Admin Credentials:</strong></p>
                            <p>Username: <code>admin</code></p>
                            <p>Password: <code>admin123</code></p>
                        </div>
                    </div>
                    
                    <a href="admin/login.php" class="btn-admin-login">Admin Login</a>
                <?php endif; ?>
                
                <div class="quick-links">
                    <a href="#features" class="quick-link">
                        <h4>Features</h4>
                        <p>See all features</p>
                    </a>
                    <a href="#about" class="quick-link">
                        <h4>About</h4>
                        <p>Learn more</p>
                    </a>
                    <a href="#contact" class="quick-link">
                        <h4>Contact</h4>
                        <p>Get support</p>
                    </a>
                </div>
                
                <div style="margin-top: 30px; text-align: center; color: #7f8c8d;">
                    <p>For student inquiries, please contact the administration office.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional sections -->
    <div id="features" style="display: none;">
        <!-- Features section would go here -->
    </div>
    
    <script>
        // Smooth scroll for quick links
        document.querySelectorAll('.quick-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if(this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const target = document.querySelector(targetId);
                    if(target) {
                        target.style.display = 'block';
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });
        
        // Check if user is idle and show login reminder
        let idleTime = 0;
        const idleInterval = setInterval(timerIncrement, 60000); // 1 minute
        
        function timerIncrement() {
            idleTime++;
            if(idleTime > 5) { // 5 minutes idle
                // You could add a logout warning here
                console.log('User idle for 5 minutes');
            }
        }
        
        // Reset idle time on user activity
        ['mousemove', 'keypress', 'click'].forEach(event => {
            document.addEventListener(event, () => {
                idleTime = 0;
            });
        });
    </script>
</body>
</html>