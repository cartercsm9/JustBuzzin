<?php
session_start();
include './db_connect.php';
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

?>

<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <title>Redirecting...</title>
    </head>
    <body>
        <script>
            window.location.href = "../home.php";
        </script>
    </body>

</html>