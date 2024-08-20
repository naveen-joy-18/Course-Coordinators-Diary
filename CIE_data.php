<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'students_db';
$username = 'root';
$password = ''; // Update with your MySQL password if necessary

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the table exists and create it if it does not
    $tableName = 'ciee_details';

    $result = $pdo->query("SHOW TABLES LIKE '$tableName'");
    if ($result->rowCount() == 0) {
        // Table does not exist, create it
        $createTableSQL = "
            CREATE TABLE $tableName (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usn VARCHAR(15) NOT NULL,
                name VARCHAR(100) NOT NULL,
                ia1_marks DECIMAL(5,2),
                ia2_marks DECIMAL(5,2),
                ia3_marks DECIMAL(5,2),
                avg_marks DECIMAL(5,2),
                other_assessment_marks DECIMAL(5,2),
                total_ia_marks DECIMAL(5,2),
                see_marks DECIMAL(5,2),
                total_marks DECIMAL(5,2),
                see_percentage DECIMAL(5,2),
                grade VARCHAR(5)
            )
        ";
        $pdo->exec($createTableSQL);
    }

    // Decode the JSON data sent from JavaScript
    $requestData = json_decode(file_get_contents('php://input'), true);

    if ($requestData === null) {
        throw new Exception('Invalid JSON data.');
    }

    // Prepare SQL statement for inserting data into 'ciee_details' table
    $stmt = $pdo->prepare("
        INSERT INTO ciee_details (usn, name, ia1_marks, ia2_marks, ia3_marks, avg_marks, other_assessment_marks, total_ia_marks, see_marks, total_marks, see_percentage, grade)
        VALUES (:usn, :name, :ia1_marks, :ia2_marks, :ia3_marks, :avg_marks, :other_assessment_marks, :total_ia_marks, :see_marks, :total_marks, :see_percentage, :grade)
    ");

    // Insert each row of data into the database
    foreach ($requestData['data'] as $row) {
        $stmt->execute([
            'usn' => $row['usn'],
            'name' => $row['name'],
            'ia1_marks' => $row['ia1_marks'],
            'ia2_marks' => $row['ia2_marks'],
            'ia3_marks' => $row['ia3_marks'],
            'avg_marks' => $row['avg_marks'],
            'other_assessment_marks' => $row['other_assessment_marks'],
            'total_ia_marks' => $row['total_ia_marks'],
            'see_marks' => $row['see_marks'],
            'total_marks' => $row['total_marks'],
            'see_percentage' => $row['see_percentage'],
            'grade' => $row['grade']
        ]);
    }

    // Respond with a success message
    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.']);

} catch (PDOException $e) {
    // Handle database connection or query errors
    echo json_encode(['status' => 'error', 'message' => 'Failed to save data. ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>
