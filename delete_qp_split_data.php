<?php
session_start();
include('connection/dbconfig.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record from the database
    $query = "DELETE FROM qp_split_up WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: view_qp_split_data.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
