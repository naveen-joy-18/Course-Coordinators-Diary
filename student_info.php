<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Semester and Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }
        .container {
            max-width:50%;
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
        <h2>Student Details</h2>
        <form action="student_db_display.php" method="post">
            <div class="form-group">
                <h4>Select Semester</h4>
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="sem_1">Semester 1</option>
                    <option value="sem_2">Semester 2</option>
                    <option value="sem_3">Semester 3</option>
                    <option value="sem_4">Semester 4</option>
                    <option value="sem_5">Semester 5</option>
                    <option value="sem_6">Semester 6</option>
                    <option value="sem_7">Semester 7</option>
                    <option value="sem_8">Semester 8</option>
                </select>
            </div>
            <div class="form-group">
                <h4>Select Details to Display</h4>
                <label><input type="checkbox" name="fields[]" value="usn"> USN</label><br>
                <label><input type="checkbox" name="fields[]" value="name"> Name</label><br>
                <label><input type="checkbox" name="fields[]" value="branch"> Branch</label><br>
                <label><input type="checkbox" name="fields[]" value="batch"> Batch</label><br>
                <label><input type="checkbox" name="fields[]" value="semester"> Semester</label><br>
                <label><input type="checkbox" name="fields[]" value="gender"> Gender</label><br>
                <label><input type="checkbox" name="fields[]" value="dateofbirth"> D.O.B</label><br>
                <label><input type="checkbox" name="fields[]" value="gmail"> email</label><br>
                <label><input type="checkbox" name="fields[]" value="phonenumber"> Phone No</label><br>
                <label><input type="checkbox" name="fields[]" value="address"> Address</label>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>
    <footer>
        <img src="image/logo.png" alt="Logo" class="footer-logo">
        <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>