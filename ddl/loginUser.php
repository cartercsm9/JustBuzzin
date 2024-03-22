<?php
session_start(); // Start a new session or resume the existing one

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    require_once 'db_connect.php';


    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password,display_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $display_name);
        $stmt->fetch();

        // Verify the password against the hash
        if (password_verify($password, $hashed_password)) {
            // Password is correct, so start a new session
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $display_name;

            // Redirect user to home page
            header("location: ../home.php");
        } else {
            // Password is not valid, display an error message
            echo "The password you entered was not valid.";
        }
    } else {
        // Email doesn't exist, display an error message
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
