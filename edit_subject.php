<?php
session_start();
include('connection/dbconfig.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <link rel="stylesheet" href="style/styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header class="header">
    <nav class="nav">
        <div class="logo-container">
  <img src="image/logo.png" alt="Logo" class="logo-image">
        </div>
        <div class="logo">Course Coordinator's Diary</div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h1>Edit Subject</h1>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $semester = $_POST['semester'];
        $sub_code = $_POST['sub_code'];
        $sub_name = $_POST['sub_name'];
        $total_marks = $_POST['total_marks'];
        $num_internals = $_POST['num_internals'];
        $max_marks_each_internal = $_POST['max_marks_each_internal'];
        $theory_ia_marks = $_POST['theory_ia_marks'];
        $mini_project_marks = $_POST['mini_project_marks'];
        $avg=$_POST['avg'];

        // Prepare SQL statement
        $sql = "UPDATE Subjects SET semester=?, sub_code=?, sub_name=?, total_marks=?, num_internals=?, max_marks_each_internal=?, theory_ia_marks=?, mini_project_marks=? ,avg=? WHERE id=?";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        $stmt->bind_param("issiiiiiii", $semester, $sub_code, $sub_name, $total_marks, $num_internals, $max_marks_each_internal, $theory_ia_marks, $mini_project_marks,$avg, $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Record updated successfully.</div>";
            // Redirect to view_inserted_data.php after updating
            header("Location: view_inserted_data.php");
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error updating record: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Fetch subject details from database based on ID from GET parameter
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch subject details from database
        $sql = "SELECT * FROM Subjects WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="semester">Semester:</label>
                    <input type="number" class="form-control" id="semester" name="semester" value="<?php echo $row['semester']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="sub_code">Subject Code:</label>
                    <input type="text" class="form-control" id="sub_code" name="sub_code" value="<?php echo $row['sub_code']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="sub_name">Subject Name:</label>
                    <input type="text" class="form-control" id="sub_name" name="sub_name" value="<?php echo $row['sub_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="total_marks">Total Marks:</label>
                    <input type="number" class="form-control" id="total_marks" name="total_marks" value="<?php echo $row['total_marks']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="num_internals">Number of Internals:</label>
                    <input type="number" class="form-control" id="num_internals" name="num_internals" value="<?php echo $row['num_internals']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="max_marks_each_internal">Max Marks of Each Internal:</label>
                    <input type="number" class="form-control" id="max_marks_each_internal" name="max_marks_each_internal" value="<?php echo $row['max_marks_each_internal']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="theory_ia_marks">Theory IA Marks:</label>
                    <input type="number" class="form-control" id="theory_ia_marks" name="theory_ia_marks" value="<?php echo $row['theory_ia_marks']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="mini_project_marks">Mini Project Marks (Optional):</label>
                    <input type="number" class="form-control" id="mini_project_marks" name="mini_project_marks" value="<?php echo $row['mini_project_marks']; ?>">
                </div>
                <div class="form-group">
                    <label for="avg">Average Marks :</label>
                    <input type="number" class="form-control" id="avg" name="avg" value="<?php echo $row['avg']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="view_inserted_data.php" class="btn btn-secondary">Cancel</a>
            </form>
            <?php
        } else {
            echo "<p class='text-danger'>Subject not found.</p>";
        }
    } else {
        echo "<p class='text-danger'>Subject ID not provided.</p>";
    }

    $conn->close();
    ?>
</div>

<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
