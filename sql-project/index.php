<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Learning & Course Tracking System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container" id="login-container">
    <h2>Login</h2>
    <?php if(isset($_SESSION['error_msg'])): ?>
        <p style="color: #ff4b2b; text-align: center; margin-bottom: 10px;"><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
    <?php endif; ?>
    <?php if(isset($_SESSION['success_msg'])): ?>
        <p style="color: #38ef7d; text-align: center; margin-bottom: 10px;"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
    <?php endif; ?>
    
    <form action="auth/login.php" method="POST">
        <div class="form-group">
            <label>User ID</label>
            <input type="text" name="userId" required placeholder="Enter User ID">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="login-password" name="password" required placeholder="Enter Password">
        </div>
        <label class="password-toggle">
            <input type="checkbox" onclick="togglePassword('login-password')"> Show Password
        </label>
        <button type="submit" class="btn">Login</button>
    </form>
    <div class="toggle-link">
        Don't have an account? <a href="#" onclick="toggleForms(event)">Register here</a>
    </div>
</div>

<div class="container" id="register-container" style="display: none;">
    <h2>Register</h2>
    <form action="auth/register.php" method="POST">
        <div class="form-group">
            <label>User ID</label>
            <input type="text" name="userId" required placeholder="Choose User ID">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="register-password" name="password" required placeholder="Create Password">
        </div>
        <label class="password-toggle">
            <input type="checkbox" onclick="togglePassword('register-password')"> Show Password
        </label>
        <button type="submit" class="btn">Register</button>
    </form>
    <div class="toggle-link">
        Already have an account? <a href="#" onclick="toggleForms(event)">Login here</a>
    </div>
</div>

<script>
function togglePassword(inputId) {
    let input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

function toggleForms(e) {
    e.preventDefault();
    let loginForm = document.getElementById('login-container');
    let registerForm = document.getElementById('register-container');
    
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        registerForm.style.display = "none";
    } else {
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    }
}
</script>

</body>
</html>
