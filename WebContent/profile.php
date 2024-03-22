<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    $username = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Just Buzzin</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <!-- Below will store the user image -->

    <img src="imgs/userimg.png" class="profile-image">
    <p><?php echo $username ?></p>
    <!-- These are for operating the menus / which page your currently on -->
    <div class="profile-button-container">
    <a href="home.php"><button class="level-2-button">Home</button></a>
    <a href="editProf.php"><button class="level-2-button">Edit profile</button></a>
    <!-- Add an id to the button for easy identification -->
    <form id="logoutForm" action="ddl/logout.php" method="POST">
    <button type="submit" id="logoutButton" class="level-2-button">Logout</button>
    </form>
    </div>
    <!-- This is how you will  -->
    <div class = "filter-bar">
        <p>
            <label>Filter by: </label>
            <select name="filter">
                <option>Select an option</option>
                <option>Liked posts</option>
                <option>Commented on posts</option>
                <option>Newest to oldest</option>
                <option>Oldest to newest</option>
            </select>
        </p>
    
        <!-- This is just to show a mock of how the profile page will show your likes/ commented on post  -->
        <div class="post-container">
            <div class="vote-buttons">
                <button>&#8679;</button>
                <span>7</span>
                <button>&#8681;</button>
            </div>
            <div class="post-title">
                <a href="post.php">
                <h1>First buzzin post!</h1>
                </a>
                <div class="post-category">Kelowna</div>
            </div>
            <div class="post-details">
                <span>posted by user</span>
                <span>45 minutes ago...</span>
                <span>9 Comments</span>
            </div>
        </div>
    </div>

</body>
</html>
