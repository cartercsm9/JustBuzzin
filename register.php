<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Just Buzzin</title>
        <link rel="stylesheet" href="css/style-sheet.css" />
    </head>

    <body class="register">

    <div class="register-page-container">
        <div class="register-form-button-top">
        <a href="home.php"><button class="level-2-button">Home</button></a>
        <a href="login.php"><button class="level-2-button">Log in</button></a>
        </div>
        <div class="register-form-image">
        <img src="imgs/logo_alpha.png">
        </div>
        <form method = "POST" action="./ddl/addUser.php" class="form-register">
        <fieldset class="form">
            <div class="register-form-header">REGISTER</div>
            <div class="register-form-input">
                <input type="text" name="first_name" placeholder="First Name" required/>
            </div>
            <div class="register-form-input">
                <input type="text" name="last_name" placeholder="Last Name" required/>
            </div>
            <div class="register-form-input">
                <input type="email" name="email" placeholder="Email" required/>
            </div>
            <div class="register-form-input">
                <input type="password" name="password" placeholder="Password" required/>
            </div>
            <div class="register-form-input">
                <input type="password" name="passwordTest" placeholder="Re-enter Password" required/>
            </div>
            <div class="register-form-input">
                <input type="text" name="displayName" placeholder="Username" required />
            </div>
            <div class="register-form-input">
                <input type="date" name="date_of_birth" placeholder="Date Of Birth" required/>
            </div>
            <!--I should possibly place this button arrangement in its own div.-->
            <div class="register-form-button-bottom">
                <input type="reset" class="level-1-button"/>
                <input type="submit" class="level-1-button" />
            </div>
        </fieldset>

        </form>
    </div>

    </body>
</html>