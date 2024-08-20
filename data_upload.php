<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Import</title>
    <link rel="stylesheet" href="style/styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid #ddd;
            padding: 5px;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-left: 10px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            display: inline;
            margin-left: 1rem;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #f4a261;
        }

        .container {
            flex: 1;
            margin-top: 100px; /* Adjust this value to prevent overlap */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 4rem;
                left: 0;
                width: 100%;
                background: #333;
                text-align: center;
                padding: 1rem 0;
                flex-direction: column;
                z-index: 1;
            }

            .nav-links.show-links {
                display: flex;
            }

            .nav-links li {
                display: block;
                margin: 1rem 0;
            }

            .nav-links a {
                font-size: 1.2rem;
            }

            .nav-toggle {
                display: block;
                font-size: 1.5rem;
                color: #fff;
                cursor: pointer;
                margin-left: auto;
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
        <h1>Upload Your Excel File</h1>
    <div class="row">
        <div class="col-md-12 mt-4">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<h4>".$_SESSION['message']."</h4>";
                unset($_SESSION['message']);
            }
            ?>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="excel_db.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Select Semester:</label>
                            <select name="semester" class="form-control" required>
                                <option value="">Select Semester</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="3rd">3rd Semester</option>
                                <option value="4th">4th Semester</option>
                                <option value="5th">5th Semester</option>
                                <option value="6th">6th Semester</option>
                                <option value="7th">7th Semester</option>
                                <option value="8th">8th Semester</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" name="import_file" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <button type="submit" name="save_excel_data" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('show-links');
    });
</script>
</body>
</html>
