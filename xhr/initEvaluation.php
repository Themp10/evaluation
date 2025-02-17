<?php
include '../db.php';
$annee = date('Y');
$anneed = date('Y')-1;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "evaluation";
// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $sql="select id,responsable,valideur2 from users";
// $result = $conn->query($sql);
// if ($result === false) {
//     die("Error in SQL query: " . $conn->error);
// }
// $data = [];
// while ($row = $result->fetch_assoc()) {
//     $data[] = $row;
// }
$data = [['id'=>65,'responsable'=>7,'valideur2'=>3]];
//$data = [];
//loop through data
foreach ($data as $row) {
    $id = $row['id'];
    echo $id;
    $responsable = $row['responsable'];
    $valideur2 = $row['valideur2'];

//==========================insert validation=======================
    $sql = "INSERT INTO validation (annee, user,valideur1,valideur2) VALUES ('$annee', '$id','$responsable', '$valideur2')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
       exit();
    }
    echo "insert validation : Done";
//==========================insert disciplines=======================
    $sql = "INSERT INTO discipline (annee, user) VALUES ('$anneed', '$id')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    echo "insert disciplines : Done";
//==========================insert evolution=======================
    $sql = "INSERT INTO evolution (annee, user,souhait) VALUES ('$annee', '$id',1)";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "insert evolution : Done";
//==========================insert scores=======================
    $sql = "INSERT INTO scores (annee, user) VALUES ('$annee', '$id')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "insert scores : Done";
//==========================insert softskills=======================
    $sql = "INSERT INTO softskills (annee, user,id_ss) VALUES ('$anneed', '$id', '1'),
                                                        ('$anneed', '$id', '2'),
                                                        ('$anneed', '$id', '3'),
                                                        ('$anneed', '$id', '4'),
                                                        ('$anneed', '$id', '5'),
                                                        ('$anneed', '$id', '6'),
                                                        ('$anneed', '$id', '7'),
                                                        ('$anneed', '$id', '8'),
                                                        ('$anneed', '$id', '9'),
                                                        ('$anneed', '$id', '10'),
                                                        ('$anneed', '$id', '11'),
                                                        ('$anneed', '$id', '12'),
                                                        ('$anneed', '$id', '13'),
                                                        ('$anneed', '$id', '14')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }    
    echo "insert softskills : Done";
}
echo "Done";
$conn->close();
?>