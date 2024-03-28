<?php
ob_start();
ini_set('display_errors', 0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php'; 


// Function to serve the default image
function serveDefaultImage() {
    if (ob_get_length()) ob_end_clean();
    $imageData = file_get_contents('../imgs/userimg.png');
    $imageType = 'image/png';
    
    header("Content-Type: " . $imageType);
    echo $imageData;
    exit;
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
        if($row != null){
            $row = $result->fetch_assoc();

            header("Content-Type: " . $row['profile_pic_type']);
            echo $row['profile_pic'];
        }else{
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
