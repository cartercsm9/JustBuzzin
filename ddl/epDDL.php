<?php
session_start(); // Start a new session or resume the existing one

if (isset($_SESSION['loggedin'])) {
    $user = $_SESSION['username'];
    $id = $_SESSION['id'];
}

if ($_REQUEST["email"]) {

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Store the username to be changed
        $email = $_POST["email"];
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ? and display_name = ?");
        $stmt->bind_param("sis", $email, $id, $user);
        $stmt->execute();
    }

}else if ($_REQUEST["email"] && $_REQUEST["newpic"]) {

    //Store the username to be changed
    $email = $_POST["email"];
    
    //Set the target directory for the file
    $targetDir = "../uploads/";

    //Check if there is an error in the file
    if($_FILES['file']['error']==0){
        //If not set the filename and target path
        $filename = basename($_FILES["file"]["name"]);
        $targetPath = $targetDir.$fileName;

        //Move the file to the directory in our project
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)){
            //Prepare and execute the query 
            $sql="UPDATE users SET email = '$email' AND filename = '$fileName' AND filepath = '$targetPath' WHERE id = '$id' AND display_name = '$user'";
            if($conn->query($sql) == true){
                echo "You have successfully updated your profile";
            }
            else{
                echo "Error: ".$sql."Error Details: " .$conn->error;
            }
        }
        else{
            echo"Error Moving the file";
        }
    }

}else if ($_REQUEST["email"] && $_REQUEST["displayName"]) {

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Store the new email and username to be updated
        $email = $_POST["email"];
        $newUserN = $_POST["displayName"];
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE users SET email = ? AND display_name = ?WHERE id = ? AND display_name = ?");
        $stmt->bind_param("ssis", $email, $newUserN, $id, $user);
        $stmt->execute();
    }

}else if ($_REQUEST["email"] && $_REQUEST["password"] && $_REQUEST["passwordTest"]) {

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Store the new email and username to be updated
        $email = $_POST["email"];
        $newPass = $_POST["displayName"];
        $newPassT = $_POST["passwordTest"];

        //Check if the new passwords match each other
        if($newPass == $newPassT){
            //Hash the new password and store it. 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("UPDATE users SET email = ? AND password = ? WHERE id = ? AND display_name = ?");
            $stmt->bind_param("ssis", $email, $hashedPassword, $id, $user);
            $stmt->execute(); 
        }else{
            echo "Passwords do not match!";
            exit;
        }

    }

}else if ($_REQUEST["email"] && $_REQUEST["newpic"] && $_REQUEST["password"]  && $_REQUEST["passwordTest"]) {

    //Store the username to be changed
    $email = $_POST["email"];

    //Set the target directory for the file
    $targetDir = "../uploads/";

    $newPass = $_POST["displayName"];
    $newPassT = $_POST["passwordTest"];

    //Check if the new passwords match each other
    if($newPass == $newPassT){
        //Hash the new password and store it. 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if($_FILES['file']['error']==0){
            $filename = basename($_FILES["file"]["name"]);
            $targetPath = $targetDir.$fileName;

            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)){
                $sql="UPDATE users SET email = '$email' AND password = '$hashedPassword' AND filename = '$fileName' AND filepath = '$targetPath' WHERE id = '$id' AND display_name = '$user'";
                if($conn->query($sql) == true){
                    echo "You have successfully updated your profile";
                }
                else{
                    echo "Error: ".$sql."Error Details: " .$conn->error;
                }
            }
            else{
                echo"Error Moving the file";
            }
        }
    }else{
        echo "Passwords do not match!";
        exit;
    }

}else if ($_REQUEST["email"] && $_REQUEST["newpic"] && $_REQUEST["displayName"]) {


    //Store the username to be changed
    $email = $_POST["email"];
    $newUserN = $_POST["displayName"];
    
    //Set the target directory for the file
    $targetDir = "../uploads/";

    if($_FILES['file']['error']==0){
        $filename = basename($_FILES["file"]["name"]);
        $targetPath = $targetDir.$fileName;

        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)){
            $sql="UPDATE users SET email = '$email' AND display_name = '$newUserN' AND filename = '$fileName' AND filepath = '$targetPath' WHERE id = '$id' AND display_name = '$user'";
            if($conn->query($sql) == true){
                echo "You have successfully updated your profile";
            }
            else{
                echo "Error: ".$sql."Error Details: " .$conn->error;
            }
        }
        else{
            echo"Error Moving the file";
        }
    }

}else if ($_REQUEST["email"] && $_REQUEST["password"] && $_REQUEST["displayName"]  && $_REQUEST["passwordTest"] && $_REQUEST["newpic"]) {

    //Store the username to be changed
    $email = $_POST["email"];
    $newUserN = $_POST["displayName"];

    //Set the target directory for the file
    $targetDir = "../uploads/";

    $newPass = $_POST["displayName"];
    $newPassT = $_POST["passwordTest"];

    //Check if the new passwords match each other
    if($newPass == $newPassT){
        //Hash the new password and store it. 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if($_FILES['file']['error']==0){
            $filename = basename($_FILES["file"]["name"]);
            $targetPath = $targetDir.$fileName;

            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)){
                $sql="UPDATE users SET email = '$email' AND password = '$hashedPassword' AND display_name = '$newUserN' AND filename = '$fileName' AND filepath = '$targetPath' WHERE id = '$id' AND display_name = '$user'";
                if($conn->query($sql) == true){
                    echo "You have successfully updated your profile";
                }
                else{
                    echo "Error: ".$sql."Error Details: " .$conn->error;
                }
            }
            else{
                echo"Error Moving the file";
            }
        }
    }else{
        echo "Passwords do not match!";
        exit;
    }
}
?>