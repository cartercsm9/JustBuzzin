<?php
ob_start();
header('Content-Type: application/json');
session_start();
include './db_connect.php';

if (isset($_POST['post_id'], $_POST['vote'])) {
    $postId = (int)$_POST['post_id'];
    $vote = (int)$_POST['vote'];
    $userId = $_SESSION['id'];

    // Check if the user has already voted on this post
    $checkVoteSql = "SELECT vote FROM post_votes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($checkVoteSql);
    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $existingVote = $result->fetch_assoc()['vote'];
        // If the new vote is the same as the existing vote, set vote to 0 (retract vote)
        // Otherwise, set to the new vote
        $newVote = ($existingVote == $vote) ? 0 : $vote;

        // Update the vote to either 0 (retracted) or the new vote
        $updateVoteSql = "UPDATE post_votes SET vote = ? WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($updateVoteSql);
        $stmt->bind_param("iii", $newVote, $userId, $postId);
    } else {
        // Insert new vote
        $insertVoteSql = "INSERT INTO post_votes (post_id, user_id, vote) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertVoteSql);
        $stmt->bind_param("iii", $postId, $userId, $vote);
    }

    $stmt->execute();

    // Retrieve the new total votes for the post
    $totalVotesSql = "SELECT SUM(vote) AS total_votes FROM post_votes WHERE post_id = ?";
    $stmt = $conn->prepare($totalVotesSql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $newTotalVotes = $row['total_votes'];

    ob_end_clean();
    echo json_encode(['success' => true, 'newTotalVotes' => $newTotalVotes]);
} else {
    ob_end_clean();
    echo json_encode(['success' => false]);
}
?>
