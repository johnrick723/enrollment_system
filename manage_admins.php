
<?php
include 'auth.php';
include 'config/database.php';

$message = "";

if (isset($_POST['add_admin'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || strlen($password) < 6) {
        $message = "Enter a username and a password with at least 6 characters.";
    } else {

        $check = mysqli_prepare(
            $conn,
            "SELECT admin_id FROM admins WHERE username = ?"
        );

        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        $check_result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Username already exists.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO admins (username, password) VALUES (?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $username,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "New admin account created successfully!";
            } else {
                $message = "Unable to create admin account.";
            }
        }
    }
}

if (isset($_POST['delete_admin'])) {

    $admin_id = (int) $_POST['admin_id'];

    if ($admin_id == $_SESSION['admin_id']) {
        $message = "You cannot delete your own account.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM admins WHERE admin_id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $admin_id);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Admin account deleted.";
        } else {
            $message = "Unable to delete admin account.";
        }
    }
}

$result = mysqli_query(
    $conn,
    "SELECT admin_id, username FROM admins ORDER BY admin_id ASC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Management</title>
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
        <a href="manage_admins.php">Admin Management</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">

        <h2>Admin Management</h2>
        <p>Create and manage administrator accounts.</p>

        <?php if ($message != ""): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">

            <h3>Create New Admin</h3>

            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               minlength="6"
                               required>
                    </div>

                </div>

                <div class="form-buttons">
                    <button type="submit"
                            name="add_admin"
                            class="primary-button">
                        Create Admin
                    </button>
                </div>

            </form>

        </div>

        <div class="content-card" style="margin-top:25px;">

            <h3>Admin Accounts</h3>

            <div class="table-wrapper">

                <table>

                    <tr>
                        <th>Admin ID</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>

                    <?php while ($admin = mysqli_fetch_assoc($result)): ?>

                        <tr>
                            <td><?php echo $admin['admin_id']; ?></td>

                            <td><?php echo htmlspecialchars($admin['username']); ?></td>

                            <td>

                                <?php if ($admin['admin_id'] == $_SESSION['admin_id']): ?>

                                    <span>Current Account</span>

                                <?php else: ?>

                                    <form method="POST"
                                          onsubmit="return confirm('Delete this admin account?');">

                                        <input type="hidden"
                                               name="admin_id"
                                               value="<?php echo $admin['admin_id']; ?>">

                                        <button type="submit"
                                                name="delete_admin"
                                                class="delete-button">
                                            Delete
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </td>
                        </tr>

                    <?php endwhile; ?>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
