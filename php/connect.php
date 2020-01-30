<?php
$servername = "167.99.180.7";
$username = "gaugeReaders";
$password = "Vision_Killers";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
