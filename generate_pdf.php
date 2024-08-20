<?php
require 'vendor/autoload.php'; // Load the Dompdf library

use Dompdf\Dompdf;

session_start(); // Start the session

if (!isset($_SESSION['matchedData']) || !isset($_SESSION['course']) || !isset($_SESSION['semester'])) {
    die('No matched data or course and semester information available to generate PDF.');
}

$matchedData = $_SESSION['matchedData'];
$course = $_SESSION['course'];
$semester = $_SESSION['semester'];

// Create a new instance of Dompdf
$dompdf = new Dompdf();

// Create HTML content
$html = '<h2>Branch: ' . $course . '</h2>';
$html = '<h2>Semester: ' . $semester . '</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr><th>USN</th><th>Name</th><th>CO1</th><th>CO2</th><th>CO3</th><th>CO4</th><th>CO5</th></tr>';
foreach ($matchedData as $data) {
    $html .= '<tr>';
    $html .= '<td>' . $data['usn'] . '</td>';
    $html .= '<td>' . $data['name'] . '</td>';
    $html .= '<td>' . $data['co1'] . '</td>';
    $html .= '<td>' . $data['co2'] . '</td>';
    $html .= '<td>' . $data['co3'] . '</td>';
    $html .= '<td>' . $data['co4'] . '</td>';
    $html .= '<td>' . $data['co5'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// Load the HTML content into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set up the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('matched_data.pdf', ['Attachment' => 1]);
?>
