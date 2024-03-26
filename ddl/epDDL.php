<?php
session_start(); // Start a new session or resume the existing one

if (!isset($_SESSION['loggedin'])) {
    // Redirect to login page or handle not logged in
    header('Location: ../home.php');
    exit("Not logged in");
}

require_once './ddl/db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable exceptions for mysqli

try {
    $conn->begin_transaction(); // Start transaction if you're making multiple updates

    $user = $_SESSION['username'];
    $id = $_SESSION['id'];

    // Initialize variables to hold potential updates
    $emailToUpdate = $_POST['email'] ?? null;
    $newDisplayName = $_POST['displayName'] ?? null;
    $newPassword = $_POST['password'] ?? null;
    $newPasswordTest = $_POST['passwordTest'] ?? null;
    $fileError = $_FILES['file']['error'] ?? null;
    $fileName = basename($_FILES["file"]["name"]) ?? null;
    $targetDir = "../uploads/";
    $targetPath = $targetDir . $fileName;

    // Start building the SQL query dynamically
    $query = "UPDATE users SET ";
    $params = [];
    $types = "";

    // Check and append email to the query if provided
    if ($emailToUpdate) {
        $query .= "email = ?, ";
        $params[] = $emailToUpdate;
        $types .= "s";
    }

    // Handle file upload
    if ($fileError === 0 && $fileName) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
            $query .= "filename = ?, filepath = ?, ";
            $params[] = $fileName;
            $params[] = $targetPath;
            $types .= "ss";
        } else {
            throw new Exception("Error moving the file.");
        }
    }

    // Handle password update
    if ($newPassword && $newPassword === $newPasswordTest) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query .= "password = ?, ";
        $params[] = $hashedPassword;
        $types .= "s";
    } elseif ($newPassword && $newPassword !== $newPasswordTest) {
        throw new Exception("Passwords do not match!");
    }

    // Append display name to the query if provided
    if ($newDisplayName) {
        $query .= "display_name = ?, ";
        $params[] = $newDisplayName;
        $types .= "s";
    }

    // Finalize the query
    $query = rtrim($query, ", ") . " WHERE id = ? AND username = ?";
    $params[] = $id;
    $params[] = $user;
    $types .= "is";

    // Prepare, bind, and execute the SQL statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $conn->commit(); // Commit transaction

    // If execution is successful, redirect to home with a success message
    header('Location: ../home.php?message=Profile+updated+successfully');
    exit;
} catch (mysqli_sql_exception $e) {
    // Rollback transaction in case of error
    $conn->rollback
