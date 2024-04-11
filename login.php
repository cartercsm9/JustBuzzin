<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Just Buzzin</title>
        <link rel="stylesheet" href="css/style-sheet.css" />
        <script src="./js/headerfootermanager.js"></script>

    </head>

    <body>
        <div class="login-page-container">
            <div class="login-form-button-top">
                <a href="home.php"><button class="level-2-button">Back</button></a>
            </div>
            <div class="login-form-image">
                <img src="imgs/logo_alpha.png">
            </div>
        <!-- The same styling was used for the registration pages. -->
        <form name="login" method = "POST" action="./ddl/loginUser.php" class="form-login">
            <fieldset class="form">
                <div class="login-form-header">LOGIN</div>
                <div class="login-form-input">
                    <input type="text" name="email" placeholder="Enter email" required/>
                </div>
                <div class="login-form-input">
                    <input type="password" name="password" placeholder="Enter password" required/>
                </div>
                
                <div class="login-form-button-bottom">
                    <input type="reset" class="level-1-button"/>
                    <input type="submit" class="level-1-button"/>
                </div>
            </fieldset>
        </form>
        <div class="login-form-bottom-text">
            <a href="register.php"> Don't have a Buzzin Account?<br> Click here to make one!</a>
        </div>    
    </div>
    </body>
</html>