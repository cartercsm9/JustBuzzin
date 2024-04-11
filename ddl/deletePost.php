<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_post']) && isset($_POST['postId'])) {
    $postId = (int)$_POST['postId'];
    
    if (isset($_SESSION['id']) && $_SESSION['loggedin'] == true) {
        $userId = $_SESSION['id'];

        // Check if the logged-in user is the author of the post
        $checkQuery = "SELECT user_id FROM posts WHERE id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['user_id'] == $userId) {
                // User verification passed, delete post
                $deleteQuery = "DELETE FROM posts WHERE id = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param("i", $postId);
                if ($deleteStmt->execute()) {
                    echo "Post deleted successfully.";
                } else {
                    echo "Error deleting post.";
                }
                $deleteStmt->close();
            } else {
                echo "You do not have permission to delete this post.";
            }
        } else {
            echo "Post not found.";
        }
        $stmt->close();
    } else {
        echo "You need to be logged in to delete a post.";
    }
    $conn->close();
}
header("Location: ../home.php");
?>