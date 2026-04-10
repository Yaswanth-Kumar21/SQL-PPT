<?php
// setup.php
$host = 'localhost';
$user = 'root';
$pass = '';

// Connect without database
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS sql_project";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db('sql_project');

// Create Users Table
$users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME NULL,
    last_logout DATETIME NULL
)";
if ($conn->query($users_table) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// Create Courses Table
$courses_table = "CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(userId) ON DELETE CASCADE
)";
if ($conn->query($courses_table) === TRUE) {
    echo "Table 'courses' created successfully.<br>";
} else {
    echo "Error creating table 'courses': " . $conn->error . "<br>";
}

// Create Materials Table
$materials_table = "CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL
)";
if ($conn->query($materials_table) === TRUE) {
    echo "Table 'materials' created successfully.<br>";
} else {
    echo "Error creating table 'materials': " . $conn->error . "<br>";
}

// Insert Default Materials
$check_materials = "SELECT * FROM materials";
$res = $conn->query($check_materials);
if ($res && $res->num_rows == 0) {
    $insert = "INSERT INTO materials (title, file_path) VALUES 
        ('SQL Notes', 'materials/SQL Notes by Apna College.pdf'),
        ('ML Text Book', 'materials/ML TEXT BOOK.pdf')";
    if ($conn->query($insert) === TRUE) {
        echo "Default materials inserted successfully.<br>";
    } else {
        echo "Error inserting materials: " . $conn->error . "<br>";
    }
} else {
    echo "Default materials already exist.<br>";
}

echo "<br><b>Database setup complete!</b>";


$conn->close();
?>