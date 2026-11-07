
<?php
include 'config/database.php';

$message = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $username,
        $hashed_password
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Admin Account Created Successfully!";
    } else {
        $message = "Username already exists or registration failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Create Admin Account</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

    Username:<br>
    <input type="text" name="username" required>
    <br><br>

    Password:<br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="register">
        Create Account
    </button>

</form>

</body>
</html>
