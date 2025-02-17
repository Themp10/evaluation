<?php
session_start();
include '../db.php';
$data = json_decode(file_get_contents('php://input'), true);

$annee = date('Y');
$date=date('Y-m-d');
function getValideurs($id){
    $sql = "SELECT valideur1,valideur2 FROM validation WHERE user = ?";
    global $conn;
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare user query: ' . $conn->error]);
        exit;
    }
}
if (isset($_SESSION['collaborateurId'])){
    $id_user = $_SESSION['collaborateurId'];

}else{
    $id_user = $_SESSION['user_id'];
}

if ($data && isset($data['type'])) {
    if($data['type']=="submit"){
        $comm=$data['commentaire'];
        $sql = "UPDATE validation SET submit = '1', comm_evalue = ?, date_submit = ? WHERE annee = ? AND user = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssii', $comm, $date, $annee, $id_user);
            $stmt->execute();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
            exit;
        }

    }elseif($data['type']=="validate1"){
        $comm=$data['commentaire'];
        $user=getValideurs($id_user);
        if($user['valideur1']==$user['valideur2']){
            $sql="UPDATE validation SET validation1 = '1', date_validation1 =?, validation2 = '1', date_validation2 =?, comm_evaluateur =? WHERE annee =? AND user =?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('sssii', $date, $date, $comm, $annee, $id_user);
                $stmt->execute();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
                exit;
            }
        }else{
            $sql="UPDATE validation SET validation1 = '1', date_validation1 =?, comm_evaluateur =? WHERE annee =? AND user =?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('ssii', $date, $comm, $annee, $id_user);
                $stmt->execute();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
                exit;
            }

        }


        $sql="UPDATE scores SET s1 = ?, s2 = ?, s3 = ?,s4 = ?, p1 = ?, p2 = ?,p3 = ?, p4 = ?, n1 = ? ,n2 = ?, n3 = ?, n4 = ? ,ng = ?, obj = ?, finale = ?  WHERE annee = ? AND user = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ddddiiiiddddddiii',$data['s1'],$data['s2'],$data['s3'],$data['s4'],$data['p1'],$data['p2'],$data['p3'],$data['p4'],$data['n1'],$data['n2'],$data['n3'],$data['n4'],$data['ng'],$data['no'],$data['nf'],$annee, $id_user);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Objectifs Query execution failed: ' . $stmt->error]);
                exit;
            }
            $stmt->close();
        }


    }elseif($data['type']=="validate2"){
        $comm=$data['commentaire'];
        $sql="UPDATE validation SET validation2 = '1', date_validation2 =?, comm_evaluateur =? WHERE annee =? AND user =?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssii', $date, $comm, $annee, $id_user);
            $stmt->execute();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare objectifs query: ' . $conn->error]);
            exit;
        }
        $sql="UPDATE scores SET s1 = ?, s2 = ?, s3 = ?,s4 = ?, p1 = ?, p2 = ?,p3 = ?, p4 = ?, n1 = ? ,n2 = ?, n3 = ?, n4 = ? ,ng = ?, obj = ?, finale = ?  WHERE annee = ? AND user = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ddddiiiiddddddiii',$data['s1'],$data['s2'],$data['s3'],$data['s4'],$data['p1'],$data['p2'],$data['p3'],$data['p4'],$data['n1'],$data['n2'],$data['n3'],$data['n4'],$data['ng'],$data['no'],$data['nf'],$annee, $id_user);
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Objectifs Query execution failed: ' . $stmt->error]);
                exit;
            }
            $stmt->close();
        }


    }
    echo json_encode(['status' => 'success', 'message' => 'success' . $conn->error]);

}

$conn->close();
?>