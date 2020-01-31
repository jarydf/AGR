<?php
$servername = "localhost";
$database = "mfac";
$username = "root";
$password = "";

$con = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
