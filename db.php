<?php
$servername = "localhost";
$username = "piyush";
$password = "piyush21";
$dbname = "ai_website";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
