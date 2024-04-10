<?php
session_start();

if (isset($_SESSION['loggedin'])) {
    $user = $_SESSION['username'];
}
if(isset($_GET['filter'])){
    $filter = $_GET['filter'];
} else{
    $filter = "Select Option";
}
$userId = $_SESSION['id'];
require_once './ddl/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Just Buzzin</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        #profileUsername{
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <img src="ddl/displayProfilePic.php?displayImage=true" alt="Profile Picture" class="profile-image">
    <div id="profileUsername">
        <p><?php echo $user ?></p>
    </div>

    <div class="profile-button-container">
    <a href="home.php"><button class="level-2-button">Home</button></a>
    <a href="editProf.php"><button class="level-2-button">Edit profile</button></a>
    <?php 
    if($_SESSION['admin'] == 1){
        echo '<a href="admin_manage.php"><button class="level-2-button">Admin</button></a>';
    }
    ?>
    <form id="logoutForm" action="ddl/logout.php" method="POST">
    <button type="submit" id="logoutButton" class="level-2-button">Logout</button>
    </form>
    </div>
    <div class = "filter-bar">
        <p>
            <form action="" method="GET" id="filterForm">
            <label>Filter by: </label>
            <select name="filter" onchange="document.getElementById('filterForm').submit();">
                <option><?php echo $filter?></option>
                <option>My Posts</option>
                <option>Liked posts</option>
                <option>Commented on posts</option>
            </select>
            </form>
        </p>
    </div>
    <?php 
    $sql = "";
    
    switch ($filter) {
        case "My Posts":
            $sql = "SELECT posts.*, 
                        COALESCE(SUM(post_votes.vote), 0) AS total_upvotes, 
                        categories.color AS category_color, 
                        categories.name AS category_name, 
                        users.display_name
                    FROM posts
                    LEFT JOIN post_votes ON posts.id = post_votes.post_id
                    JOIN users ON posts.user_id = users.id
                    LEFT JOIN categories ON posts.category_id = categories.id
                    WHERE posts.user_id = ?
                    GROUP BY posts.id, categories.color, categories.name, users.display_name";
            break;

        case "Liked posts":
            $sql = "SELECT posts.*, 
                        COALESCE(SUM(post_votes.vote), 0) AS total_upvotes, 
                        categories.color AS category_color, 
                        categories.name AS category_name, 
                        users.display_name
                    FROM posts
                    INNER JOIN post_votes ON posts.id = post_votes.post_id AND post_votes.vote = 1
                    JOIN users ON posts.user_id = users.id
                    LEFT JOIN categories ON posts.category_id = categories.id
                    WHERE post_votes.user_id = ?
                    GROUP BY posts.id, categories.color, categories.name, users.display_name";
            break;
    
        case "Commented on posts":
            $sql = "SELECT DISTINCT posts.*, 
                        (SELECT COALESCE(SUM(vote), 0) FROM post_votes WHERE post_votes.post_id = posts.id) AS total_upvotes, 
                        categories.color AS category_color, 
                        categories.name AS category_name, 
                        users.display_name
                    FROM posts
                    INNER JOIN comments ON posts.id = comments.post_id
                    JOIN users ON posts.user_id = users.id
                    LEFT JOIN categories ON posts.category_id = categories.id
                    WHERE comments.user_id = ?";
            break;
    
        default:
            $sql = "SELECT posts.*, 
                    COALESCE(SUM(post_votes.vote), 0) AS total_upvotes, 
                    categories.color AS category_color, 
                    categories.name AS category_name, 
                    users.display_name
                FROM posts
                LEFT JOIN post_votes ON posts.id = post_votes.post_id
                JOIN users ON posts.user_id = users.id
                LEFT JOIN categories ON posts.category_id = categories.id
                WHERE posts.user_id = ?
                GROUP BY posts.id, categories.color, categories.name, users.display_name";
            break;
    }
    
    $stmt = $conn->prepare($sql);
    if ($filter !== "Select an option") {
        $stmt->bind_param("i", $userId);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="post-container">
                    <div class="vote-buttons">
                        <button class="upvote-button" data-post-id="' . $row["id"] . '">&#8679;</button>
                        <span id="votes-count-' . $row["id"] . '">' . $row["total_upvotes"] . '</span>
                        <button class="downvote-button" data-post-id="' . $row["id"] . '">&#8681;</button>
                    </div>
                    <div class="post-title">
                        <a href="post.php?id=' . $row["id"] . '">
                            <h1>' . htmlspecialchars($row["title"]) . '</h1>
                        </a>
                        <div class="post-category" style="background-color: ' . htmlspecialchars($row['category_color']) . ';">' . htmlspecialchars($row['category_name']) . '</div>
                    </div>
                    <div class="post-details">
                        <span>posted by ' . htmlspecialchars($row["display_name"]) . '</span>
                        <span>' . $row["creation_date"] . '</span>
                    </div>
                </div>';
        }
    } else {
        echo "0 results";
    }
    ?>


</body>
</html>
