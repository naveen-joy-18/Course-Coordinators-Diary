<?php
require 'db_connection.php';

if (isset($_POST['semester'])) {
    $selectedSemester = filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("SELECT sub_code, sub_name FROM subjects WHERE semester = ?");
    $stmt->bind_param("i", $selectedSemester);
    $stmt->execute();
    $subjectsResult = $stmt->get_result();
    $subjects = $subjectsResult->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    echo json_encode($subjects);
}
?>
