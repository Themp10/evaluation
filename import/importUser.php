<?php
require 'vendor/autoload.php'; // Autoload PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

$host = 'localhost';
$username = "sa";
$password = "MG+P@ssw0rd";
$database = "evaluation";
// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load the Excel file
$filePath = 'users.xlsx'; // Replace with your Excel file name
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray(null, true, true, true);

// Loop through the rows and insert data into the database
foreach ($data as $rowIndex => $row) {
    if ($rowIndex == 1) {
        // Skip the header row
        continue;
    }

    $login = $conn->real_escape_string($row['B']);
    $nom = $conn->real_escape_string($row['C']);
    $prenom = $conn->real_escape_string($row['D']);
    $date_embauche = $conn->real_escape_string($row['E']);
    $direction = $conn->real_escape_string($row['F']);
    $poste = $conn->real_escape_string($row['G']);
    $responsable = $conn->real_escape_string($row['H']);
    $pwd = $conn->real_escape_string($row['I']);
    $collaborateurs = $conn->real_escape_string($row['J']);
    $valideur2 = $conn->real_escape_string($row['K']);

    $sql = "INSERT INTO users (login, nom, prenom, date_embauche, direction, poste, responsable, pwd, collaborateurs, valideur2)
            VALUES ('$login', '$nom', '$prenom', '$date_embauche', '$direction', '$poste', '$responsable', '$pwd', '$collaborateurs','$valideur2')";

    if (!$conn->query($sql)) {
        echo "Error inserting row $rowIndex: " . $conn->error . PHP_EOL;
    }
}

echo "Data imported successfully.";

// Close the connection
$conn->close();
?>
