<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php'; // Ensure database connection

// Function to serve the image content directly
function serveImage($imageData, $imageType) {
    header("Content-Type: " . $imageType);
    echo $imageData;
    exit;
}

// Function to serve the default image
function serveDefaultImage() {
    $defaultImagePath = '../imgs/userimg.png'; // Update this path
    $imageData = file_get_contents($defaultImagePath);
    $imageType = mime_content_type($defaultImagePath);
    serveImage($imageData, $imageType);
}

// Check if user is logged in and a valid ID exists
if(isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    // Prepare the query to fetch the user's profile picture
    $stmt = $conn->prepare("SELECT profile_pic, profile_pic_type FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the user has a custom profile picture set
        if(!empty($row['profile_pic']) && !empty($row['profile_pic_type'])) {
            // Serve the user's profile picture
            serveImage($row['profile_pic'], $row['profile_pic_type']);
        } else {
            // Serve the default image
            serveDefaultImage();
        }
    } else {
        // No user found, or user has no profile picture, serve the default image
        serveDefaultImage();
    }
} else {
    // User not logged in, serve the default image
    serveDefaultImage();
}
?>
