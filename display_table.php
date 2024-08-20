<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Table</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container styling */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cccccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: flex-start;
        }

        .checkbox-group input {
            margin-right: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background-color: #007bff;
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .additional-links {
            margin-top: 20px;
        }

        .additional-links a {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .additional-links a:hover {
            color: #0056b3;
        }

        /* Footer styling */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 15px;
            }

            h2 {
                font-size: 22px;
            }

            .label-file, button {
                font-size: 14px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
                margin: 10px;
            }

            h2 {
                font-size: 18px;
            }

            .label-file, button {
                font-size: 12px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selected Table</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the selected table name
            $selectedTable = $_POST['table_name'];

            if (!empty($selectedTable)) {
                echo "<p>You selected the table: <strong>" . htmlspecialchars($selectedTable) . "</strong></p>";
            } else {
                echo "<p>No table was selected.</p>";
            }
        } else {
            echo "<p>Invalid request method.</p>";
        }
        ?>
        <div class="additional-links">
            <a href="home.php">Go back</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Yitise2021.com. All rights reserved.
    </footer>
</body>
</html>
