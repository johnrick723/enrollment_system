
<?php
include 'auth.php';
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {

    $id = (int) $_POST['id'];

    if ($id > 0) {

        $sql = "DELETE FROM students WHERE student_id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: view_students.php");
exit();
?>
