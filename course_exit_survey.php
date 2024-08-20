<!--index.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>COURSE EXIT SURVEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        
        form {
            width: 300px;
            margin: 0 auto;
        }
        input, select {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
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
<h1>COURSE EXIT SURVEY</h1>
    <form action="process.php" method="post" enctype="multipart/form-data">
        <label for="semester">Select Semester:</label>
<select id="semester" name="semester">
    <option value="" selected>Select Semester</option>
    <option value="first_semester">First Semester</option>
    <option value="second_semester">Second Semester</option>
    <option value="third_semester">Third Semester</option>
    <option value="fourth_semester">Fourth Semester</option>
    <option value="fifth_semester">Fifth Semester</option>
    <option value="sixth_semester">Sixth Semester</option>
    <option value="seventh_semester">Seventh Semester</option>
    <option value="eighth_semester">Eighth Semester</option>
</select>
        <label for="course">Enter Branch:</label>
        <input type="text" id="course" name="course" required>
        <label for="year">Enter Year:</label>
        <input type="text" id="year" name="year" required>
        <label for="subject">Enter Subject:</label>
        <input type="text" id="subject" name="subject" required>
        <label for="file">Upload Excel File:</label>
        <input type="file" id="file" name="file" accept=".xlsx" required>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" style="background-color: blueviolet;">
        </div>
    </form>
</div>
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>
