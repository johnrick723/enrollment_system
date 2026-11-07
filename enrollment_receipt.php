
<?php
include 'auth.php';
include 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_students.php");
    exit();
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: view_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrollment Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            padding: 30px;
        }

        .receipt {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 35px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h2 {
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .details p {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 45px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
            text-align: center;
        }

        .signature {
            border-top: 1px solid black;
            padding-top: 8px;
            width: 45%;
        }

        .buttons {
            text-align: center;
            margin-top: 25px;
        }

        button, a {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
            margin: 5px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt {
                border: none;
            }

            .buttons {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="receipt">

    <div class="header">
        <h2>ENROLLMENT SYSTEM</h2>
        <p>STUDENT ENROLLMENT RECEIPT</p>
    </div>

    <div class="details">

        <p><strong>Receipt No.:</strong>
            <?= $student['student_id']; ?>
        </p>

        <p><strong>Student Name:</strong>
            <?= htmlspecialchars(
                $student['first_name'] . " " . $student['last_name']
            ); ?>
        </p>

        <p><strong>Gender:</strong>
            <?= htmlspecialchars($student['gender']); ?>
        </p>

        <p><strong>Birthday:</strong>
            <?= htmlspecialchars($student['birthday']); ?>
        </p>

        <p><strong>Address:</strong>
            <?= htmlspecialchars($student['address']); ?>
        </p>

        <p><strong>Contact Number:</strong>
            <?= htmlspecialchars($student['contact_number']); ?>
        </p>

        <p><strong>School Year:</strong>
            <?= htmlspecialchars($student['school_year'] ?? 'Not Set'); ?>
        </p>

        <p><strong>Semester:</strong>
            <?= htmlspecialchars($student['semester'] ?? 'Not Set'); ?>
        </p>

        <p><strong>Enrollment Date:</strong>
            <?= htmlspecialchars($student['enrollment_date'] ?? ''); ?>
        </p>

        <p><strong>Enrollment Status:</strong>
            <span class="status">
                <?= htmlspecialchars($student['enrollment_status']); ?>
            </span>
        </p>

    </div>

    <div class="footer">
        <div class="signature">
            Student Signature
        </div>

        <div class="signature">
            Authorized Signature
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.print()">Print Receipt</button>
        <a href="student_profile.php?id=<?= $student['student_id']; ?>">Back</a>
    </div>

</div>

</body>
</html>
