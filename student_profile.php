
<?php
include 'auth.php';
include 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_students.php");
    exit();
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: view_students.php");
    exit();
}

// Get requirements summary
$req_sql = "SELECT
            COUNT(*) AS total,
            SUM(requirement_status = 'Submitted') AS submitted
            FROM student_requirements
            WHERE student_id = ?";

$req_stmt = mysqli_prepare($conn, $req_sql);
mysqli_stmt_bind_param($req_stmt, "i", $id);
mysqli_stmt_execute($req_stmt);

$req_result = mysqli_stmt_get_result($req_stmt);
$req_data = mysqli_fetch_assoc($req_result);

$total_requirements = $req_data['total'] ?? 0;
$submitted_requirements = $req_data['submitted'] ?? 0;
$pending_requirements = $total_requirements - $submitted_requirements;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-container">

    <div class="sidebar">
        <h2>ENROLLMENT SYSTEM</h2>

        <a href="index.php">Dashboard</a>
        <a href="add_student.php">Add Student</a>
        <a href="view_students.php">View Students</a>
        <a href="features.php">System Features</a>
        <a href="report.php">Student Reports</a>
        <a href="manage_admins.php">Admin Management</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">

        <div class="page-header">
            <h2>Student Profile</h2>

            <a href="view_students.php" class="secondary-button">
                Back to Students
            </a>
        </div>

        <div class="content-card">

            <h3>
                <?= htmlspecialchars(
                    $student['first_name'] . " " . $student['last_name']
                ); ?>
            </h3>

            <hr>

            <h3>Personal Information</h3>

            <p><strong>Student ID:</strong>
                <?= $student['student_id']; ?>
            </p>

            <p><strong>First Name:</strong>
                <?= htmlspecialchars($student['first_name']); ?>
            </p>

            <p><strong>Last Name:</strong>
                <?= htmlspecialchars($student['last_name']); ?>
            </p>

            <p><strong>Gender:</strong>
                <?= htmlspecialchars($student['gender']); ?>
            </p>

            <p><strong>Birthday:</strong>
                <?= htmlspecialchars($student['birthday']); ?>
            </p>

            <p><strong>Address:</strong>
                <?= htmlspecialchars($student['address']); ?>
            </p>

            <p><strong>Contact Number:</strong>
                <?= htmlspecialchars($student['contact_number']); ?>
            </p>

            <hr>

            <h3>Enrollment Information</h3>

            <p><strong>School Year:</strong>
                <?= htmlspecialchars($student['school_year'] ?? 'Not Set'); ?>
            </p>

            <p><strong>Semester:</strong>
                <?= htmlspecialchars($student['semester'] ?? 'Not Set'); ?>
            </p>

            <p><strong>Enrollment Status:</strong>
                <?= htmlspecialchars($student['enrollment_status']); ?>
            </p>

            <p><strong>Enrollment Date:</strong>
                <?= htmlspecialchars($student['enrollment_date'] ?? 'Not Set'); ?>
            </p>

            <hr>

            <h3>Enrollment Requirements</h3>

            <p><strong>Total Requirements:</strong>
                <?= $total_requirements; ?>
            </p>

            <p><strong>Submitted:</strong>
                <?= $submitted_requirements; ?>
            </p>

            <p><strong>Pending:</strong>
                <?= $pending_requirements; ?>
            </p>

            <div class="form-buttons">

                <a class="primary-button"
                   href="requirements.php?id=<?= $student['student_id']; ?>">
                    Manage Requirements
                </a>

                <a class="primary-button"
                   href="edit_student.php?id=<?= $student['student_id']; ?>">
                    Edit Student
                </a>

                <a class="primary-button"
                   href="enrollment_receipt.php?id=<?= $student['student_id']; ?>">
                    Print Enrollment Receipt
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
