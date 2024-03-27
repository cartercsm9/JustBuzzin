<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assuming db_connect.php connects to your database without outputting any content
require_once 'db_connect.php';

// Directly serve the image based on the condition
if(isset($_SESSION['username']) && isset($_GET['displayImage'])) {
    $userId = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT profile_pic, profile_pic_type FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($imageData, $imageType);
        $stmt->fetch();
        header("Content-Type: " . $imageType);
        echo $imageData;
    } else {
        // Fallback to the default image if no user image is found
        $defaultImage = '../imgs/userimg.png';
        $imageType = mime_content_type($defaultImage);
        header("Content-Type: " . $imageType);
        readfile($defaultImage);
    }
    exit;
}
?>
