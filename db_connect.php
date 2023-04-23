<?php
$servername = "localhost";
$username = "eltechu1_root";
$password = "E72782001e@";
$databasename = "eltechu1_testbot";

global $conn;
// Create connection
$conn = new mysqli($servername, $username, $password,$databasename,3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";