
<?php
include 'auth.php';
include 'config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$status = isset($_GET['status']) ? $_GET['status'] : "";

$allowed_status = ["Pending", "Enrolled", "Rejected"];

if (!in_array($status, $allowed_status)) {
    $status = "";
}

$sql = "SELECT * FROM students
        WHERE (? = '' OR enrollment_status = ?)
        AND (
            first_name LIKE ?
            OR last_name LIKE ?
            OR contact_number LIKE ?
        )
        ORDER BY student_id DESC";

$stmt = mysqli_prepare($conn, $sql);

$search_value = "%" . $search . "%";

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $status,
    $status,
    $search_value,
    $search_value,
    $search_value
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
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
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">

        <div class="page-header">
            <h2>Student Records</h2>

            <a href="add_student.php" class="primary-button">
                + Add Student
            </a>
        </div>

        <div class="content-card">

            <form method="GET" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search student..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

                <select name="status">
                    <option value="">All Status</option>

                    <option value="Pending"
                        <?php if ($status == "Pending") echo "selected"; ?>>
                        Pending
                    </option>

                    <option value="Enrolled"
                        <?php if ($status == "Enrolled") echo "selected"; ?>>
                        Enrolled
                    </option>

                    <option value="Rejected"
                        <?php if ($status == "Rejected") echo "selected"; ?>>
                        Rejected
                    </option>
                </select>

                <button type="submit" class="primary-button">
                    Search
                </button>

                <a href="view_students.php" class="secondary-button">
                    Reset
                </a>

            </form>

            <div class="table-wrapper">

                <table>

                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?php echo $row['student_id']; ?></td>

                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>

                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>

                                <td><?php echo htmlspecialchars($row['gender']); ?></td>

                                <td><?php echo htmlspecialchars($row['birthday']); ?></td>

                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>

                                <td><?php echo htmlspecialchars($row['enrollment_status']); ?></td>

                                <td><?php echo htmlspecialchars($row['enrollment_date'] ?? ''); ?></td>

                                <td>
                                    <a class="action-link"
                                       href="student_profile.php?id=<?php echo $row['student_id']; ?>">
                                        Profile
                                    </a>

                                    <a class="action-link"
                                       href="edit_student.php?id=<?php echo $row['student_id']; ?>">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="delete_student.php"
                                          style="display:inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this student?');">

                                        <input type="hidden"
                                               name="id"
                                               value="<?php echo $row['student_id']; ?>">

                                        <button type="submit" class="delete-button">
                                            Delete
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9">No student records found.</td>
                        </tr>

                    <?php endif; ?>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
