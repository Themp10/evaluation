<?php


// $username = "sa";
// $password = "MG+P@ssw0rd";

$username = "root";
$password = "";

$servername = "localhost";
$dbname = "evaluation";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>