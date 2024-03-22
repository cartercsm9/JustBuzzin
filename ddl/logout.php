<?php
session_start();
$_SESSION['loggedin'] = false;
// Destroy the session
session_destroy();
// Redirect to home.php or any other desired page
header("Location: ../home.php");
exit;

