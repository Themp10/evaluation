<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "db.php";


function updatePass($username, $password) {
  global $conn;

  // Hash the password before updating
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $sql = "UPDATE users SET pwd = ?, pwd_changed = 1 WHERE id = ?";
  $stmt = $conn->prepare($sql);
  
  if (!$stmt) {
      die("Error preparing the SQL query: " . $conn->error);
  }

  $stmt->bind_param("si", $hashedPassword, $username);
  $stmt->execute();
  $stmt->close();

  $_SESSION['pwdChanged'] = true;
  header('Location: index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $newPass = $_POST['password'];
    $user = $_SESSION['user_id'];
    updatePass($user,$newPass);
    exit(); 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Evaluation Annuelle Groupe Mfadel</title>
<link rel="stylesheet" href="style.css">

<style>

</style>
</head>
<div class="login-body">
    <div class="login-container">
      <h2>Modification du mot de passe après votre première connexion</h2>
      <form class="users-form" action="newPass.php" method="post">
        <input class="login-input" type="text" name="password" placeholder="Nouveau Mot de passe" required>
        <button type="submit" class="login-submit">Modifier</button>
      </form>
      <div class="footer">
        GROUPE MFADEL - DOSI
      </div>
    </div>
</div>
</html>
