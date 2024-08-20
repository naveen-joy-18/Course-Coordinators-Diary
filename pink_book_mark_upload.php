<?php
include('connection/dbconfig.php');

// Retrieve and sanitize POST data
$semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
$subject = $_POST['subject'];
$assessments = $_POST['assessment'];
$scaledown = $_POST['scaledown'];
$max_marks = $_POST['max_marks'];
$cos = $_POST['co'];

// Calculate the total maximum marks
$total_max_marks = array_sum($max_marks);

// Map the semester to the table name
$semesters = [
    1 => 'first_semester',
    2 => 'second_semester',
    3 => 'third_semester',
    4 => 'fourth_semester',
    5 => 'fifth_semester',
    6 => 'sixth_semester',
    7 => 'seventh_semester',
    8 => 'eighth_semester'
];

// Ensure the semester value is valid and fetch the corresponding table name
if (!isset($semesters[$semester])) {
    die("Invalid semester selected.");
}
$tableName = $semesters[$semester];

// Check if the table exists
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$checkTableResult = $conn->query($checkTableQuery);

if ($checkTableResult->num_rows == 0) {
    die("Table for semester $semester does not exist.");
}

// Fetch student data from the table
$sql = "SELECT usn, name FROM $tableName";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Assessment</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .container{
            width:90%;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .marks-input {
            width: 60px;
        }
        .c1{
            margin-top: 20px;
            width: 100%;
            flex: 1;
            border:none;
            border-radius:10px;
            padding-top:10px;
            padding-bottom:10px;
            background-color:#007bff;
            color:white;
            font-size:20px;
        }
        .c1:hover{
            background-color: #0056b3;
            color:white;
        }
.success-message {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-top: 20px;
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
</header> <div class="container"><br><br>
<h2>Student Assessment Marks</h2>
<form method="POST" action="pink_book_mark_store.php">
        <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
        <input type="hidden" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
        <input type="hidden" name="scaledown" value="<?php echo htmlspecialchars($scaledown); ?>">
        <input type="hidden" name="total_max_marks" value="<?php echo htmlspecialchars($total_max_marks); ?>">
        <input type="hidden" name="max_marks_json" value='<?php echo json_encode($max_marks); ?>'>
        <input type="hidden" name="assessments_json" value='<?php echo json_encode($assessments); ?>'>
        <input type="hidden" name="cos_json" value='<?php echo json_encode($cos); ?>'>
        <table>
            <thead>
                <tr>
                    <th>USN</th>
                    <th>Name</th>
                    <?php
                    foreach ($assessments as $assessment) {
                        $co = htmlspecialchars($cos[$assessment]);
                        echo "<th>$assessment ($co) <input type='checkbox' class='assessment-header' data-assessment='$assessment'></th>";
                    }
                    ?>
                    <th>Total Marks</th>
                    <th>Scaled Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['usn']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";

                        foreach ($assessments as $assessment) {
                            echo "<td ><input type='number' name='marks[" . htmlspecialchars($row['usn']) . "][" . htmlspecialchars($assessment) . "]' placeholder='Enter marks' class='marks-input'></td>";
                        }

                        echo "<td class='total-marks'></td>";
                        echo "<td class='scaled-marks'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($assessments) + 4) . "'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <input type="submit" value="Save Marks" class="c1">
        <div id="success-message" style="display: none;"></div>
    </form>
</div> 
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>

    <script>
        document.querySelectorAll('.assessment-header').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateMarks();
            });
        });

        document.querySelectorAll('.marks-input').forEach(function(input) {
            input.addEventListener('input', function() {
                updateMarks();
            });
        });

        function updateMarks() {
            let rows = document.querySelectorAll('tbody tr');
            let scaledown = <?php echo json_encode($scaledown); ?>;
            let totalMaxMarks = <?php echo json_encode($max_marks); ?>;
            let totalAssessmentMarks = 0;

            rows.forEach(function(row) {
                let totalMarks = 0;
                let totalCheckedMaxMarks = 0;

                row.querySelectorAll('.marks-input').forEach(function(markInput) {
                    let assessment = markInput.name.split('[')[2].split(']')[0];
                    let headerCheckbox = document.querySelector('.assessment-header[data-assessment="' + assessment + '"]');
                    if (headerCheckbox.checked) {
                        totalMarks += parseFloat(markInput.value) || 0;
                        totalCheckedMaxMarks += parseFloat(totalMaxMarks[assessment]) || 0;
                    }
                });

                row.querySelector('.total-marks').textContent = totalMarks;
                let scaledMarks = (totalMarks * scaledown) / totalCheckedMaxMarks;
                row.querySelector('.scaled-marks').textContent = isNaN(scaledMarks) ? '0.00' : scaledMarks.toFixed(2);
                totalAssessmentMarks += totalMarks;
            });
        }

        const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('pink_book_mark_store.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            document.getElementById('success-message').innerHTML = message;
            document.getElementById('success-message').style.display = 'block';
        })
        .catch(error => console.error(error));
    });

    </script>
</body>
</html>
