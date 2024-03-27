<?php
echo "Checkpoint 1: Before database operations<br>";
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

// Initialize variables for file upload
$fileName = null;
$targetPath = null;

if (isset($_FILES['newpic']) && $_FILES['newpic']['error'] === UPLOAD_ERR_OK) {
    // Original file name
    $originalFileName = basename($_FILES['newpic']['name']);
    // Extract the file extension
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    
    // Generate a unique file name to avoid overwriting existing files
    // Example: user_profile_1234567890.jpg where 1234567890 is a timestamp
    $uniqueSuffix = time() . '_' . rand(1000, 9999); // You can use the user's ID or any unique identifier
    $fileName = "user_profile_" . $uniqueSuffix . "." . $fileExtension;
    
    $targetDir = "../uploads/";
    $targetPath = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES['newpic']['tmp_name'], $targetPath)) {
        // Handle failure to move the file
        $errorMessages[] = "Error moving the file.";
        $fileName = null; // Reset fileName since the move failed
    }
} else if (isset($_FILES['newpic'])) {
    // Handle other errors related to file upload
    $fileError = $_FILES['newpic']['error'];
    // Specific error handling based on the value of $fileError
}

$query = "UPDATE users SET ";
$params = [];
$types = "";
$errorMessages = [];

if ($emailToUpdate) {
    $query .= "email = ?, ";
    $params[] = $emailToUpdate;
    $types .= "s";
}

if ($fileName && $targetPath) {
    $query .= "filename = ?, filepath = ?, ";
    $params[] = $fileName;
    $params[] = $targetPath;
    $types .= "ss";
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
        echo "Executing SQL: " . $stmt . "<br>";

        if (!$stmt->execute()) {
            echo "Error executing SQL: " . $stmt->error;
        }

        $conn->commit();
        header('Location: ../home.php?message=Profile+updated+successfully');
        exit;
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        // If an exception is caught, it means there was a database or a transaction issue
        $errorMessages[] = 'Failed to update profile: ' . $e->getMessage();
    }
} else {
    // If there are errors, display them on the page
    foreach ($errorMessages as $message) {
        echo "<p>Error: $message</p>";
    }
}
?>
