<?php
include('connection/dbconfig.php');

$semester = $_POST['semester'];
$subject = $_POST['subject'];
$scaledown = $_POST['scaledown'];
$total_max_marks = $_POST['total_max_marks'];
$marks = $_POST['marks'];
$max_marks = json_decode($_POST['max_marks_json'], true);

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
$student_table_name = $semesters[$semester];

// Generate a table name for storing assessment marks
$table_name = "sem{$semester}_{$subject}_Assessment_Marks";
$table_name = str_replace(' ', '_', strtolower($table_name)); // Normalize the table name

// Create the table if it doesn't exist
$createQuery = "
    CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usn VARCHAR(20) NOT NULL,
        name VARCHAR(100) NOT NULL,
        total_marks FLOAT NOT NULL,
        scaled_marks FLOAT NOT NULL,
        UNIQUE(usn)
    )";

if ($conn->query($createQuery) !== TRUE) {
    die("Error creating table: " . $conn->error . "<br>");
}

// Prepare statements for inserting or updating marks
$sql_student = "SELECT name FROM $student_table_name WHERE usn = ?";
$sql_insert = "INSERT INTO $table_name (usn, name, total_marks, scaled_marks)
               VALUES (?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE total_marks = ?, scaled_marks = ?";

$stmt_student = $conn->prepare($sql_student);
$stmt_insert = $conn->prepare($sql_insert);

if (!$stmt_student || !$stmt_insert) {
    die("Error preparing statements: " . $conn->error . "<br>");
}

// Insert or update the marks for each student
foreach ($marks as $usn => $assessments) {
    $total_marks = array_sum($assessments);
    $scaled_marks = ($total_marks * $scaledown) / $total_max_marks;

    // Fetch the student's name
    $stmt_student->bind_param("s", $usn);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    if ($result_student->num_rows > 0) {
        $student = $result_student->fetch_assoc();
        $name = $student['name'];

        // Insert or update the student's marks
        $stmt_insert->bind_param("ssddds", $usn, $name, $total_marks, $scaled_marks, $total_marks, $scaled_marks);
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo "Marks for USN '$usn' saved successfully.<br>";
        } else {
            echo "Error inserting or updating marks for USN '$usn': " . $conn->error . "<br>";
        }
    } else {
        echo "Student with USN '$usn' not found in semester table.<br>";
    }
}

$stmt_student->close();
$stmt_insert->close();
$conn->close();
?>
