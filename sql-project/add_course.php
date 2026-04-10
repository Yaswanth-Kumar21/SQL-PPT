<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $userId = $_SESSION['userId']; // Storing userId string based on schema

    if (!empty($course_name)) {
        // Prevent adding the same course twice (optional but good idea)
        $check_stmt = $conn->prepare("SELECT id FROM courses WHERE user_id = ? AND course_name = ?");
        $check_stmt->bind_param("ss", $userId, $course_name);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();
        
        if ($check_res->num_rows == 0) {
            $check_stmt->close();
            
            $stmt = $conn->prepare("INSERT INTO courses (user_id, course_name) VALUES (?, ?)");
            $stmt->bind_param("ss", $userId, $course_name);
            $stmt->execute();
            $stmt->close();
        } else {
            $check_stmt->close();
        }
    }
}
$conn->close();
header("Location: dashboard.php");
exit();
?>
