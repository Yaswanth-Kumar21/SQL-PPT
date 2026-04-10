<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userId = $_SESSION['userId'];

// Fetch user info for last login/logout
$stmt = $conn->prepare("SELECT last_login, last_logout FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch materials
$materials = [];
$mat_res = $conn->query("SELECT title, file_path FROM materials");
if ($mat_res) {
    while ($row = $mat_res->fetch_assoc()) {
        $materials[] = $row;
    }
}

// Fetch user courses
$courses = [];
$course_stmt = $conn->prepare("SELECT course_name FROM courses WHERE user_id = ?");
$course_stmt->bind_param("s", $userId); // Note: storing userId string in courses table per requirements
$course_stmt->execute();
$c_res = $course_stmt->get_result();
if ($c_res) {
    while ($row = $c_res->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
}
$course_stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Learning System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container dashboard-container">
    <div class="dashboard-header">
        <h2>Welcome, <?php echo htmlspecialchars($userId); ?>! 👋</h2>
        <form action="logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn danger" style="width: auto; padding: 10px 20px;">Logout</button>
        </form>
    </div>

    <div class="user-info">
        <div>
            User ID: <span><?php echo htmlspecialchars($userId); ?></span>
        </div>
        <div>
            Last Login: <span><?php echo $user_info['last_login'] ? $user_info['last_login'] : 'N/A'; ?></span>
        </div>
        <div>
            Last Logout: <span><?php echo $user_info['last_logout'] ? $user_info['last_logout'] : 'N/A'; ?></span>
        </div>
    </div>

    <div class="grid">
        <!-- Learning Materials Section -->
        <div class="card">
            <h3>📚 Learning Materials</h3>
            <?php if (count($materials) > 0): ?>
                <?php foreach ($materials as $mat): ?>
                    <a href="<?php echo htmlspecialchars($mat['file_path']); ?>" target="_blank" class="material-link">
                        📄 <?php echo htmlspecialchars($mat['title']); ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No materials found.</p>
            <?php endif; ?>
        </div>

        <!-- Courses Section -->
        <div class="card">
            <h3>🎓 My Courses</h3>
            <?php if (count($courses) > 0): ?>
                <ul class="course-list">
                    <?php foreach ($courses as $c): ?>
                        <li><?php echo htmlspecialchars($c); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="margin-bottom: 15px; color: rgba(255,255,255,0.7);">You haven't added any courses yet.</p>
            <?php endif; ?>
            
            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">
            
            <form action="add_course.php" method="POST">
                <div class="form-group">
                    <label>Add a New Course</label>
                    <select name="course_name" required>
                        <option value="" disabled selected>Select a Course</option>
                        <option value="AI">AI</option>
                        <option value="ML">ML</option>
                        <option value="SQL">SQL</option>
                        <option value="Data Science">Data Science</option>
                    </select>
                </div>
                <button type="submit" class="btn">Add Course</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
