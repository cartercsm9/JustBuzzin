<?php
session_start();
require_once 'db_connect.php'; // Ensure this path accurately points to your database connection script

// Set header for content type to html as default
header('Content-Type: text/html; charset=utf-8');

// Function to show the image tag or output the image
function displayImage($imgSrc = 'imgs/userimg.png', $isBlob = false, $blobType = '') {
    if (!$isBlob) {
        // If not blob, return the image tag
        return "<img src='" . htmlspecialchars($imgSrc) . "' class='profile-image'>";
    } else {
        // If blob, output the correct headers and the image data
        header("Content-Type: " . $blobType);
        echo $imgSrc;
        exit; // Stop script to prevent outputting further HTML
    }
}

// Check if the user is logged in and a specific image display is not requested
if(isset($_SESSION['username']) && !isset($_GET['displayImage'])) {
    $userId = $_SESSION['id'];

    $query = "SELECT profile_pic, profile_pic_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($imageData, $imageType);
        $stmt->fetch();

        // Call function to output the image
        echo displayImage($imageData, true, $imageType);
    } else {
        // Display default image if no profile picture is found
        echo displayImage();
    }
} else {
    // Display default image if not logged in or for direct script access without proper session
    echo displayImage();
}

// Additional HTML content can go here if needed, but will be ignored for blob output
?>
