<?php
require_once 'auth.php';
require_once 'config/database.php';

if (isset($_POST['mark_all'])) {
    mysqli_query($conn, "UPDATE notifications SET is_read = 1");
    header("Location: notifications.php");
    exit();
}

if (isset($_POST['delete_notification'])) {
    $id = (int) $_POST['delete_notification'];

    $stmt = mysqli_prepare($conn,
        "DELETE FROM notifications WHERE notification_id = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: notifications.php");
    exit();
}

$unread = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM notifications WHERE is_read = 0"
);
$unread_count = mysqli_fetch_assoc($unread)['total'];

$notifications = mysqli_query($conn,
    "SELECT * FROM notifications ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Notifications</h1>

    <h3>Unread Notifications: <?= $unread_count ?></h3>

    <form method="POST">
        <button type="submit" name="mark_all">
            Mark All as Read
        </button>
    </form>

    <br>

    <table>
        <tr>
            <th>Title</th>
            <th>Message</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($notifications)): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td><?= $row['is_read'] ? 'Read' : 'Unread' ?></td>
            <td>
                <form method="POST">
                    <button type="submit"
                        name="delete_notification"
                        value="<?= $row['notification_id'] ?>">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="index.php">Back to Dashboard</a>
</div>

</body>
</html>