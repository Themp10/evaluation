<?php

$servername = "localhost";
$username = "sa";
$password = "MG+P@ssw0rd";
$dbname = "evaluation";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "evaluation";
// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>