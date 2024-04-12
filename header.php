<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    $profileLink = "profile.php";
} else {
    $profileLink = "login.php";
    $hidePost = true;
}
?>


<div class="header-bar">
    <div class="header-bar-logo">
        <img src="imgs/logo_alpha.png">
    </div>
    <div class="search-bar">
        <form action="home.php" method="get" class="search-form">
            <input type="text" name="search" class="search-form-input">
            <button type="submit" class="search-bar-button"></button>
        </form>
    </div>
    <button type="button" class="level-2-button-header" id="new-post">New Post</button>
    <div class="header-bar-user-icon"><a href="<?php echo $profileLink; ?>" style="width:100px; margin:0px;"><img src="ddl/displayProfilePic.php?displayImage=true" alt="Profile Picture"></a></div>

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

