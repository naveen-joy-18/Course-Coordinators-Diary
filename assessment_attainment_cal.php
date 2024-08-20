<?php
session_start();
include('connection/dbconfig.php');

// Define semesters and assessments
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

// Example condition to choose which set of assessments to use
$useFirstSet = true; // You can change this condition based on your requirements

if ($useFirstSet) {
    $assessments = [
        'Assignment 1' => ['Assignment1_CO1'],
        'Assignment 2' => ['Assignment2_CO2'],
        'Unit Test 1' => ['unittest1_CO3'],
        'Unit Test 2' => ['unittest2_CO4'],
        'Quiz 1' => ['Quiz1_CO5'],
        'Quiz 2' => ['Quiz2_CO5'],
        'Miniproject' => ['Miniproject_CO'],
        'Seminar' => ['Seminar_CO'],
        'Other Assessment Tool' => ['OtherAssessmentTool_CO']
    ];
} else {
    $assessments = [
        'Assignment 1' => 'Assignment_CO1',
        'Assignment 2' => 'Assignment2_CO2',
        'Unit Test 1' => 'unittest1_CO5',
        'Unit Test 2' => 'unittest2_CO6',
        'Quiz 1' => 'Quiz1_CO3',
        'Quiz 2' => 'Quiz2_CO4',
        'Miniproject' => 'Miniproject_CO',
        'Seminar' => 'Seminar_CO',
        'Other Assessment Tool' => 'OtherAssessmentTool_CO'
    ];
}

// Initialize variables
$students = [];
$selectedSemester = '';
$selectedSubject = '';
$selectedAssessments = [];

// Fetch subjects based on the selected semester
if (isset($_POST['semester'])) {
    $selectedSemester = filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("SELECT sub_code, sub_name FROM subjects WHERE semester = ?");
    $stmt->bind_param("i", $selectedSemester);
    $stmt->execute();
    $subjectsResult = $stmt->get_result();
    $subjects = $subjectsResult->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester'], $_POST['subject'], $_POST['assessments'])) {
    $selectedSemester = filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT);
    $selectedSubject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $selectedAssessments = $_POST['assessments'];

    // Ensure selected assessments are within the defined list
    $selectedAssessments = array_intersect($selectedAssessments, array_keys($assessments));

    // Fetch subject details
    $stmt = $conn->prepare("SELECT sub_name FROM subjects WHERE sub_code = ? AND semester = ?");
    $stmt->bind_param("si", $selectedSubject, $selectedSemester);
    $stmt->execute();
    $subjectResult = $stmt->get_result();
    $subjectDetails = $subjectResult->fetch_assoc();
    $stmt->close();

    if ($subjectDetails) {
        $sub_name = $subjectDetails['sub_name'];
        $sub_code = $selectedSubject;
        $semester = $selectedSemester;
        
        $tableName = "sem{$semester}_{$sub_name}_{$sub_code}_assessment_marks";
        $tableName = str_replace(' ', '_', strtolower($tableName)); // Normalize the table name

        // Prepare the column names for the query
        // ...



$selectedAssessmentsDbNames = array_intersect_key($assessments, array_flip($selectedAssessments));

$columns = [];
foreach ($selectedAssessmentsDbNames as $coValues) {
    $columns = array_merge($columns, $coValues);
}

$selectedAssessmentsStr = implode(", ", $columns);

$query = "SELECT usn, name, $selectedAssessmentsStr FROM $tableName";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "DEBUG: Query preparation failed.";
}
    }
}

// Function to calculate total, percentage, and level
function calculateResults($student, $selectedAssessmentsDbNames) {
    $total = 0;
    $maxMarks = count($selectedAssessmentsDbNames) * 10; // Assuming each assessment is out of 10 marks

    foreach ($selectedAssessmentsDbNames as $assessmentName => $coValues) {
        foreach ($coValues as $coValue) {
            $total += $student[$coValue];
        }
    }

    $percentage = ($total / $maxMarks) * 100;

    // Determine level based on percentage
    if ($percentage >= 90) {
        $level = '5';
    } elseif ($percentage >= 80) {
        $level = '4';
    } elseif ($percentage >= 70) {
        $level = '3';
    } elseif ($percentage >= 60) {
        $level = '2';
    } else {
        $level = '1';
    }

    return [$total, $percentage, $level];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attainment Calculation</title>
    <link rel="stylesheet" href="style/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .my-4 {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .alert {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        /* Responsive Table Styles */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }

        /* Footer Styles */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
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
    <h2 class="my-4">Attainment Calculation</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="semester" class="form-label">Semester:</label>
            <select id="semester" name="semester" class="form-select" required onchange="this.form.submit()">
                <option value="">Select Semester</option>
                <?php foreach ($semesters as $key => $value): ?>
                    <option value="<?= $key ?>" <?= (isset($selectedSemester) && $selectedSemester == $key) ? 'selected' : '' ?>>Semester <?= $key ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (isset($subjects)): ?>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject:</label>
                <select id="subject" name="subject" class="form-select" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['sub_code'] ?>" <?= ($selectedSubject == $subject['sub_code']) ? 'selected' : '' ?>>
                            <?= $subject['sub_code'] . ' - ' . $subject['sub_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="assessments" class="form-label">Assessments:</label>
            <?php foreach ($assessments as $displayName => $dbName): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="<?= strtolower(str_replace(' ', '_', $displayName)) ?>" name="assessments[]" value="<?= $displayName ?>">
                    <label class="form-check-label" for="<?= strtolower(str_replace(' ', '_', $displayName)) ?>">
                        <?= $displayName ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester'], $_POST['subject'], $_POST['assessments'])): ?>
        <?php if (empty($students)): ?>
            <div class="alert alert-info mt-4">No data found for the selected assessments.</div>
        <?php else: ?>
            <h3 class="my-4">Student Data</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>USN</th>
                        <th>Name</th>
                        <?php foreach ($selectedAssessments as $displayName): ?>
                            <th><?= $displayName ?></th>
                        <?php endforeach; ?>
                        <th>Total</th>
                        <th>Percentage</th>
                        <th>Level</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): ?>
                        <?php list($total, $percentage, $level) = calculateResults($student, $selectedAssessmentsDbNames); ?>
                        <tr>
                            <td><?= $student['usn'] ?></td>
                            <td><?= $student['name'] ?></td>
                            <?php foreach ($selectedAssessmentsDbNames as $assessmentName => $coValues): ?>
    <?php foreach ($coValues as $dbName): ?>
        <td><?= $student[$dbName] ?></td>
    <?php endforeach; ?>
<?php endforeach; ?>
                            <td><?= $total ?></td>
                            <td><?= number_format($percentage, 2) ?>%</td>
                            <td><?= $level ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>