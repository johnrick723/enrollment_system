<?php
function logActivity($conn, $action, $details) {
    $admin_id = $_SESSION['admin_id'] ?? null;

    $stmt = mysqli_prepare($conn,
        "INSERT INTO activity_logs (admin_id, action, details)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "iss", $admin_id, $action, $details);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function createNotification($conn, $title, $message) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO notifications (title, message)
         VALUES (?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "ss", $title, $message);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>