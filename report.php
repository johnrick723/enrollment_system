
<?php
include 'auth.php';
include 'config/database.php';

$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students")
)['total'];

$enrolled = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Enrolled'")
)['total'];

$pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Pending'")
)['total'];

$rejected = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Rejected'")
)['total'];

$sql = "SELECT * FROM students ORDER BY student_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Reports</title>
    <link rel="stylesheet" href="style.css">

    <style>
        @media print {
            .sidebar,
            .no-print {
                display: none !important;
            }

            .dashboard-container {
                display: block;
            }

            .main-content {
                padding: 0;
            }

            body {
                background: white;
            }

            .content-card {
                box-shadow: none;
            }

            th {
                background: #ddd !important;
                color: black !important;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <div class="sidebar no-print">
        <h2>ENROLLMENT SYSTEM</h2>
        <a href="index.php">Dashboard</a>
        <a href="add_student.php">Add Student</a>
        <a href="view_students.php">View Students</a>
        <a href="report.php">Student Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">

        <div class="page-header">
            <h2>Student Reports</h2>

            <button onclick="window.print()" class="primary-button no-print">
                Print Report
            </button>
        </div>

        <div class="content-card">

            <h3>Enrollment Summary</h3>

            <div style="display:flex; flex-wrap:wrap; gap:15px;">

                <div class="dashboard-card">
                    <h3>Total Students</h3>
                    <h1><?php echo $total; ?></h1>
                </div>

                <div class="dashboard-card">
                    <h3>Enrolled</h3>
                    <h1><?php echo $enrolled; ?></h1>
                </div>

                <div class="dashboard-card">
                    <h3>Pending</h3>
                    <h1><?php echo $pending; ?></h1>
                </div>

                <div class="dashboard-card">
                    <h3>Rejected</h3>
                    <h1><?php echo $rejected; ?></h1>
                </div>

            </div>

            <h3 style="margin-top:30px;">Complete Student List</h3>

            <div class="table-wrapper">

                <table>

                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Enrollment Date</th>
                    </tr>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?php echo $row['student_id']; ?></td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['first_name'] . " " . $row['last_name']
                                    ); ?>
                                </td>

                                <td><?php echo htmlspecialchars($row['gender']); ?></td>

                                <td><?php echo htmlspecialchars($row['birthday']); ?></td>

                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>

                                <td><?php echo htmlspecialchars($row['enrollment_status']); ?></td>

                                <td><?php echo htmlspecialchars($row['enrollment_date'] ?? ''); ?></td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">No student records found.</td>
                        </tr>

                    <?php endif; ?>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
