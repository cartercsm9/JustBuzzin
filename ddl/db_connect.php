<?php
// Database credentials
$servername = "localhost";
$username = "90445313";
$dbpassword = "90445313";
$dbname = "db_90445313";

// Create database connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

