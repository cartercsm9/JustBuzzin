<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Just Buzzin</title>
        <link rel="stylesheet" href="css/style-sheet.css" />
    </head>
    <style>
        input::placeholder {
        color: grey;
        }
    </style>

    <body>
        <div class="login-page-container">
        <a href="home.php"><button class="level-2-button">Back</button></a>
        <img src="imgs/logo_alpha.png" height="200em" width="200em">
        <!-- This will be used to log in -->
        <form method = "POST" action="./ddl/loginUser.php">
            <fieldset class="form">
                <legend>LOG IN</legend>
                <p>
                    <label>Email: </label>
                    <input type="text" name="email"  placeholder="Email" required/>
                </p>
                <p>
                    <label>Password: </label>
                    <input type="password" name="password" placeholder="Password" required/>
                </p>
                
                <p>
                    <input type="submit" class="level-1-button"/>
                    <input type="reset" class="level-1-button"/>
                </p>
            </fieldset>
        </form>
        <a href="register.php"> Dont have an account? Click here to make one!</a>
</div>
    </body>
</html>