
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

$message = "";

if (isset($_POST['update_student'])) {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $enrollment_status = $_POST['enrollment_status'];
    $school_year = trim($_POST['school_year']);
    $semester = $_POST['semester'];

    $sql = "UPDATE students SET
            first_name = ?,
            last_name = ?,
            gender = ?,
            birthday = ?,
            address = ?,
            contact_number = ?,
            enrollment_status = ?,
            school_year = ?,
            semester = ?
            WHERE student_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssi",
        $first_name,
        $last_name,
        $gender,
        $birthday,
        $address,
        $contact_number,
        $enrollment_status,
        $school_year,
        $semester,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: view_students.php");
        exit();

    } else {
        $message = "Unable to update student.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
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
        <a href="logout.php">Logout</a>

    </div>

    <div class="main-content">

        <div class="page-header">
            <h2>Edit Student Information</h2>
        </div>

        <div class="form-card">

            <?php if ($message != ""): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text"
                               name="first_name"
                               value="<?= htmlspecialchars($student['first_name']) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text"
                               name="last_name"
                               value="<?= htmlspecialchars($student['last_name']) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>

                        <select name="gender" required>
                            <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Birthday</label>
                        <input type="date"
                               name="birthday"
                               value="<?= htmlspecialchars($student['birthday']) ?>"
                               required>
                    </div>

                    <div class="form-group full-width">
                        <label>Address</label>
                        <input type="text"
                               name="address"
                               value="<?= htmlspecialchars($student['address']) ?>"
                               required>
                    </div>

                    <div class="form-group full-width">
                        <label>Contact Number</label>
                        <input type="text"
                               name="contact_number"
                               value="<?= htmlspecialchars($student['contact_number']) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>School Year</label>
                        <input type="text"
                               name="school_year"
                               value="<?= htmlspecialchars($student['school_year'] ?? '') ?>"
                               placeholder="Example: 2026-2027"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Semester</label>

                        <select name="semester" required>
                            <option value="First Semester" <?= ($student['semester'] ?? '') == 'First Semester' ? 'selected' : '' ?>>First Semester</option>
                            <option value="Second Semester" <?= ($student['semester'] ?? '') == 'Second Semester' ? 'selected' : '' ?>>Second Semester</option>
                            <option value="Summer" <?= ($student['semester'] ?? '') == 'Summer' ? 'selected' : '' ?>>Summer</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Enrollment Status</label>

                        <select name="enrollment_status" required>
                            <option value="Pending" <?= $student['enrollment_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Enrolled" <?= $student['enrollment_status'] == 'Enrolled' ? 'selected' : '' ?>>Enrolled</option>
                            <option value="Rejected" <?= $student['enrollment_status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                </div>

                <div class="form-buttons">

                    <button type="submit"
                            name="update_student"
                            class="primary-button">
                        Update Student
                    </button>

                    <a href="view_students.php"
                       class="secondary-button">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
