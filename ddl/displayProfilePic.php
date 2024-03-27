<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the username is not set in the session
if(!isset($_SESSION['username'])) {
    // Use a default image and exit the script
    $profilePic = 'imgs/userimg.png';
    $profilePicHTML = "<img src='" . htmlspecialchars($profilePic) . "' class='profile-image'>";
    echo $profilePicHTML;
    exit(); // Stop script execution
}

require_once 'db_connect.php';

// The user is logged in, proceed with your original logic
$user = $_SESSION['username'];

// Use the session variable if set, otherwise use a default image
$profilePic = isset($_SESSION['profilePic']) ? $_SESSION['profilePic'] : 'imgs/userimg.png';

// Now directly use the $profilePic variable for the image source
$profilePicHTML = "<img src='" . htmlspecialchars($profilePic) . "' class='profile-image'>";
?>
