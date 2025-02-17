<?php
session_start();
include '../db.php'; 
$data = json_decode(file_get_contents('php://input'), true);
$date=date('Y-m-d');
if (isset($_SESSION['collaborateurId'])){
    $id_user = $_SESSION['collaborateurId'];
    $annee = date('Y');

    $sql="UPDATE validation SET validationDG = '1', date_validationDG =? WHERE annee =? AND user =?";
    if ($stmt = $conn->prepare($sql)) {
        $thisannee = date('Y');
        $stmt->bind_param('sii', $date, $annee, $id_user);
        $stmt->execute();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'success' . $conn->error]);
}

$conn->close();


?>