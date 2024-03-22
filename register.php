<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Just Buzzin</title>
        <link rel="stylesheet" href="css/style-sheet.css" />
        <script src="./js/headerfootermanager.js"></script>
    </head>

    <body class="register">

    <div class="register-page-container">
        <p>
        <a href="home.php"><button class="level-2-button">Home</button></a>
        
        <a href="login.php"><button class="level-2-button">Log in</button></a>
        </p>
        <img src="imgs/logo_alpha.png" height="200em" width="200em">

        <form method = "POST" action="./ddl/addUser.php" class="form">
        <fieldset class="form">
            <legend>REGISTER</legend>
            <p>
                <input type="text" name="first_name" placeholder="First Name"/>
            </p>
            <p>
                <input type="text" name="last_name" placeholder="Last Name"/>
            </p>
            <p>
                <input type="text" name="email" placeholder="Email"/>
            </p>
            <p>
                <input type="password" name="password" placeholder="Password"/>
            </p>
            <p>
                <input type="password" name="passwordTest" placeholder="Re-enter Password"/>
            </p>
            <p>
                <input type="text" name="displayName" placeholder="Username"/>
            </p>
            <p>
                <input type="date" name="date_of_birth" placeholder="Date Of Birth"/>
            </p>
            <p>
                <input type="submit" class="level-1-button" />
                <input type="reset" class="level-1-button"/>
            </p>
        </fieldset>

        </form>
    </div>
    </body>
</html>