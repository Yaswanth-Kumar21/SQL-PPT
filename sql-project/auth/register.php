<?php
session_start();
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, userId, password FROM users WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error_msg'] = "User ID already exists! Please try another or login.";
        $stmt->close();
        header("Location: ../index.php");
        exit();
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert_stmt = $conn->prepare("INSERT INTO users (userId, password) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $userId, $hashed_password);
    
    if ($insert_stmt->execute()) {
        $_SESSION['success_msg'] = "Registration successful! Please login.";
    } else {
        $_SESSION['error_msg'] = "Error during registration! Please try again.";
    }
    
    $insert_stmt->close();
    $conn->close();
    
    header("Location: ../index.php");
    exit();
}
?>
