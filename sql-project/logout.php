<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Update last_logout
    $stmt = $conn->prepare("UPDATE users SET last_logout = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // Destroy session
    session_unset();
    session_destroy();
}

header("Location: index.php");
exit();
?>
