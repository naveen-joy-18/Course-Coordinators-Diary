<?php
session_start();
include('connection/dbconfig.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['save_excel_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if(in_array($file_ext, $allowed_ext)) {
        $semester = $_POST['semester']; // Get the selected semester from the form
        $table_name = '';

        // Ensure the selected semester matches the predefined table names
        switch ($semester) {
            case '1st':
                $table_name = 'first_semester';
                break;
            case '2nd':
                $table_name = 'second_semester';
                break;
            case '3rd':
                $table_name = 'third_semester';
                break;
            case '4th':
                $table_name = 'fourth_semester';
                break;
            case '5th':
                $table_name = 'fifth_semester';
                break;
            case '6th':
                $table_name = 'sixth_semester';
                break;
            case '7th':
                $table_name = 'seventh_semester';
                break;
            case '8th':
                $table_name = 'eighth_semester';
                break;
            default:
                die('Invalid semester selected');
        }
        

        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach($data as $row) {
            if($count > 0) {
                $usn = $row[0];
                $name = $row[1];
                $branch = $row[2];
                $batch = $row[3];
                $semester = $row[4];
                $gender = $row[5];
                $dateofbirth = $row[6];
                $gmail = $row[7];
                $phonenumber = $row[8];
                $address = $row[9];

                // Check if the entry already exists
                $checkQuery = "SELECT * FROM `$table_name` WHERE usn = '$usn'";
                $result = mysqli_query($conn, $checkQuery);
                if(mysqli_num_rows($result) == 0) {
                    // Entry does not exist, insert it
                    $studentQuery = "INSERT INTO `$table_name` (usn, name, branch, batch, semester, gender, dateofbirth, gmail, phonenumber, address) VALUES ('$usn', '$name', '$branch', '$batch', '$semester', '$gender', '$dateofbirth', '$gmail', '$phonenumber', '$address')";
                    mysqli_query($conn, $studentQuery);
                }
            } else {
                $count++;
            }
        }

        $_SESSION['message'] = "Data imported successfully";
        header('Location: data_upload.php');
        exit();
    } else {
        $_SESSION['message'] = "Invalid file format";
        header('Location: data_upload.php');
        exit();
    }
}
?>
