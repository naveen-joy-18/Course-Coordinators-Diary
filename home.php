<?php 
session_start();
include('connection/dbconfig.php');
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Coordinator's Diary</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="style/styles.css">
    <style>
        .hero {
            color: #fff;
            padding: 6rem 0;
            text-align: center;
            margin-top: 60px; /* Space for the fixed header */
            flex: 1;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 2rem;
        }

        .cta-button {
            background: #333;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: calc(25% - 10px);
        }

        .cta-button:hover {
            background: white;
            color: darkblue; 
            transform: translateY(-5px);
            text-decoration: none;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-family: Arial, sans-serif;
        }

        .table th {
            background-color: #337ab7;
            color: #fff;
            font-weight: bold;
        }

        .table td {
            font-size: 14px;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
        }

        @media screen and (max-width: 768px) {
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

            .hero {
                padding: 3rem 0;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .cta-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .cta-button {
                padding: 0.75rem 1.5rem;
                width: 100%;
            }
        }

        .nav-toggle {
            display: none;
        }
        .subject-list {
  margin: 20px auto;
  width: 80%;
  border: 1px solid #ccc; /* changed border color to light gray */
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.subject-list h2 {
  margin-top: 0;
  font-weight: bold;
  color: #00698f; /* changed header text color to a darker blue */
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table thead th {
  background-color: #009688; /* changed header background color to a greenish-blue */
  color: #fff; /* kept white text color */
  border: 1px solid #009688; /* changed border color to match background */
  padding: 10px;
  text-align: center;
}

.table tbody td {
  border: 1px solid #ccc; /* changed border color to light gray */
  padding: 10px;
  text-align: center;
}

.table tbody tr:nth-child(even) {
  background-color: #f7f7f7; /* changed alternating row color to a light gray */
}

.table tbody tr:hover {
  background-color: #e5e5e5; /* changed hover color to a lighter gray */
  cursor: pointer;
}

.subject-list .table td {
  vertical-align: middle;
}

.subject-list .table th, .subject-list .table td {
  width: 25%;
}

.subject-list .table th:first-child, .subject-list .table td:first-child {
  width: 10%;
}

.subject-list .table th:last-child, .subject-list .table td:last-child {
  width: 20%;
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
            <li><a href="data_upload.php">Upload</a></li>
            <li><a href="logout.php">logout</a></li>
        </ul>
    </nav>
</header>
    <section class="hero">
        <div class="hero-content">
            <h2>Welcome to Course Coordinator's Diary</h2>
            <h2>Your one-stop solution for academic needs.</h2>
            <div class="cta-buttons">
                <a href="subject_reg.php" class="cta-button">Subject Registration</a>
                <a href="student_info.php" class="cta-button">Student Information</a>
                <a href="select_sem.php" class="cta-button">IA Marks</a>
                <a href="pink_book_marks.php" class="cta-button">Assessment Marks</a>
                <a href="IA_attainment_cal.php" class="cta-button">IA Attainment Calculation</a>
                <a href="assessment_attainment_cal.php" class="cta-button">Assessment Attainment Calculation</a>
                <a href="final_marks.php" class="cta-button">Final Marks</a>
                <!-- <a href="CIE_details.php" class="cta-button">CIE-SEE Details</a> -->
                <a href="course_exit_survey.php" class="cta-button">Course Exit Survey</a>
                <a href="csb.php" class="cta-button">Minutes Of Meeting</a>
            </div>
        </div>
    </section>
    <section class="subject-list">
        <h2 style="text-align: center; color:white;">Subjects List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center; background-color: #337ab7; color: #fff; border: 1px solid #337ab7;">Sl. No</th>
                    <th style="text-align: center; background-color: #337ab7; color: #fff; border: 1px solid #337ab7;">Semester</th>
                    <th style="text-align: center; background-color: #337ab7; color: #fff; border: 1px solid #337ab7;">Subject Name</th>
                    <th style="text-align: center; background-color: #337ab7; color: #fff; border: 1px solid #337ab7;">Subject Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT * FROM subjects";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        $sl_no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>". $sl_no. "</td>";
                            echo "<td>". (isset($row['semester']) ? $row['semester'] : ''). "</td>";
                            echo "<td>". (isset($row['sub_name']) ? $row['sub_name'] : ''). "</td>";
                            echo "<td>". (isset($row['sub_code']) ? $row['sub_code'] : ''). "</td>";
                            echo "</tr>";
                            $sl_no++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>No subjects found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </section>

    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
    <script>
        const navToggle = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show-links');
        });
    </script>
</body>
</html>
