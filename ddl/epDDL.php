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

// Initial setup
$user = $_SESSION['username'];
$emailToUpdate = $_POST['email'] ?? null;
$newDisplayName = $_POST['displayName'] ?? null;
$newPassword = $_POST['password'] ?? null;
$newPasswordTest = $_POST['passwordTest'] ?? null;
$errorMessages = [];

// Start database transaction
$conn->begin_transaction();

try {
    // Update profile picture if a new one is uploaded
    if (isset($_FILES['newpic']) && $_FILES['newpic']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['newpic']['tmp_name'];
        $imageType = $_FILES['newpic']['type'];
        $imageData = file_get_contents($tmpName);

        if ($imageData === false) {
            throw new Exception("Error reading the file.");
        }

        $imageQuery = "UPDATE users SET profile_pic = ?, profile_pic_type = ? WHERE display_name = ?";
        $imageStmt = $conn->prepare($imageQuery);
        $null = NULL; // Placeholder for the blob
        $imageStmt->bind_param('bss', $null, $imageType, $user);
        $imageStmt->send_long_data(0, $imageData);

        if (!$imageStmt->execute()) {
            throw new Exception("Failed to update profile picture: " . $imageStmt->error);
        }
    }

    // Prepare the base query for other user info updates
    $query = "UPDATE users SET ";
    $params = [];
    $types = "";

    // Conditional updates for user info
    if ($emailToUpdate) {
        $query .= "email = ?, ";
        $params[] = $emailToUpdate;
        $types .= "s";
    }

    if ($newDisplayName) {
        $query .= "display_name = ?, ";
        $params[] = $newDisplayName;
        $types .= "s";
    }

    if ($newPassword && $newPassword === $newPasswordTest) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query .= "password = ?, ";
        $params[] = $hashedPassword;
        $types .= "s";
    } elseif ($newPassword && $newPassword !== $newPasswordTest) {
        throw new Exception("Passwords do not match!");
    }

    // Finalize and execute the query for user info updates if there are changes
    if (!empty($types)) {
        $query = rtrim($query, ", ") . " WHERE display_name = ?";
        $params[] = $user;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user info: " . $stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['feedback'] = 'Profile updated successfully';
    header('Location: ../home.php?message=' . urlencode($_SESSION['feedback']));
    exit;
} catch (Exception $e) {
    $conn->rollback();
    // Log error or handle exception
    $_SESSION['feedback'] = $e->getMessage();
    header('Location: ../home.php?error=' . urlencode($_SESSION['feedback']));
    exit;
}
?>
