<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$profilePic = 'imgs/userimg.png'; // Default image
$user = ''; // Default to no user

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // User is logged in, proceed with your original logic
    $user = $_SESSION['username'];
    // Use the session variable for profilePic if set, otherwise use a default image
    $profilePic = isset($_SESSION['profilePic']) ? $_SESSION['profilePic'] : 'imgs/userimg.png';
}

// Use the determined $profilePic for the image source
$profilePicHTML = "<img src='" . htmlspecialchars($profilePic) . "' class='profile-image'>";
?>
