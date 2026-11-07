
<?php
include 'auth.php';
include 'config/database.php';

$message = "";

if (isset($_POST['add_student'])) {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $enrollment_status = $_POST['enrollment_status'];
    $school_year = trim($_POST['school_year']);
    $semester = $_POST['semester'];

    $sql = "INSERT INTO students
            (first_name, last_name, gender, birthday, address,
             contact_number, enrollment_status, enrollment_date,
             school_year, semester)
            VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssss",
        $first_name,
        $last_name,
        $gender,
        $birthday,
        $address,
        $contact_number,
        $enrollment_status,
        $school_year,
        $semester
    );

    if (mysqli_stmt_execute($stmt)) {

        $student_id = mysqli_insert_id($conn);

        // Add default enrollment requirements
        $requirements = [
            "Birth Certificate",
            "Good Moral Certificate",
            "Form 138 / Report Card",
            "2x2 ID Picture"
        ];

        $requirement_sql = "INSERT INTO student_requirements
                            (student_id, requirement_name)
                            VALUES (?, ?)";

        $requirement_stmt = mysqli_prepare($conn, $requirement_sql);

        foreach ($requirements as $requirement) {
            mysqli_stmt_bind_param(
                $requirement_stmt,
                "is",
                $student_id,
                $requirement
            );

            mysqli_stmt_execute($requirement_stmt);
        }

        mysqli_stmt_close($requirement_stmt);

        $message = "Student added successfully!";

    } else {
        $message = "Unable to add student. Please check your database.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
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
            <h2>Add New Student</h2>
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
                        <input type="text" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>

                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Birthday</label>
                        <input type="date" name="birthday" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Address</label>
                        <input type="text" name="address" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" required>
                    </div>

                    <div class="form-group">
                        <label>School Year</label>
                        <input
                            type="text"
                            name="school_year"
                            placeholder="Example: 2026-2027"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Semester</label>

                        <select name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Enrollment Status</label>

                        <select name="enrollment_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Enrolled">Enrolled</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>

                </div>

                <div class="form-buttons">

                    <button type="submit" name="add_student"
                            class="primary-button">
                        Save Student
                    </button>

                    <a href="view_students.php"
                       class="secondary-button">
                        View Students
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
