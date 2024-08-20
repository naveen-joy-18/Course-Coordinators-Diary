<?php
require 'vendor/autoload.php'; // Load the PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$matchedData = $_SESSION['matchedData'];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header row
$sheet->setCellValue('A1', 'USN');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'CO1');
$sheet->setCellValue('D1', 'CO2');
$sheet->setCellValue('E1', 'CO3');
$sheet->setCellValue('F1', 'CO4');
$sheet->setCellValue('G1', 'CO5');

// Fill data
$rowNumber = 2; // Starting from the second row
foreach ($matchedData as $data) {
    $sheet->setCellValue('A' . $rowNumber, $data['usn']);
    $sheet->setCellValue('B' . $rowNumber, $data['name']);
    $sheet->setCellValue('C' . $rowNumber, $data['co1']);
    $sheet->setCellValue('D' . $rowNumber, $data['co2']);
    $sheet->setCellValue('E' . $rowNumber, $data['co3']);
    $sheet->setCellValue('F' . $rowNumber, $data['co4']);
    $sheet->setCellValue('G' . $rowNumber, $data['co5']);
    $rowNumber++;
}

// Write the file
$writer = new Xlsx($spreadsheet);
$filename = 'matched_data.xlsx';

// Send the file to the browser as a download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
