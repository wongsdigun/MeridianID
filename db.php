<?php
//dev
//$dbhost = 'localhost';
//$dbuser = 'root';
//$dbpass = '';
//$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Error " . mysqli_error($conn));
//$db_selected = mysqli_select_db($conn, 'db_rikues');
//prod
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Error " . mysqli_error($conn));
$db_selected = mysqli_select_db($conn, 'rikues');
?>