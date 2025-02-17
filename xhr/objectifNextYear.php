<?php
session_start();
include '../db.php';
$data = json_decode(file_get_contents('php://input'), true);
$annee = date('Y');
if (isset($_SESSION['collaborateurId'])){
    $id_user = $_SESSION['collaborateurId'];

}else{
    $id_user = $_SESSION['user_id'];
    $query = "UPDATE validation SET saisie = ? WHERE annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        $saisie = 1;
        $stmt->bind_param('iii', $saisie, $annee, $id_user);
        if (!$stmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Validation Query execution failed: ' . $stmt->error]);
            exit;
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }
}

if ($data && isset($data['objectives']) && isset($data['formations']) && isset($data['evolution'])) {

    $objectives = $data['objectives'];
    $formations = $data['formations'];
    $evolution = $data['evolution'];

    }
    //Inserer les objectifs 
    $query = "DELETE from objectifs WHERE  annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ii', $annee, $id_user);
        $stmt->execute();
        $stmt->close();
    }
    $query = "INSERT INTO objectifs (id_ligne,annee,user,objectif,echeance,indicateur) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        foreach ($objectives as $index => $objectif) {
            $id_ligne = $objectif['index'];
            $objectif_name = $objectif['objectif'];
            $echeance = $objectif['echeance'];
            $indicateur = $objectif['indicateur'];
            $stmt->bind_param('iiisss', $id_ligne, $annee, $id_user, $objectif_name, $echeance, $indicateur);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Objectifs Query execution failed: ' . $stmt->error]);
                exit;
            }
        }
        $stmt->close();
    }else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }


    //Inserer les formations 
    $query = "DELETE from formations WHERE  annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ii', $annee, $id_user);
        $stmt->execute();
        $stmt->close();
    }
    $query = "INSERT INTO formations (id_ligne,annee,user,formation,obj_formation,priorite) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        foreach ($formations as $index => $formation) {
            $id_ligne = $formation['index'];
            $formation_name = $formation['formation'];
            $obj_formation = $formation['objFormation'];
            $priorite = $formation['priorite'];
            $stmt->bind_param('iiissi', $id_ligne, $annee, $id_user, $formation_name, $obj_formation, $priorite);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'formations Query execution failed: ' . $stmt->error]);
                exit;
            }
        }
        $stmt->close();
    }else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare formations query: ' . $conn->error]);
        exit;
    }
    //Inserer les evolutions 
    $query = "DELETE from evolution WHERE  annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ii', $annee, $id_user);
        $stmt->execute();
        $stmt->close();
    $query = "INSERT INTO evolution (annee,user,souhait,motivation,axes,avis) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        foreach ($evolution as $index => $evolution1) {
            $souhait = $evolution1['souhait'];
            $motivations = $evolution1['motivations'];
            $axes = $evolution1['axes'];
            $avisresponsable = $evolution1['avisresponsable'];
            $stmt->bind_param('iiisss', $annee, $id_user, $souhait, $motivations, $axes, $avisresponsable);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'formations Query execution failed: ' . $stmt->error]);
                exit;
            }
        }
        $stmt->close();
    }else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare formations query: ' . $conn->error]);
        exit;
    }
    // Return success response
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input or missing data']);
}

$conn->close();
?>