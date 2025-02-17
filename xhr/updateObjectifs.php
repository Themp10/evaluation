<?php
session_start();
include '../db.php'; 
$data = json_decode(file_get_contents('php://input'), true);

$annee = date('Y') - 1;
if (isset($_SESSION['collaborateurId'])){
    $id_user = $_SESSION['collaborateurId'];

}else{
    $id_user = $_SESSION['user_id'];
    $query = "UPDATE validation SET saisie = ? WHERE annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        $saisie = 1;
        $year = $annee+1;
        $stmt->bind_param('iii', $saisie, $year, $id_user);
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

if ($data && isset($data['objectives']) && isset($data['softSkills'])) {
    $objectives = $data['objectives'];
    $softSkills = $data['softSkills'];
    $query = "UPDATE objectifs SET realisation = ?, score = ?, resultat_analyse = ? WHERE id_ligne = ? AND annee = ? AND user = ?";
    if ($stmt = $conn->prepare($query)) {
        foreach ($objectives as $index => $objectif) {
            $realisation = $objectif['realisation'];
            $score = $objectif['score'];
            $commentaire = $objectif['commentaire'];
            $id_ligne = $objectif['index'];

            $stmt->bind_param('sdsiii', $realisation, $score, $commentaire, $id_ligne, $annee, $id_user);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Objectifs Query execution failed: ' . $stmt->error]);
                exit;
            }
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
        exit;
    }
    $querySoftSkills = "UPDATE softskills SET point = ?, score = ?, commentaire = ? WHERE id_ss = ? AND annee = ? AND user = ?";
    if ($stmtSoftSkills = $conn->prepare($querySoftSkills)) {
        foreach ($softSkills as $softSkill) {
            $id_ss = $softSkill['index'];
            $point = $softSkill['point'];
            $score = $softSkill['score'];
            $commentaire = $softSkill['commentaire'];
            $stmtSoftSkills->bind_param('sisiii', $point, $score, $commentaire, $id_ss, $annee, $id_user);
            if (!$stmtSoftSkills->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Soft skills query execution failed: ' . $stmtSoftSkills->error]);
                exit;
            }
        }
        $stmtSoftSkills->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare soft skills query: ' . $conn->error]);
        exit;
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input or missing data']);
}

$conn->close();
?>
