<?php
// db.php
$host = 'localhost';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP password
$db_name = 'sql_project';

// Create connection
$conn = new mysqli($host, $user, $pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
