<?php
include 'ddl/db_connect.php'; 

if (isset($_GET['id'])) {
    $postId = (int) $_GET['id'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $userId = $_SESSION['id']; 
    $commentContent = trim($_POST['comment']);
    $postId = isset($_POST['postId']) ? (int)$_POST['postId'] : 0; 

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $postId, $userId, $commentContent);
    
    // Execute
    if ($stmt->execute()) {
        echo "Comment added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    // Redirect back to the same page to avoid form resubmission issues
    header("Location: post.php?id=" . $postId);
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
    <?php include 'header.php'; ?>
    <?php include 'ddl/db_connect.php';  ?>
</head>
<body>

<?php
// Check if the 'id' GET parameter is set
if (isset($_GET['id'])) {
    $postId = (int) $_GET['id'];

    // Query to select the post
    $postQuery = "SELECT posts.*, users.display_name, categories.name AS category_name, categories.color AS category_color, 
              COALESCE(SUM(post_votes.vote), 0) AS total_votes
              FROM posts
              JOIN users ON posts.user_id = users.id
              LEFT JOIN categories ON posts.category_id = categories.id
              LEFT JOIN post_votes ON posts.id = post_votes.post_id
              WHERE posts.id = ?
              GROUP BY posts.id, users.display_name, categories.name, categories.color";
    $stmt = $conn->prepare($postQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $postResult = $stmt->get_result();

    if ($postResult->num_rows > 0) {
        $post = $postResult->fetch_assoc();
        ?>
        
        <div class="post">
            <div style="display: flex;">
                <div class="vote-buttons">
                    <button class="upvote-button" data-post-id="<?php echo $post['id']; ?>">&#8679;</button>
                    <span id="votes-count-<?php echo $post['id']; ?>"><?php echo $post['total_votes']; ?></span>
                    <button class="downvote-button" data-post-id="<?php echo $post['id']; ?>">&#8681;</button>
                </div>
                <div class="post-title">
                    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="post-category" style="background-color: <?php echo htmlspecialchars($post['category_color']); ?>;">
                        <?php echo htmlspecialchars($post['category_name']); ?>
                    </div>
                </div>
            </div>
            <p class="post-text">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </p>
        </div>


        <div class="comments">
        <form action="" method="POST">
            <textarea id="comment-box" name="comment" rows="4" cols="50" placeholder="Leave a Comment"></textarea>
            <input type="hidden" name="postId" value="<?php echo $postId; ?>">
            <button type="submit" name="submit_comment">Post Comment</button>
        </form>


            <?php
            // Query to select comments for this post
            $commentQuery = "SELECT comments.*, users.display_name FROM comments
                             JOIN users ON comments.user_id = users.id
                             WHERE comments.post_id = ? ORDER BY comments.creation_date ASC";
            $stmt = $conn->prepare($commentQuery);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $commentResult = $stmt->get_result();

            if ($commentResult->num_rows > 0) {
                while ($comment = $commentResult->fetch_assoc()) {
                    ?>
                    <div id="comment">
                        <br>
                        <span><?php echo htmlspecialchars($comment['display_name']); ?>:</span>
                        <p>
                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                        </p>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No comments yet.</p>";
            }
            ?>

        </div>
        <?php
    } else {
        echo "<p>Post not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid post ID.</p>";
}

$conn->close();
?>


<script src="./js/votelogic.js"></script>

</body>
</html>
