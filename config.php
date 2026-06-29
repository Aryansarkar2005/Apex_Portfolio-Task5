<?php
// config.php - Master Database Configuration for ApexAcademy
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'apex_academy_db';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Fallback check: If database doesn't exist yet, try connecting without db_name to create it
if (!$conn) {
    $temp_conn = mysqli_connect($db_host, $db_user, $db_pass);
    if ($temp_conn) {
        mysqli_query($temp_conn, "CREATE DATABASE IF NOT EXISTS `$db_name`");
        mysqli_close($temp_conn);
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    }
}

if (!$conn) {
    die("Database connection failure: " . mysqli_connect_error());
}
?>
