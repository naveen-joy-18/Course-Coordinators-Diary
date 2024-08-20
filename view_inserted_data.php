<?php
session_start();
include('connection/dbconfig.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inserted Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="style/styles.css">
    <style>
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }

        .button:hover {
            background-color: #0056b3;
        }

        h1, h2 {
            text-align: center;
        }

        @media (max-width: 600px) {
            .input-group input {
                flex: 1 1 100%;
            }

            button {
                padding: 8px 16px;
            }
        }

        .table-responsive {
            margin-top: 20px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
        }

        .action-buttons a {
            margin: 5px;
        }

        @media (max-width: 576px) {
            .table-responsive {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tr {
                display: block;
                margin-bottom: 10px;
            }

            .table td {
                display: block;
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 70%;
                padding-right: 10px;
                text-align: left;
                white-space: nowrap;
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
</header>

<div class="container">
    <h1 class="my-4">View Inserted Data</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Prepare a DELETE statement
        $sql_delete = "DELETE FROM Subjects WHERE id = ?";

        $stmt_delete = $conn->prepare($sql_delete);
        if ($stmt_delete === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        $stmt_delete->bind_param("i", $delete_id);

        // Execute the statement
        if ($stmt_delete->execute()) {
            echo '<div id="deleteSuccessAlert" class="alert alert-success" role="alert">Record deleted successfully.</div>';
        } else {
            echo '<div id="deleteErrorAlert" class="alert alert-danger" role="alert">Error deleting record.</div>';
        }

        $stmt_delete->close();
    }

    // Fetch data from database
    $sql_select = "SELECT id, semester, sub_code, sub_name, total_marks, num_internals, max_marks_each_internal, theory_ia_marks, mini_project_marks, avg FROM Subjects";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th scope="col">Semester</th>';
        echo '<th scope="col">Subject Code</th>';
        echo '<th scope="col">Subject Name</th>';
        echo '<th scope="col">Total Marks</th>';
        echo '<th scope="col">Number of Internals</th>';
        echo '<th scope="col">Max Marks of Each Internal</th>';
        echo '<th scope="col">Theory IA Marks</th>';
        echo '<th scope="col">Mini Project Marks</th>';
        echo '<th scope="col">Average</th>';
        echo '<th scope="col">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td data-label="Semester">' . $row["semester"] . '</td>';
            echo '<td data-label="Subject Code">' . $row["sub_code"] . '</td>';
            echo '<td data-label="Subject Name">' . $row["sub_name"] . '</td>';
            echo '<td data-label="Total Marks">' . $row["total_marks"] . '</td>';
            echo '<td data-label="Number of Internals">' . $row["num_internals"] . '</td>';
            echo '<td data-label="Max Marks of Each Internal">' . $row["max_marks_each_internal"] . '</td>';
            echo '<td data-label="Theory IA Marks">' . $row["theory_ia_marks"] . '</td>';
            echo '<td data-label="Mini Project Marks">' . $row["mini_project_marks"] . '</td>';
            echo '<td data-label="avg">' . $row["avg"] . '</td>';
            echo '<td data-label="Actions" class="action-buttons">';
            echo '<a href="edit_subject.php?id=' . $row["id"] . '" class="btn btn-primary btn-sm">Edit</a>';
            echo '<a href="view_inserted_data.php?delete_id=' . $row["id"] . '" class="btn btn-danger btn-sm">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning" role="alert">No records found.</div>';
    }

    $conn->close();
    ?>
</div>

<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $("#deleteSuccessAlert").fadeOut("slow", function(){
                $(this).remove();
            });
            $("#deleteErrorAlert").fadeOut("slow", function(){
                $(this).remove();
            });
        }, 3000); // 3 seconds
    });
</script>

</body>
</html>
