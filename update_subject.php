<?php
session_start();
include('connection/dbconfig.php');

// Create DB Connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $semester = $_POST['semester'];
    $sub_code = $_POST['sub_code'];
    $sub_name = $_POST['sub_name'];
    $total_marks = $_POST['total_marks'];
    $num_internals = $_POST['num_internals'];
    $max_marks_each_internal = $_POST['max_marks_each_internal'];
    $theory_ia_marks = $_POST['theory_ia_marks'];
    $mini_project_marks = $_POST['mini_project_marks'];

    // Prepare SQL statement
    $sql = "UPDATE Subjects SET semester=?, sub_code=?, sub_name=?, total_marks=?, num_internals=?, max_marks_each_internal=?, theory_ia_marks=?, mini_project_marks=? WHERE id=?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("issiiiiii", $semester, $sub_code, $sub_name, $total_marks, $num_internals, $max_marks_each_internal, $theory_ia_marks, $mini_project_marks, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>
