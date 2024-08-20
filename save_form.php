<?php
session_start();
include('connection/dbconfig.php');

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $courseCode = $_POST['courseCode'];
  $courseTitle = $_POST['courseTitle'];
  $academicYear = $_POST['academicYear'];
  $poMapped = $_POST['poMapped'];
  $courseCoordinator = $_POST['courseCoordinator'];
  $courseCoordinatorDesign = $_POST['courseCoordinatorDesign'];
  $streamExpert = $_POST['streamExpert'];
  $streamExpertDesign = $_POST['streamExpertDesign'];
  $date = $_POST['date'];

  // Prepare SQL statement using prepared statements to avoid SQL injection
  $stmt = $conn->prepare("INSERT INTO checklist (courseCode, courseTitle, academicYear, poMapped, courseCoordinator, courseCoordinatorDesign, streamExpert, streamExpertDesign, dateCompleted)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssssss", $courseCode, $courseTitle, $academicYear, $poMapped, $courseCoordinator, $courseCoordinatorDesign, $streamExpert, $streamExpertDesign, $date);

  if ($stmt->execute()) {
    echo "Form data saved successfully.";
  } else {
    echo "Error: " . $stmt->error;
  }
}

// Close connection
$conn->close();
?>
