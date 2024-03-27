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

$fileName = null;
$targetPath = null;
$errorMessages = [];

if (isset($_FILES['newpic']) && $_FILES['newpic']['error'] === UPLOAD_ERR_OK) {
    $originalFileName = basename($_FILES['newpic']['name']);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueSuffix = time() . '_' . rand(1000, 9999);
    $fileName = "user_profile_" . $uniqueSuffix . "." . $fileExtension;
    $targetDir = "../uploads/";
    $targetPath = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES['newpic']['tmp_name'], $targetPath)) {
        $errorMessages[] = "Error moving the file.";
        $fileName = null;
    }
}

$query = "UPDATE users SET ";
$params = [];
$types = "";

if ($emailToUpdate) {
    $query .= "email = ?, ";
    $params[] = $emailToUpdate;
    $types .= "s";
}

if ($fileName) {
    $query .= "filename = ?";
    $params[] = $fileName;
    $types .= "s";
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
        $query = rtrim($query, ", ") . " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        // Update session variables after successful update
        if ($emailToUpdate) {
            $_SESSION['email'] = $emailToUpdate;
        }
        if ($newDisplayName) {
            $_SESSION['username'] = $newDisplayName;
        }
        if ($fileName) {
            $_SESSION['profilePic'] = './uploads/' . $fileName;
        }

        $conn->commit();
        header('Location: ../home.php?message=Profile+updated+successfully');
        exit;
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo 'Failed to update profile: ' . $e->getMessage();
    }
} else {
    foreach ($errorMessages as $message) {
        echo "<p>Error: $message</p>";
    }
}
?>
