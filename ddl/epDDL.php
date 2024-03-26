<?php
session_start(); // Start a new session or resume the existing one

if (!isset($_SESSION['loggedin'])) {
    // Redirect to login page or handle not logged in
    exit("Not logged in");
}

require_once './ddl/db_connect.php';

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
if ($fileError === 0) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
        $query .= "filename = ?, filepath = ?, ";
        $params[] = $fileName;
        $params[] = $targetPath;
        $types .= "ss";
    } else {
        exit("Error moving the file.");
    }
}

// Handle password update
if ($newPassword && $newPassword === $newPasswordTest) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $query .= "password = ?, ";
    $params[] = $hashedPassword;
    $types .= "s";
} elseif ($newPassword !== $newPasswordTest) {
    exit("Passwords do not match!");
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
if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
} else {
    echo "You have successfully updated your profile";
}
?>
