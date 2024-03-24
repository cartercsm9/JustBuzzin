<?php

function initializeDatabase($filePath) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    // Connect without specifying the database
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS justBuzzin";
    if ($conn->query($sql) === TRUE) {
        echo "Database created or already exists. ";
    } else {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db("justBuzzin");

    // Read the SQL file for table creation and initial data
    $sql = file_get_contents($filePath);
    if (!$sql) {
        die("Failed to read file: " . $filePath);
    }

    // Execute SQL from the file
    if (mysqli_multi_query($conn, $sql)) {
        echo "Database initialized successfully. ";
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
    } else {
        echo "Error initializing database with SQL file: " . mysqli_error($conn);
    }

    $conn->close();
}

initializeDatabase(__DIR__ . '/ddl/init.sql');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
</head>
<body>
    <p>If you are not redirected, <a href="home.php">click here to continue</a>.</p>

    <script>
        window.location.href = "home.php";
    </script>
</body>
</html>
