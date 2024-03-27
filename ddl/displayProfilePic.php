<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

if(isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    

    // Use the session variable if set, otherwise use a default image
    $profilePic = isset($_SESSION['profilePic']) ? $_SESSION['profilePic'] : 'imgs/userimg.png';
    
    // Now directly use the $profilePic variable for the image source
    $profilePicHTML = "<img src='" . htmlspecialchars($profilePic) . "' class='profile-image'>";
} else {
    echo "User not specified.";
}
?>
