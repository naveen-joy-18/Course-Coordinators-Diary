<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'students_db';
$username = 'root';
$password = ''; // Update with your MySQL password if necessary

// Create a MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$students = [];
$dataFound = false;

// Turn off default error reporting to handle it manually
mysqli_report(MYSQLI_REPORT_OFF);

// Fetch available semesters
$semesters = [1, 2, 3, 4, 5, 6, 7, 8];

// Handle form submission to get semester and subject
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : null;
$sub_code = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $semester && $sub_code) {
    // Fetch the subject name
    $subjectQuery = $conn->prepare("SELECT sub_name FROM subjects WHERE sub_code = ? AND semester = ?");
    $subjectQuery->bind_param("si", $sub_code, $semester);
    $subjectQuery->execute();
    $subjectResult = $subjectQuery->get_result();
    if (!$subjectResult) {
        die('Error: ' . $conn->error);
    }
    $subjectRow = $subjectResult->fetch_assoc();
    $sub_name = $subjectRow['sub_name'] ?? 'Unknown';

    // Aggregate table name
    $aggregateTable = "sem{$semester}_{$sub_name}_{$sub_code}_IA_aggregate";
    $aggregateTable = str_replace(' ', '_', strtolower($aggregateTable)); // Normalize the table name

    try {
        // Fetch student aggregate marks
        $studentsQuery = $conn->prepare("SELECT usn, name, ia1_marks, ia2_marks, ia3_marks, total_ia_marks, avg_marks FROM $aggregateTable");
        $studentsQuery->execute();
        $studentsResult = $studentsQuery->get_result();
        if (!$studentsResult) {
            throw new mysqli_sql_exception('Error querying students: ' . $conn->error);
        }
        if ($studentsResult->num_rows > 0) {
            $dataFound = true;
            while ($row = $studentsResult->fetch_assoc()) {
                // Calculate IA Marks from Other Assessments (max 20)
                $iaMarksFromOtherAssessments = min(20, $row['avg_marks']);
                
                // Calculate Total IA Marks (max 40)
                $totalIaMarks = min(40, $iaMarksFromOtherAssessments + $row['total_ia_marks']);

                // Calculate the average of the IA marks
                $iaAvgMarks = ($row['ia1_marks'] + $row['ia2_marks'] + $row['ia3_marks']) / 3;

                $students[$row['usn']] = array_merge($row, [
                    'ia_marks_from_other_assessments' => $iaMarksFromOtherAssessments,
                    'total_ia_marks' => $totalIaMarks,
                    'ia_avg_marks' => $iaAvgMarks
                ]);
            }
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Database Error: ' . $e->getMessage();
    }
}

// Handle AJAX request to fetch subjects
if (isset($_GET['fetch_subjects'])) {
    if (isset($_GET['semester'])) {
        $semester = intval($_GET['semester']);
        
        $subjectsQuery = $conn->prepare("SELECT sub_code, sub_name FROM subjects WHERE semester = ?");
        $subjectsQuery->bind_param("i", $semester);
        $subjectsQuery->execute();
        $subjectsResult = $subjectsQuery->get_result();

        $subjects = [];
        while ($row = $subjectsResult->fetch_assoc()) {
            $subjects[] = ['sub_code' => $row['sub_code'], 'sub_name' => $row['sub_name']];
        }

        echo json_encode($subjects);
    } else {
        echo json_encode([]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIE Details</title>
    <style>
        body {
            background-color: white;
            color: black;
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center; /* Center text */
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
            width: 80%;
            border: 1px solid #007bff;
        }

        th, td {
            padding: 10px;
            border: 1px solid #007bff;
            text-align: center;
        }

        input[type=number] {
            width: 60px;
        }

        .grades-table {
            margin: 20px auto;
            width: 60%;
            border: 1px solid #007bff;
            border-collapse: collapse;
        }

        .grades-table th, .grades-table td {
            padding: 8px;
            border: 1px solid #007bff;
            text-align: center;
        }

        .save-button, .home-button {
            background-color: #007bff;
            color: white; /* White text */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 20px;
        }

        .home-button {
            margin-top: 10px;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-direction: column;
        }

        form select, form button {
            margin-bottom: 10px;
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
    <h1>CIE Details</h1>
    <form method="GET">
        <div>
            <label for="semester">Semester:</label>
            <select id="semester" name="semester" onchange="fetchSubjects()">
                <option value="">Select Semester</option>
                <?php foreach ($semesters as $sem): ?>
                    <option value="<?= $sem ?>" <?= $semester == $sem ? 'selected' : '' ?>><?= $sem ?></option>
                <?php endforeach; ?>
            </select>

            <label for="subject">Subject:</label>
            <select id="subject" name="subject">
                <option value="">Select Subject</option>
            </select>

            <button type="submit">Submit</button>
        </div>
    </form>

    <table id="dataTable">
        <thead>
            <tr>
                <th>USN</th>
                <th>Student Name</th>
                <th>1 IA Marks (max 30)</th>
                <th>2 IA Marks (max 30)</th>
                <th>3 IA Marks (max 30)</th>
                <th>Average IA Marks</th>
                <th>IA Marks from Other Assessments (max 20)</th>
                <th>Total IA Marks (max 40)</th>
                <th>SEE Marks (60)</th>
                <th>Total Marks (100)</th>
                <th>SEE (%)</th>
                <th>Grades</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataFound): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['usn']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['ia1_marks']) ?></td>
                        <td><?= htmlspecialchars($student['ia2_marks']) ?></td>
                        <td><?= htmlspecialchars($student['ia3_marks']) ?></td>
                        <td><?= number_format($student['ia_avg_marks'], 2) ?></td>
                        <td><?= number_format($student['ia_marks_from_other_assessments'], 2) ?></td>
                        <td><?= number_format($student['total_ia_marks'], 2) ?></td>
                        <td><input type="number" name="seeMarks" min="0" max="60" oninput="calculateTotalMarks(this)"></td>
                        <td class="totalMarks"></td> <!-- Total Marks -->
                        <td class="seePercentage"></td> <!-- SEE Percentage -->
                        <td class="grades"></td> <!-- Grades -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="12">No data found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div>
        <button class="save-button" onclick="saveData()">Save</button>
        <a href="home.php" class="home-button">Home</a>
    </div>

    <h2 class="center">Grades Summary</h2>
    <table class="grades-table">
        <thead>
            <tr>
                <th>Grade</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody id="gradesSummary">
            <tr><td>S</td><td id="gradeS">0</td></tr>
            <tr><td>A</td><td id="gradeA">0</td></tr>
            <tr><td>B</td><td id="gradeB">0</td></tr>
            <tr><td>C</td><td id="gradeC">0</td></tr>
            <tr><td>D</td><td id="gradeD">0</td></tr>
            <tr><td>E</td><td id="gradeE">0</td></tr>
            <tr><td>F</td><td id="gradeF">0</td></tr>
        </tbody>
    </table>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
    <script>
        function fetchSubjects() {
            const semester = document.getElementById('semester').value;
            if (semester) {
                fetch(`?fetch_subjects=true&semester=${semester}`)
                    .then(response => response.json())
                    .then(subjects => {
                        const subjectSelect = document.getElementById('subject');
                        subjectSelect.innerHTML = '<option value="">Select Subject</option>'; // Clear existing options
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.sub_code;
                            option.textContent = subject.sub_name;
                            subjectSelect.appendChild(option);
                        });
                    });
            }
        }

        function calculateTotalMarks(seeInput) {
            const row = seeInput.closest('tr');
            const seeMarks = parseFloat(seeInput.value) || 0;
            const totalIaMarks = parseFloat(row.querySelector('.totalIaMarks').textContent) || 0;
            const totalMarks = Math.min(100, seeMarks + totalIaMarks);
            row.querySelector('.totalMarks').textContent = totalMarks.toFixed(2);
            const seePercentage = ((seeMarks / 60) * 100).toFixed(2);
            row.querySelector('.seePercentage').textContent = `${seePercentage}%`;
            const grade = getGrade(totalMarks);
            row.querySelector('.grades').textContent = grade;
            updateGradeSummary(grade);
        }

        function getGrade(totalMarks) {
            if (totalMarks >= 90) return 'S';
            if (totalMarks >= 80) return 'A';
            if (totalMarks >= 70) return 'B';
            if (totalMarks >= 60) return 'C';
            if (totalMarks >= 50) return 'D';
            if (totalMarks >= 40) return 'E';
            return 'F';
        }

        function updateGradeSummary(grade) {
            const gradeSummary = document.getElementById('gradesSummary');
            const gradeCounts = {
                S: 'gradeS',
                A: 'gradeA',
                B: 'gradeB',
                C: 'gradeC',
                D: 'gradeD',
                E: 'gradeE',
                F: 'gradeF'
            };
            document.getElementById(gradeCounts[grade]).textContent = parseInt(document.getElementById(gradeCounts[grade]).textContent) + 1;
        }

        function saveData() {
            alert('Data saved successfully!');
        }
    </script>
</body>
</html>
