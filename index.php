<?php
if (session_status() === PHP_SESSION_NONE) {

    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);
    session_start();
}
include "db.php";


if (isset($_SESSION['error_message'])) {
    $cnn_err = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the message after use
}else{
    $cnn_err='';
}

if (isset($_SESSION['pwdChanged']) and $_SESSION['pwdChanged']==true) {
    $cnn_err = "Mot de passe modifier avec succès !";
    unset($_SESSION['pwdChanged']);
}

function getUserIP() {
    return $_SERVER['REMOTE_ADDR'];
}

function login($username, $password,$access) {
    global $conn;

    $stmt = $conn->prepare("SELECT id,responsable, login, collaborateurs, pwd, pwd_changed,type FROM users WHERE login = ?");
    if (!$stmt) {
        die("Error preparing the SQL query: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    // Fetch the results

    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['pwd'])) {

            $ip = getUserIP();
            $datetime=date("Y-m-d H:i:s"); 
            $stmt = $conn->prepare("INSERT INTO log (login, date, ip) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user['login'], $datetime, $ip);
            
            if ($stmt->execute()) {
                echo "Log entry added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['access'] = $access;
    
            if ($user['pwd_changed']=='0'){
                header('Location: newPass.php');
            }else{
                if($access=="E" ){
                    if($user['id']==$user['responsable']){
                        $_SESSION['connected'] = false;
                        $cnn_err= 'Merci de selectionner l\'acces évaluateur';
                        $_SESSION['error_message'] = $cnn_err;
                        header('Location: index.php');
                        exit;
                    }
                    $_SESSION['connected'] = true;
                    header('Location: evalue.php');
        
                }elseif($access=="V" ){
                    if($user['collaborateurs']=='NA'){
                        $_SESSION['connected'] = false;
                        $cnn_err= 'Accès non autorisé';
                        $_SESSION['error_message'] = $cnn_err;
                        header('Location: index.php');
                    }else{
                        $_SESSION['connected'] = true;
                        header('Location: evaluateur.php');
                    }
                }elseif($access=="R" ){
                    if($user['type']=='u'){
                        $_SESSION['connected'] = false;
                        $cnn_err= 'Accès non autorisé';
                        $_SESSION['error_message'] = $cnn_err;
                        header('Location: index.php');
                    }else{
                        $_SESSION['connected'] = true;
                        header('Location: rh.php');
                    }
                }
            }

        }else {
            $_SESSION['connected'] = false;
            $cnn_err= 'Mot de passe incorrect';
            $_SESSION['error_message'] = $cnn_err;
            header('Location: index.php');
        }

       

    } else {
        $_SESSION['connected'] = false;
        $cnn_err= 'Login incorrect';
        $_SESSION['error_message'] = $cnn_err;
        header('Location: index.php');
        exit;
        // User authentication failed
    }

    // Close statement
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST['username'];
    $password = $_POST['password'];
    $access = $_POST['type-access'];
    login($user,$password,$access);
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
      <h2>Connexion à votre compte</h2>
      <form class="users-form" action="index.php" method="post">
        <input class="login-input" type="text" name="username" placeholder="Identifiant" required>
        <input class="login-input" type="password" name="password" placeholder="Mot de passe" required>
        <div class="type-access">
        <label for="type-access-evalue">Accès évalué</label>
        <input class="radio-btn" type="radio" id="type-access-evalue" name="type-access" value="E" checked/>
        <label for="type-access-evaluateur">Accès évaluateur</label>
        <input class="radio-btn" type="radio" id="type-access-evaluateur" name="type-access" value="V"/>
        <label for="type-access-evaluateur">Accès RH</label>
        <input class="radio-btn" type="radio" id="type-access-rh" name="type-access" value="R"/>
        </div>
        <button type="submit" class="login-submit">Se connecter</button>
      </form>
      <div class="login-err"><?php echo $cnn_err;?></div>
      <div class="footer">
        GROUPE MFADEL - DOSI
      </div>
    </div>
</div>
</html>
