<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    $profileLink = "profile.php";
} else {
    $profileLink = "login.php";
    $hidePost = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Header</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div id="header-bar">
    <nav>
        <ul class="items">

        <!--   id="buzzin"  -->
            <img src="imgs/logo_alpha.png" id="buzzin">
            <div class="search-bar">
            <input type="text" placeholder="Search Posts">
            <button type="submit" class="search-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg></button>
            </div>
            <button class="level-2-button" id="new-post">New Post</button>
            <a href="<?php echo $profileLink; ?>" style="width:100px; margin:0px;"><img src="imgs/userimg.png" id="userimg"></a>
        </ul>
    </nav>
</div>

<script>
    function hidePost(){
        var button = document.getElementById('new-post');
        button.style.visibility = 'hidden';
    }

    document.getElementById("buzzin").addEventListener("click", function() {
        window.location.href = "home.php";
    });

    document.getElementById("new-post").addEventListener("click", function() {
        window.location.href = "create.php";
    });

    <?php if (isset($hidePost) && $hidePost === true): ?>
        hidePost();
    <?php endif; ?>
</script>
</body>
</html>
