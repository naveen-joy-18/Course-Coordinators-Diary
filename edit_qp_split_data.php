<?php
session_start();
include('connection/dbconfig.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the record from the database
    $query = "SELECT * FROM qp_split_up WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Record not found.";
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $semester = $_POST['semester'];
    $sub_code = $_POST['sub_code'];
    $sub_name = $_POST['sub_name'];
    $which_ia = $_POST['which_ia'];
    $total_marks = $_POST['total_marks'];
    $questions_data = $_POST['questions_data'];

    $query = "UPDATE qp_split_up SET semester = '$semester', sub_code = '$sub_code', sub_name = '$sub_name', which_ia = '$which_ia', total_marks = '$total_marks', questions_data = '$questions_data' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: view_qp_split_data.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="style/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
</header> <br><br><br>
    <div class="container mt-5">
        <h2>Edit Data</h2>
        <form method="POST" action="edit_qp_split_data.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" value="<?php echo $row['semester']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="sub_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="sub_code" name="sub_code" value="<?php echo $row['sub_code']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="sub_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="sub_name" name="sub_name" value="<?php echo $row['sub_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="which_ia" class="form-label">Which IA</label>
                <input type="text" class="form-control" id="which_ia" name="which_ia" value="<?php echo $row['which_ia']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="total_marks" class="form-label">Total Marks</label>
                <input type="text" class="form-control" id="total_marks" name="total_marks" value="<?php echo $row['total_marks']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="questions_data" class="form-label">Questions Data</label>
                <textarea class="form-control" id="questions_data" name="questions_data" required><?php echo htmlspecialchars($row['questions_data']); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>
