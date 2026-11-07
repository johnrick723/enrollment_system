<?php
require_once 'auth.php';
require_once 'config/database.php';

$sql = "SELECT activity_logs.*, admins.username
        FROM activity_logs
        LEFT JOIN admins ON activity_logs.admin_id = admins.admin_id
        ORDER BY activity_logs.created_at DESC";

$logs = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Activity Logs</h1>

    <table>
        <tr>
            <th>Admin</th>
            <th>Action</th>
            <th>Details</th>
            <th>Date</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($logs)): ?>
        <tr>
            <td><?= htmlspecialchars($row['username'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['action']) ?></td>
            <td><?= htmlspecialchars($row['details']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="index.php">Back to Dashboard</a>
</div>

</body>
</html>