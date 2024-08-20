<?php
session_start();
include('connection/dbconfig.php');

// Fetch all records from the qp_split_up table
$query = "SELECT * FROM qp_split_up";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inserted Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .table-responsive {
            margin-bottom: 20px;
        }
        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
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
</header><br><br>
    <div class="container">
            <h2>Inserted Data</h2>
        <div class="table-responsive">
            <table class="table table-bordered" border="px solid black">
                <thead border="2px">
                    <tr>
                        <th>Sl. No.</th>
                        <th>Semester</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Which IA</th>
                        <th>Total Marks</th>
                        <th>Questions Data</th>
                        <th>CO Data</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $sl_no = 1;
                        while($row = mysqli_fetch_assoc($result)) {
                            $questions_data = json_decode($row['questions_data'], true);
                            $formatted_questions_data = '';
                            if (json_last_error() === JSON_ERROR_NONE && is_array($questions_data)) {
                                foreach ($questions_data as $key => $value) {
                                    if (is_array($value)) {
                                        $value = implode(', ', $value); // convert sub-array to string
                                    }
                                    $formatted_questions_data .= htmlspecialchars("{$key}: {$value}"). "<br>";
                                }
                            } else {
                                $formatted_questions_data = htmlspecialchars($row['questions_data']); // handle case where decoding fails
                            }

                            $co_data = json_decode($row['co_data'], true);
                            $formatted_co_data = '';
                            if (json_last_error() === JSON_ERROR_NONE && is_array($co_data)) {
                                foreach ($co_data as $key => $value) {
                                    $formatted_co_data .= htmlspecialchars("{$key}: {$value}"). "<br>";
                                }
                            } else {
                                $formatted_co_data = htmlspecialchars($row['co_data']); // handle case where decoding fails
                            }

                            echo "<tr>
                                <td>". htmlspecialchars($sl_no). "</td>
                                <td>". htmlspecialchars($row['semester']). "</td>
                                <td>". htmlspecialchars($row['sub_code']). "</td>
                                <td>". htmlspecialchars($row['sub_name']). "</td>
                                <td>". htmlspecialchars($row['which_ia']). "</td>
                                <td>". htmlspecialchars($row['total_marks']). "</td>
                                <td>{$formatted_questions_data}</td>
                                <td>{$formatted_co_data}</td>
                                <td>
                                    <a href='edit_qp_split_data.php?id={$row['id']}' class='btn btn-warning btn-sm me-1'>Edit</a>
                                    <a href='delete_qp_split_data.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                                </td>
                            </tr>";
                            $sl_no++;
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="qp_split_up.php" class="btn btn-primary">Back</a>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>
