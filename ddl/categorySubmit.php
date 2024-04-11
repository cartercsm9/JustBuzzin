<?php
session_start();
include './db_connect.php';
if(!isset($_SESSION['loggedin'])){
    header("Location: ../login.php");
}
if (isset($_POST['submitCategory'])) {
    $categoryName = $conn->real_escape_string($_POST['categoryName']);
    $categoryDescription = $conn->real_escape_string($_POST['categoryDescription']);
    $categoryColor = $conn->real_escape_string($_POST['categoryColor']);
    $sql = "INSERT INTO categories (name, description, color) VALUES ('$categoryName', '$categoryDescription', '$categoryColor')";
    if ($conn->query($sql) === TRUE) {
        echo "New category added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

header('Location: ../home.php');
exit();
?>
