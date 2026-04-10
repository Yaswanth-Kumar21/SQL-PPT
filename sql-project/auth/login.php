<?php
session_start();
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, userId, password FROM users WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['userId'] = $user['userId'];
            
            // Update last_login
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            $update_stmt->close();
            
            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error_msg'] = "Invalid password!";
        }
    } else {
        $_SESSION['error_msg'] = "Invalid User ID!";
    }
    
    $stmt->close();
    $conn->close();
    
    header("Location: ../index.php");
    exit();
}
?>
