<?php
$_SESSION['connected'] = false;
$_SESSION['user'] = "";
session_start();
session_unset();
session_destroy();

// Redirect to the login page
header('Location: index.php');
exit;
