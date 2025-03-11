<?php
session_start();

// Include database config, but don't require it and suppress any warnings
// This allows the app to work even if the database is not available
@include_once('config/db.php');

// Check if already logged in
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Check if login form submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Static credentials for testing
    $valid_username = "admin";
    $valid_password = "admin123";
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = "";
    
    if(empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Simple static authentication
        if($username === $valid_username && $password === $valid_password) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_name'] = "System Administrator";
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Sit-in Monitoring System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2>Sit-in Monitoring System</h2>
            </div>
            
            <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">
                <?php if(isset($error) && !empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="login-btn" id="loginButton">Login <i class="fas fa-sign-in-alt"></i></button>
                </div>
            </form>
            
            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> Sit-in Monitoring System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Ensure the login button properly submits the form
        document.getElementById('loginButton').addEventListener('click', function(e) {
            e.preventDefault();
            // Quick validation
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if(username === '' || password === '') {
                alert('Please enter both username and password');
                return;
            }
            
            // Submit the form
            document.getElementById('loginForm').submit();
        });
    </script>
</body>
</html>
