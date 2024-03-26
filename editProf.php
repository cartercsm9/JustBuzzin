<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Just Buzzin</title>
        <link rel="stylesheet" href="css/style-sheet.css" />
        <script src="./js/headerfootermanager.js"></script>
    </head>

    <body>
        <div class="login-page-container">
        <form method = "POST" action="./ddl/epDDL.php" >
            <fieldset class="form">
                <legend>Edit My Account</legend>
                <p>
                    <label><img src="imgs/userimg.png" class="profile-image"></label>
                    <input type="file" name="newpic" class="level-1-button"/>
                    <p>Choose your new profile picture above</p>
                </p>
                <p>
                    <label>Edit email: </label>
                    <input type="email" name="email" placeholder="Enter the current or new email for your account"/>
                </p>
                <p>
                    <label>Change password: </label>
                    <input type="password" name="password" placeholder="Enter the new password you wish to use on your account" />
                </p>
                <p>
                    <label>Confirm password: </label>
                    <input type="password" name="passwordTest" placeholder="Re-enter the new password you wish to use" />
                </p>
                <p>
                    <label>Change display name: </label>
                    <input type="text" name="displayName" placeholder="Enter the new display name you wish to use for your account"/>
                </p>
                <p>
                    <input type="reset" class="level-1-button"/>
                    <input type="submit" class="level-1-button"/>
                </p>
            </fieldset>
        </form>
        </div>
        <a href="home.php"><button class="level-1-button">Home</button></a>
        <a href="profile.php"><button class="level-2-button">Back</button></a>

    </body>
</html>