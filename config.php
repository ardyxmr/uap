<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "akademik"; // Nama database yang Anda gunakan

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
