<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matched Data</title>
    <link rel="stylesheet" href="style/styles.css">

    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: auto;
            margin: 0;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 5px;
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
    </div>
</header>
<br><br><br><br><br><br><br>
<?php
session_start();
include('connection/dbconfig.php');
require 'vendor/autoload.php'; // Load the PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$matchedData = [];

$semester = "";
$course = "";
$year = "";
$subject = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester = $_POST['semester'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $subject = $_POST['subject'];

    $file = $_FILES['file']['tmp_name'];
    
    // Store the branch, semester, year, and subject information in session
    $_SESSION['semester'] = $semester;
    $_SESSION['course'] = $course;
    $_SESSION['year'] = $year;
    $_SESSION['subject'] = $subject;

    // Display the branch, semester, year, and subject at the top of the page
    echo "<div id='print-table'>";
    echo "<h1>COURSE EXIT SURVEY</h1>";
    echo "<h1>Branch: $course</h1>";
    echo "<h1>Semester: $semester</h1>";
    echo "<h1>Year: $year</h1>";
    echo "<h1>Subject: $subject</h1>";
    
    // Load the Excel file
    $spreadsheet = IOFactory::load($file);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    // Prepare query to fetch data from the selected semester table
    $sql = "SELECT usn, name FROM $semester";
    $result = $conn->query($sql);

    $dbData = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dbData[] = $row;
        }
    }

    // Compare data and prepare the result set
    foreach ($sheetData as $row) {
        if (!isset($row['A']) || !isset($row['B']) || !isset($row['C']) || !isset($row['D']) || !isset($row['E']) || !isset($row['F']) || !isset($row['G'])) {
            continue; // Skip rows that don't have all required columns
        }
        
        $excelUSN = $row['A'];
        $excelName = $row['B'];
        $lastThreeDigits = substr($excelUSN, -3);
        
        foreach ($dbData as $dbRow) {
            $dbUSN = $dbRow['usn'];
            $dbLastThreeDigits = substr($dbUSN, -3);
            
            if ($lastThreeDigits == $dbLastThreeDigits) {
                $matchedData[] = [
                    'usn' => $excelUSN,
                    'name' => $excelName,
                    'co1' => $row['C'],
                    'co2' => $row['D'],
                    'co3' => $row['E'],
                    'co4' => $row['F'],
                    'co5' => $row['G']
                ];
                break;
            }
        }
    }

    // Sort the matched data
    usort($matchedData, function($a, $b) {
        return strcmp($a['usn'], $b['usn']);
    });

    // Initialize counters for each score in each CO
    $scoresCount = [
        'co1' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
        'co2' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
        'co3' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
        'co4' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
        'co5' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]
    ];

    // Initialize counters for scores 3, 4, and 5 in each CO
    $highScoresCount = ['co1' => 0, 'co2' => 0, 'co3' => 0, 'co4' => 0, 'co5' => 0];

    // Calculate totals for each CO and update score counters
    $totals = ['co1' => 0, 'co2' => 0, 'co3' => 0, 'co4' => 0, 'co5' => 0];
    foreach ($matchedData as $data) {
        // Add checks to initialize keys if they do not exist
        if (!isset($scoresCount['co1'][$data['co1']])) {
            $scoresCount['co1'][$data['co1']] = 0;
        }
        if (!isset($scoresCount['co2'][$data['co2']])) {
            $scoresCount['co2'][$data['co2']] = 0;
        }
        if (!isset($scoresCount['co3'][$data['co3']])) {
            $scoresCount['co3'][$data['co3']] = 0;
        }
        if (!isset($scoresCount['co4'][$data['co4']])) {
            $scoresCount['co4'][$data['co4']] = 0;
        }
        if (!isset($scoresCount['co5'][$data['co5']])) {
            $scoresCount['co5'][$data['co5']] = 0;
        }
    
        // Increment counts
        $scoresCount['co1'][$data['co1']]++;
        $scoresCount['co2'][$data['co2']]++;
        $scoresCount['co3'][$data['co3']]++;
        $scoresCount['co4'][$data['co4']]++;
        $scoresCount['co5'][$data['co5']]++;

        // Increment totals
        $totals['co1'] += $data['co1'];
        $totals['co2'] += $data['co2'];
        $totals['co3'] += $data['co3'];
        $totals['co4'] += $data['co4'];
        $totals['co5'] += $data['co5'];
    
        if ($data['co1'] >= 3) $highScoresCount['co1']++;
        if ($data['co2'] >= 3) $highScoresCount['co2']++;
        if ($data['co3'] >= 3) $highScoresCount['co3']++;
        if ($data['co4'] >= 3) $highScoresCount['co4']++;
        if ($data['co5'] >= 3) $highScoresCount['co5']++;
    }

    // Calculate total number of students
    $totalStudents = count($matchedData);

    // Store the matched data in session
    $_SESSION['matchedData'] = $matchedData;

    // Display the matched data
    echo "<table id='print-table'>";
    echo "<tr><th>USN</th><th>Name</th><th>CO1</th><th>CO2</th><th>CO3</th><th>CO4</th><th>CO5</th></tr>";
    foreach ($matchedData as $data) {
        echo "<tr>";
        echo "<td>{$data['usn']}</td>";
        echo "<td>{$data['name']}</td>";
        echo "<td>{$data['co1']}</td>";
        echo "<td>{$data['co2']}</td>";
        echo "<td>{$data['co3']}</td>";
        echo "<td>{$data['co4']}</td>";
        echo "<td>{$data['co5']}</td>";
        echo "</tr>";
    }
    // Display the totals row
    echo "<tr>";
    echo "<td colspan='2'><strong>Total</strong></td>";
    echo "<td><strong>{$totals['co1']}</strong></td>";
    echo "<td><strong>{$totals['co2']}</strong></td>";
    echo "<td><strong>{$totals['co3']}</strong></td>";
    echo "<td><strong>{$totals['co4']}</strong></td>";
    echo "<td><strong>{$totals['co5']}</strong></td>";
    echo "</tr>";
    // Display the total number of students
    echo "<tr>";
    echo "<td colspan='7'><strong>Total Students: {$totalStudents}</strong></td>";
    echo "</tr>";
    // Display the scores count for each CO
    for ($score = 1; $score <= 5; $score++) {
        echo "<tr>";
        echo "<td colspan='2'><strong>Score $score Count</strong></td>";
        echo "<td><strong>{$scoresCount['co1'][$score]}</strong></td>";
        echo "<td><strong>{$scoresCount['co2'][$score]}</strong></td>";
        echo "<td><strong>{$scoresCount['co3'][$score]}</strong></td>";
        echo "<td><strong>{$scoresCount['co4'][$score]}</strong></td>";
        echo "<td><strong>{$scoresCount['co5'][$score]}</strong></td>";
        echo "</tr>";
    }
    // Display the count of scores 3, 4, and 5 for each CO
    echo "<tr>";
    echo "<td colspan='2'><strong>Score 3, 4, and 5 Count</strong></td>";
    echo "<td><strong>{$highScoresCount['co1']}</strong></td>";
    echo "<td><strong>{$highScoresCount['co2']}</strong></td>";
    echo "<td><strong>{$highScoresCount['co3']}</strong></td>";
    echo "<td><strong>{$highScoresCount['co4']}</strong></td>";
    echo "<td><strong>{$highScoresCount['co5']}</strong></td>";
    echo "</tr>";

    // Display the percentage of attainment for scores 3, 4, 5 in each CO
echo "<tr>";
echo "<td colspan='2'><strong>% Attainment (Scores 3, 4, 5)</strong></td>";
if ($totalStudents > 0) {
    echo "<td><strong>" . number_format(($highScoresCount['co1'] / $totalStudents) * 100, 2) . "%</strong></td>";
    echo "<td><strong>" . number_format(($highScoresCount['co2'] / $totalStudents) * 100, 2) . "%</strong></td>";
    echo "<td><strong>" . number_format(($highScoresCount['co3'] / $totalStudents) * 100, 2) . "%</strong></td>";
    echo "<td><strong>" . number_format(($highScoresCount['co4'] / $totalStudents) * 100, 2) . "%</strong></td>";
    echo "<td><strong>" . number_format(($highScoresCount['co5'] / $totalStudents) * 100, 2) . "%</strong></td>";
} else {
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
}
echo "</tr>";
    // Display the target >= 40% for each CO
echo "<tr>";
echo "<td colspan='2'><strong>Target >= 40%</strong></td>";
if ($totalStudents > 0) {
    echo "<td><strong>". (($highScoresCount['co1'] / $totalStudents) * 100 >= 40? 'Yes' : 'No'). "</strong></td>";
    echo "<td><strong>". (($highScoresCount['co2'] / $totalStudents) * 100 >= 40? 'Yes' : 'No'). "</strong></td>";
    echo "<td><strong>". (($highScoresCount['co3'] / $totalStudents) * 100 >= 40? 'Yes' : 'No'). "</strong></td>";
    echo "<td><strong>". (($highScoresCount['co4'] / $totalStudents) * 100 >= 40? 'Yes' : 'No'). "</strong></td>";
    echo "<td><strong>". (($highScoresCount['co5'] / $totalStudents) * 100 >= 40? 'Yes' : 'No'). "</strong></td>";
} else {
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
    echo "<td><strong>N/A</strong></td>";
}
echo "</tr>";
    echo "</table>";
    echo "</div>";
}
$conn->close();
?>

<div class="center-button">
                <button onclick="printTable()">Print Table</button>
                <button onclick="window.location.href='course_exit_survey.php'">Go Back</button>
            </div>
<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
<script>
              function printTable() {
    var table = document.getElementById("print-table");
    window.print();
}
            </script>
</html>
