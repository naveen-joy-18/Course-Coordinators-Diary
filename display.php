<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: auto;
            overflow-x: auto;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-top: -30px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
        }
        .d1{
            display: flex;
            position: absolute;
            
        }
        @media print {
            /* Styles for print */
            .d1 {
                visibility: hidden;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Student Details</h2>
        </div>
        <div class="row">
            <div class="col-md-12 mt-4">
                
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "students";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['semester']) && isset($_POST['fields'])) {
                    $semester = $_POST['semester'];
                    $fields = $_POST['fields'];

                    // Validate the semester input
                    $validSemesters = ["sem_3", "sem_5", "sem_7"];
                    if (in_array($semester, $validSemesters)) {
                        $fieldList = implode(", ", $fields);
                        $sql = "SELECT $fieldList FROM $semester";
                        $result = $conn->query($sql);

                        if ($result === false) {
                            // If the query failed, display the error
                            echo "Error: " . $conn->error;
                        } else if ($result->num_rows > 0) {
                            echo "<table class='table table-striped'>";
                            echo "<tr>";
                            foreach ($fields as $field) {
                                echo "<th>" . ucfirst($field) . "</th>";
                            }
                            echo "</tr>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($fields as $field) {
                                    echo "<td>" . $row[$field] . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "0 results";
                        }
                    } else {
                        echo "Invalid semester selection.";
                    }
                } else {
                    echo "No semester selected or no fields selected.";
                }

                $conn->close();
                ?>
                <div class="d1"><button id="printButton"> Print</button></div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>
