<?php
session_start();
include('connection/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected semester
    $semester = $_POST['semester'];

    // Determine the table name based on the selected semester
    switch ($semester) {
        case 'sem_1':
            $table = 'first_semester';
            break;
        case 'sem_2':
            $table = 'second_semester';
            break;
        case 'sem_3':
            $table = 'third_semester';
            break;
        case 'sem_4':
            $table = 'fourth_semester';
            break;
        case 'sem_5':
            $table = 'fifth_semester';
            break;
        case 'sem_6':
            $table = 'sixth_semester';
            break;
        case 'sem_7':
            $table = 'seventh_semester';
            break;
        case 'sem_8':
            $table = 'eighth_semester';
            break;
        default:
            $_SESSION['message'] = 'Invalid semester selected.';
            header('Location: student_info.php'); // Redirect to the form page
            exit();
    }

    // Get the selected fields
    $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

    if (empty($fields)) {
        $_SESSION['message'] = 'Please select at least one field to display.';
        header('Location: student_info.php'); // Redirect to the form page
        exit();
    }

    // Create the SQL query
    $fields_list = implode(', ', $fields);
    $query = "SELECT $fields_list FROM $table";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Query Failed: ' . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: auto;
            min-width: 300px; /* Minimum width */
            max-width: 90%; /* Allow flexibility based on content */
            text-align: center;
        }
        h2 {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-top: -30px;
            margin-bottom: 20px;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            word-wrap: break-word; /* Ensure words break appropriately */
        }

        th, td {
            padding: 12px;
            border: 1px solid #cccccc;
            text-align: left;
            white-space: nowrap; /* Prevent text wrapping */
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        @media print {
    #calculate-button,
    header,
    footer,
    button,.center-button {
        display: none;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin: 0;
        padding: 0;
    }
    tr, td, th {
        page-break-inside: avoid;
    }
    body {
        margin: 0;
        padding: 0;
    }
    @page {
        size: auto;
        margin: 0;
    }
}

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 22px;
            }

            th, td {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }

            h2 {
                font-size: 18px;
            }

            th, td {
                font-size: 12px;
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
</header> <br><br>
    <div class="container">
        <div class="printable">
            <br><br>
            <h2 style="color:black;">Student Data for <?php echo ucfirst(str_replace('_', ' ', $semester)); ?></h2>
            <table>
                <thead >
                    <tr style="color:black;">
                        <?php foreach ($fields as $field): ?>
                            <th><?php echo ucfirst($field); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <?php foreach ($fields as $field): ?>
                                <td><?php echo htmlspecialchars($row[$field]); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="center-button">
                <button class="btn btn-primary" onclick="printTable()">Print Table</button>
                <button class="btn btn-secondary" onclick="window.history.back()">Go Back</button>
            </div>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
    <script>
       function printTable() {
    var table = document.getElementById("print-table");
    window.print();
}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
