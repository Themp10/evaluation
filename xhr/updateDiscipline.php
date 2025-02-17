<?php
session_start();
include '../db.php'; 
$data = json_decode(file_get_contents('php://input'), true);
$date=date('Y-m-d');

if (isset($_SESSION['collaborateurId'])){
    $id_user = $_SESSION['collaborateurId'];
    $annee = date('Y') - 1;
    $nb_sanctions= $data['nb_sanctions'];
    $obj_sanctions= $data['obj_sanctions'];
    $assiduite= $data['assiduite'];
    $commentaire= $data['commentaire'];
    
    $query = "UPDATE discipline SET nb_sanctions= ? , obj_sanctions= ? , assiduite= ? , commentaire= ? WHERE annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssssii', $nb_sanctions, $obj_sanctions, $assiduite, $commentaire, $annee, $id_user);
        if (!$stmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Validation Query execution failed: ' . $stmt->error]);
            exit;
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }


    $sql="UPDATE validation SET validationRH = '1', date_validationRH =? WHERE annee =? AND user =?";
    if ($stmt = $conn->prepare($sql)) {
        $thisannee = date('Y');
        $stmt->bind_param('sii', $date, $thisannee, $id_user);
        $stmt->execute();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'success' . $conn->error]);
}

$conn->close();
?>
