<?php
include('connection/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['semester'])) {
    $semester = intval($_POST['semester']);
    $sql = "SELECT id, sub_name FROM subjects WHERE semester='$semester'";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
        exit();
    }

    $subjects = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subjects[] = ['id' => $row['id'], 'sub_name' => $row['sub_name']];
        }
    } else {
        echo json_encode(['error' => 'No subjects found for this semester']);
    }
    echo json_encode($subjects);
}
?>
