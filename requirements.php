<?php
require_once 'auth.php';
require_once 'config/database.php';
require_once 'system_helpers.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid student ID.");
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE student_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

$defaults = [
    "Birth Certificate",
    "Good Moral Certificate",
    "Form 138 / Report Card",
    "2x2 ID Picture"
];

$check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM student_requirements WHERE student_id = $id");
$count = mysqli_fetch_assoc($check)['total'];

if ($count == 0) {
    foreach ($defaults as $requirement) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO student_requirements (student_id, requirement_name)
             VALUES (?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "is", $id, $requirement);
        mysqli_stmt_execute($stmt);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requirement_id = (int) $_POST['requirement_id'];
    $status = $_POST['status'];

    if (in_array($status, ['Pending', 'Submitted'])) {
        $stmt = mysqli_prepare($conn,
            "UPDATE student_requirements
             SET requirement_status = ?,
                 date_submitted = IF(? = 'Submitted', CURDATE(), NULL)
             WHERE requirement_id = ? AND student_id = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssii", $status, $status, $requirement_id, $id);
        mysqli_stmt_execute($stmt);

        logActivity($conn, "Requirement Updated",
            "Updated requirement for student ID: $id");

        createNotification($conn, "Requirement Updated",
            "A requirement was updated for " . $student['first_name'] . " " . $student['last_name']);
    }

    header("Location: requirements.php?id=$id");
    exit();
}

$requirements = mysqli_query($conn,
    "SELECT * FROM student_requirements WHERE student_id = $id ORDER BY requirement_id ASC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrollment Requirements</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Enrollment Requirements</h1>

    <h3>
        Student:
        <?= htmlspecialchars($student['first_name'] . " " . $student['last_name']) ?>
    </h3>

    <table>
        <tr>
            <th>Requirement</th>
            <th>Status</th>
            <th>Date Submitted</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($requirements)): ?>
        <tr>
            <td><?= htmlspecialchars($row['requirement_name']) ?></td>
            <td><?= htmlspecialchars($row['requirement_status']) ?></td>
            <td><?= htmlspecialchars($row['date_submitted'] ?? 'Not submitted') ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="requirement_id"
                           value="<?= $row['requirement_id'] ?>">

                    <select name="status">
                        <option value="Pending"
                            <?= $row['requirement_status'] == 'Pending' ? 'selected' : '' ?>>
                            Pending
                        </option>

                        <option value="Submitted"
                            <?= $row['requirement_status'] == 'Submitted' ? 'selected' : '' ?>>
                            Submitted
                        </option>
                    </select>

                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="student_profile.php?id=<?= $id ?>">Back to Student Profile</a>
    <br><br>
    <a href="index.php">Dashboard</a>
</div>

</body>
</html>