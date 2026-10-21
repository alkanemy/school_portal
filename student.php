<?php
$conn = mysqli_connect("localhost", "root", "", "s_chool_db45");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Check</title>
</head>

<body class="bg-light">
    <?php include 'nav.php'; ?>

    <div class="container">
        <h2>Student Check</h2>

        <form method="POST" action="student.php" class="row g-2 mb-4">
            <div class="col-md-8">
                <input type="text" name="search_name" class="form-control" placeholder="Enter student name" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_name = mysqli_real_escape_string($conn, trim($_POST['search_name']));
            $sql = "SELECT * FROM students WHERE name LIKE '%$search_name%'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<table class="table table-bordered bg-white">';
                echo '<thead><tr><th>Name</th><th>Score</th><th>Status</th></tr></thead><tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $badge = ($row['score'] >= 50)
                        ? '<span class="badge bg-success">Pass</span>'
                        : '<span class="badge bg-danger">Fail</span>';
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . $row['score'] . '</td>';
                    echo '<td>' . $badge . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning">No student found.</div>';
            }
        }
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>