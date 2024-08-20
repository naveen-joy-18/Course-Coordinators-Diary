<?php
session_start();
include('connection/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected semester
    $semester = $_POST['semester'];

    // Determine the table name based on the selected semester
    switch ($semester) {
        case 'sem_3':
            $table = 'third_semester';
            break;
        case 'sem_5':
            $table = 'fifth_semester';
            break;
        case 'sem_7':
            $table = 'seventh_semester';
            break;
        default:
            $_SESSION['message'] = 'Invalid semester selected.';
            header('Location: select.php'); // Redirect to the form page
            exit();
    }

    // Get the selected fields
    $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

    if (empty($fields)) {
        $_SESSION['message'] = 'Please select at least one field to display.';
        header('Location: select.php'); // Redirect to the form page
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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: auto;
            min-width: 300px; /* Minimum width */
            max-width: 90%; /* Allow flexibility based on content */
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
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

        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .container, .container * {
                visibility: visible;
            }
            .container {
                position: absolute;
                left: 0;
                top: 0;
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
    <div class="container">
        <h2>Student Data</h2>
        <table>
            <thead>
                <tr>
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
        <div class="btn-group">
            <a href="#" onclick="window.print();" class="btn btn-primary">Print</a>
            <a href="student_info.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Yitise2021.com. All rights reserved.
    </footer>
</body>
</html>
