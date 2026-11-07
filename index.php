
<?php
include 'auth.php';
include 'config/database.php';

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$total_students = mysqli_fetch_assoc($total_query)['total'];

$enrolled_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Enrolled'");
$enrolled_students = mysqli_fetch_assoc($enrolled_query)['total'];

$pending_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Pending'");
$pending_students = mysqli_fetch_assoc($pending_query)['total'];

$rejected_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Rejected'");
$rejected_students = mysqli_fetch_assoc($rejected_query)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-container">

    <div class="sidebar">
        <h2>ENROLLMENT SYSTEM</h2>
        <a href="index.php">Dashboard</a>
        <a href="add_student.php">Add Student</a>
        <a href="view_students.php">View Students</a>
        <a href="report.php">Student Reports</a>
        <a href="manage_admins.php">Admin Management</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">

        <h2>Admin Dashboard</h2>

        <p>
            Welcome,
            <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </p>

        <div style="display:flex; flex-wrap:wrap; gap:15px;">

            <div class="dashboard-card">
                <h3>Total Students</h3>
                <h1><?php echo $total_students; ?></h1>
            </div>

            <div class="dashboard-card">
                <h3>Enrolled Students</h3>
                <h1><?php echo $enrolled_students; ?></h1>
            </div>

            <div class="dashboard-card">
                <h3>Pending Students</h3>
                <h1><?php echo $pending_students; ?></h1>
            </div>

            <div class="dashboard-card">
                <h3>Rejected Students</h3>
                <h1><?php echo $rejected_students; ?></h1>
            </div>

        </div>

        <div class="dashboard-actions">
            <a href="add_student.php">+ Add New Student</a>
            <a href="view_students.php">View All Students</a>
            <a href="report.php">Student Reports</a>
            <a href="manage_admins.php">Manage Admins</a>
        </div>

    </div>

</div>

</body>
</html>
