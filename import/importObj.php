<?php
$host = 'localhost';
$username = "root";
$password = "";
$database = "evaluation";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$file = 'objectifs.txt';


// Open the file for reading
if (($handle = fopen($file, "r")) !== FALSE) {
    // Skip the header row
    fgetcsv($handle, 1000, "\t");

    // Read each line
    while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
        // Assign variables to the columns
        $id_ligne = (int) $data[0];
        $annee = (int) $data[1];
        $user = (int) $data[2];
        $objectif = $conn->real_escape_string($data[3]);
        $indicateur = $conn->real_escape_string($data[4]);
        $echeance = $conn->real_escape_string($data[5]);

        // Insert into the database
        $sql = "INSERT INTO objectifs (id_ligne, annee, user, objectif, indicateur, echeance)
                VALUES ('$id_ligne', '$annee', '$user', '$objectif', '$indicateur', '$echeance')";

        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    fclose($handle);
    echo "Data imported successfully!";
} else {
    echo "Failed to open the file.";
}

// Close the connection
$conn->close();
?>
