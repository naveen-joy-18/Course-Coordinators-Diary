<?php
session_start();
include('connection/dbconfig.php');

// Fetch available semesters
$semesters = [1, 2, 3, 4, 5, 6, 7, 8];

// Handle form submission to get semester and subject
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : null;
$sub_code = isset($_GET['subject']) ? $_GET['subject'] : null;

$subjects = [];
if ($semester) {
    $subjectsQuery = "SELECT sub_code, sub_name FROM subjects WHERE semester='$semester'";
    $subjectsResult = mysqli_query($conn, $subjectsQuery);
    while ($row = mysqli_fetch_assoc($subjectsResult)) {
        $subjects[] = ['sub_code' => $row['sub_code'], 'sub_name' => $row['sub_name']];
    }
}

$students = [];
$assessmentMarks = [];
$selected_assessments = [];
$assessmentTableExists = true;
$dataFound = false;

mysqli_report(MYSQLI_REPORT_OFF); // Turn off default error reporting to handle it manually

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $semester && $sub_code) {
    // Fetch the subject name
    $subjectQuery = "SELECT sub_name FROM subjects WHERE sub_code='$sub_code' AND semester='$semester'";
    $subjectResult = mysqli_query($conn, $subjectQuery);
    if (!$subjectResult) {
        die('Error: ' . mysqli_error($conn));
    }
    $subjectRow = mysqli_fetch_assoc($subjectResult);
    $sub_name = $subjectRow['sub_name'] ?? 'Unknown';

    // Aggregate table name
    $aggregateTable = "sem{$semester}_{$sub_name}_{$sub_code}_IA_aggregate";
    $aggregateTable = str_replace(' ', '_', strtolower($aggregateTable)); // Normalize the table name
    $table_name = "sem{$semester}_{$sub_name}_{$sub_code}_assessment_marks";
    $table_name = str_replace(' ', '_', strtolower($table_name)); // Normalize the table name

    try {
        // Fetch student aggregate marks
        $studentsQuery = "SELECT usn, name, ia1_marks, ia2_marks, ia3_marks, total_ia_marks, avg_marks FROM $aggregateTable";
        $studentsResult = mysqli_query($conn, $studentsQuery);
        if (!$studentsResult) {
            throw new mysqli_sql_exception('Error querying students: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($studentsResult) > 0) {
            $dataFound = true;
            while ($row = mysqli_fetch_assoc($studentsResult)) {
                $students[] = $row;
            }
        }

        // Check if the assessment table exists and fetch its columns
        $assessmentColumnsQuery = "SHOW COLUMNS FROM $table_name";
        $assessmentColumnsResult = mysqli_query($conn, $assessmentColumnsQuery);

        if (!$assessmentColumnsResult) {
            $assessmentTableExists = false;
        } else {
            while ($column = mysqli_fetch_assoc($assessmentColumnsResult)) {
                // Skip non-assessment columns (like id, usn, name, total_mark, scaled_mark)
                if (!in_array($column['Field'], ['id', 'usn', 'name', 'total_mark', 'scaled_mark'])) {
                    $selected_assessments[] = $column['Field'];
                }
            }

            if (!empty($selected_assessments)) {
                $selected_assessments_list = implode(', ', $selected_assessments);
                $assessmentMarksQuery = "SELECT usn, name, $selected_assessments_list, total_mark, scaled_mark FROM $table_name";
                $assessmentMarksResult = mysqli_query($conn, $assessmentMarksQuery);
                
                if (!$assessmentMarksResult) {
                    throw new mysqli_sql_exception('Error querying assessment marks: ' . mysqli_error($conn));
                }
                if (mysqli_num_rows($assessmentMarksResult) > 0) {
                    $dataFound = true;
                    while ($row = mysqli_fetch_assoc($assessmentMarksResult)) {
                        $usn = $row['usn'];
                        $assessmentMarks[$usn] = $row;
                    }
                }
            }
        }
    } catch (mysqli_sql_exception $e) {
        $assessmentTableExists = false;
        echo 'Database Error: ' . $e->getMessage();
    }
}

if (isset($_GET['fetch_subjects'])) {
    if (isset($_GET['semester'])) {
        $semester = intval($_GET['semester']);
        
        $subjectsQuery = "SELECT sub_code, sub_name FROM subjects WHERE semester='$semester'";
        $subjectsResult = mysqli_query($conn, $subjectsQuery);

        $subjects = [];
        while ($row = mysqli_fetch_assoc($subjectsResult)) {
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
    <title>Student Marks Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const semesterSelect = document.getElementById('semester');
            const subjectSelect = document.getElementById('subject');

            semesterSelect.addEventListener('change', function() {
                const semester = this.value;
                fetchSubjects(semester);
            });

            function fetchSubjects(semester) {
                fetch(`?fetch_subjects=1&semester=${semester}`)
                    .then(response => response.json())
                    .then(subjects => {
                        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.sub_code;
                            option.textContent = subject.sub_name;
                            subjectSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            }
        });
    </script>
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
        <h1>Student Marks Details</h1>
        <form method="GET" action="" id="form">
            <div class="info">
                <div>
                    <label for="semester"><strong>Semester:</strong></label>
                    <select id="semester" name="semester" class="form-select">
                        <option value="">Select Semester</option>
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?php echo $sem; ?>" <?php echo ($semester == $sem) ? 'selected' : ''; ?>><?php echo $sem; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="subject"><strong>Subject:</strong></label>
                    <select id="subject" name="subject" class="form-select">
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['sub_code']; ?>" <?php echo ($sub_code == $subject['sub_code']) ? 'selected' : ''; ?>><?php echo $subject['sub_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                </div>
            </div>
        </form>
        <div class="table-container">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && $semester && $sub_code): ?>
                <?php if (!$assessmentTableExists): ?>
                    <p>Assessment page not found.</p>
                <?php elseif (!$dataFound): ?>
                    <p>No data found for the selected semester and subject.</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>USN</th>
                                <th>Name</th>
                                <th>IA1 Marks</th>
                                <th>IA2 Marks</th>
                                <th>IA3 Marks</th>
                                <th>Total IA Marks</th>
                                <th>Average of IA Marks</th>
                                <?php foreach ($selected_assessments as $assessment): ?>
                                    <th><?php echo $assessment; ?></th>
                                <?php endforeach; ?>
                                <th>Total Assessment Marks</th>
                                <th>Average of Assessment Marks</th>
                                <th>Total Internal Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo $student['usn']; ?></td>
                                    <td><?php echo $student['name']; ?></td>
                                    <td><?php echo $student['ia1_marks']; ?></td>
                                    <td><?php echo $student['ia2_marks']; ?></td>
                                    <td><?php echo $student['ia3_marks']; ?></td>
                                    <td><?php echo $student['total_ia_marks']; ?></td>
                                    <td><?php echo round($student['avg_marks'], 1); ?></td>
                                    <?php foreach ($selected_assessments as $assessment): ?>
                                        <td><?php echo $assessmentMarks[$student['usn']][$assessment] ?? ''; ?></td>
                                    <?php endforeach; ?>
                                    <td><?php echo $assessmentMarks[$student['usn']]['total_mark'] ?? ''; ?></td>
                                    <td><?php echo $assessmentMarks[$student['usn']]['scaled_mark'] ?? ''; ?></td>
                                    <td><?php echo ceil($student['avg_marks'] + ($assessmentMarks[$student['usn']]['scaled_mark'] ?? 0)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <img src="image/logo.png" alt="Logo" class="footer-logo">
        <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
    </footer>
</body>
</html>
