<?php

require_once 'db_connect.php';


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $firstName = $conn->real_escape_string($_POST['first_name']);
    $lastName = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $passwordTest = $conn->real_escape_string($_POST['passwordTest']);
    $displayName = $conn->real_escape_string($_POST['displayName']);
    $dob = $conn->real_escape_string($_POST['date_of_birth']);    

    if ($password !== $passwordTest) {
        echo "Passwords do not match!";
        exit;
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, display_name, dob) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashedPassword, $displayName, $dob);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

header('Location: ../home.php');
exit();

?>
