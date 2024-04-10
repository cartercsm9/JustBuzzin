<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    $profileLink = "profile.php";
    $hidePost = false; // User is logged in, don't hide the post button
} else {
    $profileLink = "login.php";
    $hidePost = true; // User is not logged in, hide the post button
}
?>

<div id="header-bar">
    <form action="home.php" method="get">
    <nav>
        <ul class="items">
            <img src="imgs/logo_alpha.png" id="buzzin">
            <div class="search-bar">
            <input type="text" placeholder="Search Posts" name="search">
            <button type="submit" class="search-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg></button>
            </div>
            <?php if (!$hidePost): ?>
                <button type="button" class="level-2-button" id="new-post">New Post</button>
            <?php else: ?>
                <p id="accountText">Login &rarr;</p>
            <?php endif; ?>
            <a href="<?php echo $profileLink; ?>" style="width:100px; margin:0px;"><img src="ddl/displayProfilePic.php?displayImage=true" alt="Profile Picture" class="profile-image"></a>
        </ul>
    </nav>
    </form>
</div>

<script>
    document.getElementById("buzzin").addEventListener("click", function() {
        window.location.href = "home.php";
    });

    var newPostButton = document.getElementById("new-post");
    if (newPostButton) {
        newPostButton.addEventListener("click", function() {
            window.location.href = "create.php";
        });
    }
</script>
