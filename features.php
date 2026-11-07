
<?php
require_once 'auth.php';
require_once 'config/database.php';

$students = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students")
)['total'];

$requirements = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM student_requirements")
)['total'];

$logs = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM activity_logs")
)['total'];

$notifications = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM notifications WHERE is_read = 0")
)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Features</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>System Features</h1>
    <p>Manage the additional enrollment system features here.</p>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Students</h3>
            <h2><?= $students ?></h2>
        </div>

        <div class="card">
            <h3>Requirements</h3>
            <h2><?= $requirements ?></h2>
        </div>

        <div class="card">
            <h3>Activity Logs</h3>
            <h2><?= $logs ?></h2>
        </div>

        <div class="card">
            <h3>Unread Notifications</h3>
            <h2><?= $notifications ?></h2>
        </div>
    </div>

    <hr>

    <h2>Available Features</h2>

    <p><a href="view_students.php">Student Management</a></p>
    <p><a href="report.php">Enrollment Reports</a></p>
    <p><a href="activity_logs.php">Activity Logs</a></p>
    <p><a href="notifications.php">Notifications</a></p>
    <p><a href="manage_admins.php">Manage Admin Accounts</a></p>

    <br>
    <a href="index.php">Back to Dashboard</a>
</div>

</body>
</html>
