<?php
include('connection/dbconfig.php');

// Retrieve and sanitize POST data
$semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
$sub_name = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$assessments = $_POST['assessments_json'];
$scaledown = filter_var($_POST['scaledown'], FILTER_SANITIZE_NUMBER_INT);
$max_marks = json_decode($_POST['max_marks_json'], true);
$cos = json_decode($_POST['cos_json'], true);
$marks = $_POST['marks'];

// Decode JSON data
$assessments = json_decode($assessments, true);

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

if ($semester) {
    $subjectQuery = "SELECT sub_name ,sub_code FROM subjects WHERE  semester='$semester'";
    $subjectResult = mysqli_query($conn, $subjectQuery);
    $subjectRow = mysqli_fetch_assoc($subjectResult);
   $sub_name = $subjectRow['sub_name'] ?? 'Unknown';
$sub_code=$subjectRow['sub_code'] ?? 'Unknown';}
// Ensure the semester value is valid and fetch the corresponding table name
if (!isset($semesters[$semester])) {
   die("Invalid semester selected.");
}
$student_table_name = $semesters[$semester];

// Generate a table name for storing assessment marks
$table_name = "sem{$semester}_{$sub_name}_{$sub_code}_assessment_marks";
$table_name = str_replace(' ', '_', strtolower($table_name)); // Normalize the table name
// Debugging: Print table name to check if it's formed correctly
error_log("Generated Table Name: $table_name");

// Create table if not exists
$createTableQuery = "CREATE TABLE IF NOT EXISTS `$table_name` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usn` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL";

foreach ($assessments as $assessment) {
    $co = $cos[$assessment];
    $columnName = $assessment . '_' . $co;
    $createTableQuery .= ", `$columnName` INT DEFAULT 0";
}
$createTableQuery .= ", `total_mark` INT DEFAULT 0, `scaled_mark` FLOAT DEFAULT 0, UNIQUE(`usn`))";

// Log table creation query
error_log("Create Table Query: $createTableQuery");

$createTableResult = $conn->query($createTableQuery);

if (!$createTableResult) {
    die("Error creating table: " . $conn->error);
}

// Fetch student names from the corresponding semester table
$studentNames = [];
$semesterTableName = $semesters[$semester];
$studentQuery = "SELECT usn, name FROM `$semesterTableName`";
$studentResult = $conn->query($studentQuery);

if ($studentResult) {
    while ($row = $studentResult->fetch_assoc()) {
        $studentNames[$row['usn']] = $row['name'];
    }
} else {
    die("Error fetching student names: " . $conn->error);
}

// Log the JSON data for debugging
error_log("Assessments: " . print_r($assessments, true));
error_log("COS: " . print_r($cos, true));
error_log("Marks: " . print_r($marks, true));

// Insert or update data in the table
$savedCount = 0;
$savedSuccessfully = true;
foreach ($marks as $usn => $studentMarks) {
    $name = isset($studentNames[$usn]) ? $studentNames[$usn] : ''; // Get name from the fetched data
    $totalMarks = 0;
    foreach ($studentMarks as $assessment => $mark) {
        $totalMarks +=intval($mark) ;
    }

    // Calculate scaled marks
    $scaledMarks = ($totalMarks / array_sum($max_marks)) * $scaledown;

    $columns = "`usn`, `name`";
    $values = "'$usn', '$name'";
    $update = "`name` = VALUES(`name`)";
    foreach ($studentMarks as $assessment => $mark) {
        $co = $cos[$assessment];
        $columnName = $assessment . '_' . $co;
        $columns .= ", `$columnName`";
        $values .= ", '$mark'";
        $update .= ", `$columnName` = VALUES(`$columnName`)";
    }
    $columns .= ", `total_mark`, `scaled_mark`";
    $values .= ", '$totalMarks', '$scaledMarks'";
    $update .= ", `total_mark` = VALUES(`total_mark`), `scaled_mark` = VALUES(`scaled_mark`)";

    $insertQuery = "INSERT INTO `$table_name` ($columns) VALUES ($values) ON DUPLICATE KEY UPDATE $update";

    // Log the insert query
    error_log("Insert Query: $insertQuery");

    $insertResult = $conn->query($insertQuery);

    if (!$insertResult) {
        $savedSuccessfully = false;
        echo "Error saving marks: " . $conn->error;
        break; // Stop the loop if there's an error
    }
}

if ($savedSuccessfully) {
    echo '<div class="success-message">Marks saved successfully!</div>';
}
?>
