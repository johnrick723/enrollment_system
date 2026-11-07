
<?php
session_start();
include 'config/database.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];

            header("Location: index.php");
            exit();
        }
    }

    $message = "Incorrect username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-page">

    <div class="login-card">

        <h2>ENROLLMENT SYSTEM</h2>

        <p>Welcome Back! Please login to continue.</p>

        <?php if ($message != ""): ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Username</label>

                <input type="text"
                       name="username"
                       placeholder="Enter your username"
                       required>
            </div>

            <div class="form-group">
                <label>Password</label>

                <input type="password"
                       name="password"
                       placeholder="Enter your password"
                       required>
            </div>

            <button type="submit"
                    name="login"
                    class="primary-button">
                Login
            </button>

        </form>

        <p style="margin-top:25px; font-size:12px;">
            Enrollment Management System
        </p>

    </div>

</div>

</body>
</html>
