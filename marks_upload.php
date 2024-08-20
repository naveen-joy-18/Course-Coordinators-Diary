<?php
session_start();
include('connection/dbconfig.php');

// Constants for pagination
$recordsPerPage = 10; // Adjust as per your requirement
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Default to page 1 if not set

// Check if session variables are set
if (!isset($_SESSION['subject']) || !isset($_SESSION['which_ia'])) {
    die('Session variables not set. Please ensure you have selected a subject and IA.');
}

$sub_code = $_SESSION['subject'];
$which_ia = $_SESSION['which_ia'];
$co_data = $_SESSION['co_data'];
$questions_data = json_decode($_SESSION['questions_data_json'], true);

//  Group questions by COs
$co_questions = [];
foreach ($co_data as $co => $co_name) {
     if (!isset($co_questions[$co_name])) {
        $co_questions[$co_name] = [];
    }

    foreach ($questions_data as $question => $max_marks) {
        if (strpos($question, $co) !== false) {
            $co_questions[$co_name][] = $question;
        }
    }
}

// Get the selected semester from the URL
$semester = isset($_GET['semester']) ? $_GET['semester'] : die('Semester not specified.');

// Fetch the subject name and total average marks from the database
$subjectQuery = "SELECT sub_name, avg FROM subjects WHERE sub_code='$sub_code'";
$subjectResult = mysqli_query($conn, $subjectQuery);
if (!$subjectResult) {
    die("Query failed: " . mysqli_error($conn));
}
$subjectRow = mysqli_fetch_assoc($subjectResult);
$sub_name = $subjectRow['sub_name'] ?? 'Unknown';
$total_avg_marks = (float) ($subjectRow['avg'] ?? 0);

// Determine which semester table to use based on the selected semester
$semesterTable = '';
switch ($semester) {
    case 1:
        $semesterTable = 'first_semester';
        break;
    case 2:
        $semesterTable = 'second_semester';
        break;
    case 3:
        $semesterTable = 'third_semester';
        break;
    case 4:
        $semesterTable = 'fourth_semester';
        break;
    case 5:
        $semesterTable = 'fifth_semester';
        break;
    case 6:
        $semesterTable = 'sixth_semester';
        break;
    case 7:
        $semesterTable = 'seventh_semester';
        break;
    case 8:
        $semesterTable = 'eighth_semester';
        break;
    default:
        die('Invalid semester selected');
}

// Pagination logic - Calculate OFFSET for MySQL LIMIT clause
$offset = ($page - 1) * $recordsPerPage;

// Fetch students for the current page
$studentsQuery = "SELECT usn, name FROM $semesterTable LIMIT $recordsPerPage OFFSET $offset";
$studentsResult = mysqli_query($conn, $studentsQuery);
if (!$studentsResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Sanitize the subject name for use in the table name
function sanitize_table_name($name) {
    return preg_replace('/[^a-z0-9_]+/', '_', strtolower($name));
}

// Table name for storing marks
$marksTable = "sem{$semester}_" . sanitize_table_name($sub_name) . "_{$sub_code}_ia{$which_ia}";

// Debugging: Print the generated table name
error_log("Marks table name: $marksTable");

// Create the table if it does not exist
$createQuery = "
    CREATE TABLE IF NOT EXISTS $marksTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usn VARCHAR(10) NOT NULL,
        name VARCHAR(100) NOT NULL,
        questions JSON NOT NULL,
        total_marks FLOAT NOT NULL,
        total_avg_marks FLOAT NOT NULL,
        UNIQUE KEY unique_record (usn)
    )";
if (!mysqli_query($conn, $createQuery)) {
    die("Table creation failed: " . mysqli_error($conn));
}

// New table for storing IA marks aggregation
$aggregateTable = "sem{$semester}_" . sanitize_table_name($sub_name) . "_{$sub_code}_ia_aggregate";

// Debugging: Print the generated aggregate table name
error_log("Aggregate table name: $aggregateTable");

// Create the aggregate table if it does not exist
$createAggregateQuery = "
    CREATE TABLE IF NOT EXISTS $aggregateTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usn VARCHAR(10) NOT NULL,
        name VARCHAR(100) NOT NULL,
        ia1_marks FLOAT DEFAULT 0,
        ia2_marks FLOAT DEFAULT 0,
        ia3_marks FLOAT DEFAULT 0,
        total_ia_marks FLOAT DEFAULT 0,
        avg_marks FLOAT DEFAULT 0,
        UNIQUE KEY unique_record (usn)
    )";
if (!mysqli_query($conn, $createAggregateQuery)) {
    die("Aggregate table creation failed: " . mysqli_error($conn));
}

// Table name for storing CO marks
$coMarksTable = "sem{$semester}_". sanitize_table_name($sub_name). "_{$sub_code}_ia{$which_ia}_co_marks";

// Create the CO marks table if it does not exist
$createCoMarksQuery = "
    CREATE TABLE IF NOT EXISTS $coMarksTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usn VARCHAR(10) NOT NULL,
        name VARCHAR(100) NOT NULL,
        co_name VARCHAR(50) NOT NULL,
        co_marks FLOAT NOT NULL,
        UNIQUE KEY unique_record (usn, co_name)
    )";
if (!mysqli_query($conn, $createCoMarksQuery)) {
    die("CO marks table creation failed: ". mysqli_error($conn));
}


// Fetch the question attempted data from the previous page
$questions_data_json = isset($_SESSION['questions_data_json']) ? $_SESSION['questions_data_json'] : '';
$questions_data = json_decode($questions_data_json, true) ?? [];

// Handle form submission to store marks in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $errors = [];
    if (isset($_POST['co_marks']) && is_array($_POST['co_marks'])) {
        foreach ($_POST['co_marks'] as $usn => $coMarks) {
            foreach ($coMarks as $coName => $coMark) {
                // Insert into CO marks table or update existing CO marks
                $insertCoMarksQuery = "INSERT INTO $coMarksTable (usn, name, co_name, co_marks) 
                                      VALUES ('$usn', (SELECT name FROM $semesterTable WHERE usn='$usn'), '$coName', '$coMark')
                                      ON DUPLICATE KEY UPDATE co_marks='$coMark'";
                if (!mysqli_query($conn, $insertCoMarksQuery)) {
                    $errors[] = "Error inserting/updating CO marks for $usn: ". mysqli_error($conn);
                }
            }
        }
    } else {
        $errors[] = 'No CO marks data provided.';
    }
    if (isset($_POST['marks']) && is_array($_POST['marks'])) {
        foreach ($_POST['marks'] as $usn => $marks) {
            // Calculate total marks
            $total_marks = 0.0;
            foreach ($marks as $questionKey => $mark) {
                $total_marks += floatval($mark); // Assuming marks are floats
            }

            $questions_json = json_encode($marks);  // Serialize marks into JSON format

            // Insert into database or update existing marks
            $insertQuery = "INSERT INTO $marksTable (usn, name, questions, total_marks) 
                            VALUES ('$usn', (SELECT name FROM $semesterTable WHERE usn='$usn'), '$questions_json', '$total_marks')
                            ON DUPLICATE KEY UPDATE questions='$questions_json', total_marks='$total_marks'";
            if (!mysqli_query($conn, $insertQuery)) {
                $errors[] = "Error inserting/updating marks for $usn: " . mysqli_error($conn);
            } else {
                // Update the aggregate table
                $ia_column = "ia{$which_ia}_marks";
                $updateAggregateQuery = "INSERT INTO $aggregateTable (usn, name, $ia_column) 
                                         VALUES ('$usn', (SELECT name FROM $semesterTable WHERE usn='$usn'), '$total_marks')
                                         ON DUPLICATE KEY UPDATE $ia_column='$total_marks'";
                if (!mysqli_query($conn, $updateAggregateQuery)) {
                    $errors[] = "Error updating aggregate marks for $usn: " . mysqli_error($conn);
                } // Calculate total IA marks and average marks
                $stmt = $conn->prepare("SELECT ia1_marks, ia2_marks, ia3_marks FROM $aggregateTable WHERE usn = ?");
                $stmt->bind_param("s", $usn);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    $iaRow = $result->fetch_assoc();
                    $totalIAMarks = floatval($iaRow['ia1_marks']) + floatval($iaRow['ia2_marks']) + floatval($iaRow['ia3_marks']);
                    $avgMarks =($totalIAMarks * $total_avg_marks) / 90;
                    // Assuming maximum IA marks is 90
                    $stmt = $conn->prepare("UPDATE $aggregateTable SET total_ia_marks = ?, avg_marks = ? WHERE usn = ?");
                    $stmt->bind_param("dds", $totalIAMarks, $avgMarks, $usn);
                    if (!$stmt->execute()) {
                        $errors[] = "Error updating total IA marks for $usn: " . $stmt->error;
                    }
                } else {
                    $errors[] = "Error fetching IA marks for $usn: " . $stmt->error;
                }
            }
        }
    } else {
        $errors[] = 'No marks data provided.';
    }

    if (empty($errors)) {
    echo "<div id='success-message' class='alert alert-success'>Marks uploaded successfully!</div>";
   ?>
    <?php
} else {
    foreach ($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}
}

// Pagination logic - Fetch total number of records
$countQuery = "SELECT COUNT(*) AS total FROM $semesterTable";
$countResult = mysqli_query($conn, $countQuery);
if (!$countResult) {
    die("Query failed: " . mysqli_error($conn));
}
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// Calculate total pages
$totalPages = ceil($totalRecords / $recordsPerPage);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
.container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    max-width: 100%;
    width: 90%;
    height: auto;
    overflow-x: auto;
}
h2 {
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.table-responsive {
    margin-bottom: 20px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
}

.form-group input {
    border-radius: 5px;
    padding: 8px;
    width: 100%;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    width: 100%;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.alert {
    margin-top: 20px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination .page-link {
    color: #3498db;
}

.pagination .page-link:hover {
    background-color: #3498db;
    color: #fff;
}
.btn-group {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.btn-secondary,.btn-back {
  background-color: #6c757d;
  border-color: #6c757d;
  color: #fff;
  padding: 5px 10px; /* Reduced padding to make the button smaller */
  font-size: 14px; /* Reduced font size to make the button smaller */
  border-radius: 5px;
  width: 60px; /* Reduced width to make the button smaller */
  margin: 0 10px;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #5a6268;
  cursor: pointer;
}

.btn-secondary:active {
  background-color: #4d5357;
  border-color: #4d5357;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.btn-back {
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-back:hover {
  background-color: #c82333;
  border-color: #c82333;
}

.btn-back:active {
  background-color: #b21f2a;
  border-color: #b21f2a;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body>
<header class="header">
    <nav class="nav">
        <div class="logo-container">
  <img src="image/logo.png" alt="Logo" class="logo-image">
        </div>
        <div class="logo">Student Database Manager</div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
        </ul>
    </nav>
</header>
    <div class="container">
        <h1>Upload Marks for <?php echo htmlspecialchars($sub_name); ?> - IA <?php echo $which_ia; ?></h1>
        <form action="" method="post">
            <input type="hidden" name="semester" value="<?php echo $semester; ?>">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>USN</th>
                            <th>Name</th>
                            <?php
                            // Fetch question columns dynamically
                            if (!empty($questions_data)) {
                                foreach ($questions_data as $question => $max_marks) {
                                    echo "<th>$question (Max: $max_marks)</th>";
                                }
                            }

                            // Fetch CO question columns dynamically
                            foreach ($co_questions as $co_name => $questions) {
                                echo "<th>total marks of $co_name</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($studentsResult) > 0) {
                            while ($student = mysqli_fetch_assoc($studentsResult)) {
                                $usn = htmlspecialchars($student['usn']);
                                $name = htmlspecialchars($student['name']);
                                echo "<tr>";
                                echo "<td>$usn</td>";
                                echo "<td>$name</td>";

                               // Render input fields for marks
if (!empty($questions_data)) {
    foreach ($questions_data as $question => $max_marks) {
        echo "<td><input type='number' id='mark-$usn-$question' name='marks[$usn][$question]' step='' min='0' max='$max_marks' onchange='updateCoMarks(this, \"$usn\")'></td>";
    }
}

// Render input fields for CO marks
foreach ($co_questions as $co_name => $questions) {
    echo "<td><input type='number' id='co-mark-$usn-$co_name' name='co_marks[$usn][$co_name]' readonly></td>";
}
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No students found for the selected semester.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="btn-group">
            <button type="submit" name="upload" class="btn btn-secondary btn-back">Upload Marks</button>
            <button type="button" class="btn btn-secondary btn-back" onclick="window.history.back();">Back</button>
        </form>
            </div>
           

        <!-- Pagination links -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?semester=<?php echo $semester; ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?semester=<?php echo $semester; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?semester=<?php echo $semester; ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <script>
    var co_questions = <?php echo json_encode($co_questions);?>;

    function updateCoMarks(input, usn) {
    // Check if all COs are the same
    var allCosSame = true;
    var firstCo = Object.keys(co_questions)[0];
    for (var co in co_questions) {
        if (co_questions[co].join(',') !== co_questions[firstCo].join(',')) {
            allCosSame = false;
            break;
        }
    }

    // Iterate over COs
    Object.keys(co_questions).forEach(function(co) {
        if (allCosSame) {
            // Calculate total marks for the current CO
            var total_marks = 0;
            co_questions[co].forEach(function(question) {
                var question_input = document.getElementById('mark-' + usn + '-' + question);
                var mark = parseFloat(question_input.value) || 0;
                total_marks += mark;
            });
            var co_mark_input = document.getElementById('co-mark-' + usn + '-' + co);
            co_mark_input.value = total_marks;
        } else {
            // Initialize max combination marks for the current CO
            var max_combination_sum = 0;

            // Iterate over questions for the current CO in pairs
            for (var i = 0; i < co_questions[co].length; i += 2) {
                var question1 = co_questions[co][i];
                var question2 = co_questions[co][i + 1];

                // Fetch input values for the questions
                var question1_input = document.getElementById('mark-' + usn + '-' + question1);
                var question2_input = document.getElementById('mark-' + usn + '-' + question2);

                var mark1 = parseFloat(question1_input.value) || 0;
                var mark2 = parseFloat(question2_input.value) || 0;

                // Calculate sum of marks for the current combination
                var combination_sum = mark1 + mark2;

                // Update max combination sum if current combination sum is higher
                if (combination_sum > max_combination_sum) {
                    max_combination_sum = combination_sum;
                }
            }

            // Update CO marks input field with the max combination sum for the CO
            var co_mark_input = document.getElementById('co-mark-' + usn + '-' + co);
            co_mark_input.value = max_combination_sum;
        }
    });
}
</script>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>