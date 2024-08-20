<?php
session_start();
include('connection/dbconfig.php');

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

$ias = ['IA-1', 'IA-2', 'IA-3'];
$cos = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5'];

// Initialize variables
$students = [];
$marksMapping = [];
$selectedSubject = '';
$selectedIA = '';
$selectedCO = '';

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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester'], $_POST['subject'], $_POST['ia'], $_POST['co'])) {
    $selectedSemester = filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT);
    $selectedSubject = $_POST['subject'];
    $selectedIA = $_POST['ia'];
    $selectedCO = $_POST['co'];

    if (isset($semesters[$selectedSemester])) {
        $tableName = $semesters[$selectedSemester];

        // Fetch student data from the relevant semester table
        $studentStmt = $conn->prepare("SELECT id, usn, name, branch, batch, semester, gender, dateofbirth, gmail, phonenumber, address FROM $tableName");
        $studentStmt->execute();
        $students = $studentStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $studentStmt->close();

        // Fetch marks data from the marks upload table if it exists
        try {
            // Assuming the marks table name format is sem{semester}{sub_name}{sub_code}_ia{which_ia}_co_marks
            $subNameStmt = $conn->prepare("SELECT sub_name FROM subjects WHERE sub_code = ?");
            $subNameStmt->bind_param("s", $selectedSubject);
            $subNameStmt->execute();
            $subNameResult = $subNameStmt->get_result();
            $subNameRow = $subNameResult->fetch_assoc();
            $subNameStmt->close();
        
            if ($subNameRow) {
                $sub_name = strtolower(str_replace(' ', '_', $subNameRow['sub_name']));
                $which_ia = strtolower(str_replace('-', '', $selectedIA));
                $marksTable = "sem{$selectedSemester}_{$sub_name}_{$selectedSubject}_{$which_ia}_co_marks";
        
                // Check if the marks table exists
                $checkTableSql = "SELECT 1 FROM information_schema.tables WHERE table_schema = 'students_db' AND table_name = ?";
                $checkTableStmt = $conn->prepare($checkTableSql);
                $checkTableStmt->bind_param("s", $marksTable);
                $checkTableStmt->execute();
                $tableExists = $checkTableStmt->fetch();
                $checkTableStmt->close();
        
                if ($tableExists) {
                    // Try to fetch marks data
                    $marksStmt = $conn->prepare("SELECT usn, co_marks FROM $marksTable WHERE co_name = ?");
                    $marksStmt->bind_param("s", $selectedCO);
                    $marksStmt->execute();
                    $marks = $marksStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $marksStmt->close();
        
                    // Create a mapping of USN to CO marks
                    foreach ($marks as $mark) {
                        $marksMapping[$mark['usn']] = $mark['co_marks'];
                    }
                } else {
                    $marksMapping = null; // No table found
                }
            } else {
                $marksMapping = null;
            }
        } catch (mysqli_sql_exception $e) {
            $marksMapping = null;
            echo "Error: " . $e->getMessage(); // Display SQL exception error message
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid semester selected.</div>";
    }
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
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

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
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
  }
  th, td {
    border: 1px solid #ddd !important;
    padding: 8px;
    text-align: center;
    box-shadow: 0 0 0 1px #ddd;
  }
  th {
    background-color: #f2f2f2;
  }
  button {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  .center-button {
    text-align: center;
    margin-bottom: 20px;
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
         @media print {
            #calculate-button {
        display: none;
           }
            header, footer {
                    display: none;
            }
            button {
                display: none;
            }
            @page {
                size: A4;
                margin: 0;
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
    <h1 class="my-4">Attainment Calculation</h1>
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
            <label for="ia" class="form-label">IA:</label>
            <select id="ia" name="ia" class="form-select" required>
                <option value="">Select IA</option>
                <?php foreach ($ias as $ia): ?>
                    <option value="<?= $ia ?>" <?= ($selectedIA == $ia) ? 'selected' : '' ?>><?= $ia ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="co" class="form-label">CO:</label>
            <select id="co" name="co" class="form-select" required>
                <option value="">Select CO</option>
                <?php foreach ($cos as $co): ?>
                    <option value="<?= $co ?>" <?= ($selectedCO == $co) ? 'selected' : '' ?>><?= $co ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="text-center">
                <button type="submit" class="btn btn-primary" id="calculate-button">Calculate</button>
            </div>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester'], $_POST['subject'], $_POST['ia'], $_POST['co'])): ?>
    <?php if (empty($students)): ?>
        <div class="alert alert-warning">No student data found for the selected semester.</div>
    <?php elseif ($marksMapping === null): ?>
        <div class="alert alert-warning">No marks data found for the selected criteria.</div>
    <?php else: ?>
        <?php if (empty($marks)): ?>
            <div class="alert alert-warning">No CO marks found for the selected criteria.</div>
        <?php else: ?>
            <table id="print-table">
                <thead>
                <tr>
                    <th>USN</th>
                    <th>Name</th>
                    <th>Marks (<?= $selectedCO ?>)</th>
                    <th>Percentage(%)</th>
                    <th>Level(out of 5)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['usn'] ?></td>
                        <td><?= $student['name'] ?></td>
                        <td><?= isset($marksMapping[$student['usn']]) ? $marksMapping[$student['usn']] : 'N/A' ?></td>
                        <?php
                        $totalMarks = isset($marksMapping[$student['usn']]) ? $marksMapping[$student['usn']] : 0;
                        $maxMarks = $totalMarks > 15? 30 : 15;
                        $percentage = ($totalMarks / $maxMarks) * 100;
                        $level = $percentage >= 80 ? 5 : ($percentage >= 70 ? 4 : ($percentage >= 60 ? 3 : ($percentage >= 50 ? 2 : ($percentage >= 40 ? 1 : 0))));
                        ?>
                        <td><?= number_format($percentage, 2) ?>%</td>
                        <td><?= $level ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="center-button">
                <button onclick="printTable()">Print Table</button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
</div>
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
              function printTable() {
    var table = document.getElementById("print-table");
    window.print();
}
            </script>
</body>
</html>
