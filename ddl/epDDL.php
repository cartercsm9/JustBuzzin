<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../home.php');
    exit;
}

require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$user = $_SESSION['username'];
$id = $_SESSION['id'];
$emailToUpdate = $_POST['email'] ?? null;
$newDisplayName = $_POST['displayName'] ?? null;
$newPassword = $_POST['password'] ?? null;
$newPasswordTest = $_POST['passwordTest'] ?? null;
$fileError = $_FILES['file']['error'] ?? null;
$fileName = basename($_FILES["file"]["name"]) ?? null;
$targetDir = "../uploads/";
$targetPath = $targetDir . $fileName;
$query = "UPDATE users SET ";
$params = [];
$types = "";
$errorMessages = [];

if ($emailToUpdate) {
    $query .= "email = ?, ";
    $params[] = $emailToUpdate;
    $types .= "s";
}

if ($fileError === 0 && $fileName) {
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
        $errorMessages[] = "Error moving the file.";
    } else {
        $query .= "filename = ?, filepath = ?, ";
        $params[] = $fileName;
        $params[] = $targetPath;
        $types .= "ss";
    }
}

if ($newPassword && $newPassword === $newPasswordTest) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $query .= "password = ?, ";
    $params[] = $hashedPassword;
    $types .= "s";
} elseif ($newPassword && $newPassword !== $newPasswordTest) {
    $errorMessages[] = "Passwords do not match!";
}

if ($newDisplayName) {
    $query .= "display_name = ?, ";
    $params[] = $newDisplayName;
    $types .= "s";
}

if (empty($errorMessages)) {
    try {
        $conn->begin_transaction();
        $query = rtrim($query, ", ") . " WHERE id = ? AND username = ?";
        $params[] = $id;
        $params[] = $user;
        $types .= "is";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $conn->commit();
        header('Location: ../home.php?message=Profile+updated+successfully');
        exit;
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $errorMessages[] = 'Failed to update profile: ' . $e->getMessage();
    }
}

// If there are errors, display them on the page
foreach ($errorMessages as $message) {
    echo "<p>Error: $message</p>";
}
?>
