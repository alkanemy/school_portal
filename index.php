<?php
$conn = mysqli_connect("localhost", "root", "", "s_chool_db45");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete before any HTML is output
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM students WHERE id = $delete_id");
    mysqli_close($conn);
    header("Location: index.php");
    exit();
}

// Handle add student
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['student_name'] ?? '');
    $score = trim($_POST['score'] ?? '');

    if ($name === "") {
        $message = '<div class="alert alert-danger">Student name cannot be empty.</div>';
    } elseif (!is_numeric($score)) {
        $message = '<div class="alert alert-danger">Score must be a number.</div>';
    } else {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $safe_score = (int) $score;
        mysqli_query($conn, "INSERT INTO students (name, score) VALUES ('$safe_name', $safe_score)");
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }
}

$result = mysqli_query($conn, "SELECT * FROM students");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body class="bg-light">
    <?php include 'nav.php'; ?>

    <div class="container">
        <h2>Admin Dashboard</h2>

        <?= $message ?>

        <form method="POST" action="index.php" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="score" class="form-control" placeholder="Score" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">Add Student</button>
            </div>
        </form>

        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['score'] ?></td>
                        <td>
                            <?php if ($row['score'] >= 50): ?>
                                <span class="badge bg-success">Pass</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Fail</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?delete_id=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php mysqli_close($conn); ?>
</body>

</html>