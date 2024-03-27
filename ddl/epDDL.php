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

$imageData = null;
$imageType = null;
$errorMessages = [];

if (isset($_FILES['newpic']) && $_FILES['newpic']['error'] === UPLOAD_ERR_OK) {
    // Read the file's binary data
    $tmpName = $_FILES['newpic']['tmp_name'];
    $imageType = $_FILES['newpic']['type'];
    $imageData = file_get_contents($tmpName);

    if ($imageData === false) {
        $errorMessages[] = "Error reading the file.";
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

if ($imageData !== null) {
    $imageQuery = "UPDATE users SET profile_pic = ?, profile_pic_type = ? WHERE id = ?";
    $imageStmt = $conn->prepare($imageQuery);
    // The $null variable is a workaround since the blob data is sent via send_long_data()
    $null = NULL;
    $imageStmt->bind_param('bss', $null, $imageType, $id);

    // Open the file, read the contents into $imageData, and then send it using send_long_data()
    if ($fp = fopen($tmpName, 'rb')) {
        while (!feof($fp)) {
            $chunk = fread($fp, 8192);
            $imageStmt->send_long_data(0, $chunk);
        }
        fclose($fp);
    } else {
        echo "Failed to open file.";
    }

    // Execute the prepared statement
    if (!$imageStmt->execute()) {
        // Handle execution error
        echo "Execute failed: (" . $imageStmt->errno . ") " . $imageStmt->error;
    } else {
        // Success
        echo "Profile updated successfully.";
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

$query = rtrim($query, ", ") . " WHERE id = ?";
$params[] = $id;
$types .= "i";

if (empty($errorMessages)) {
    try {
        $stmt = $conn->prepare($query);
        // Dynamically bind parameters
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        // Update session variables after successful update
        if ($emailToUpdate) {
            $_SESSION['email'] = $emailToUpdate;
        }
        if ($newDisplayName) {
            $_SESSION['username'] = $newDisplayName;
        }

        header('Location: ../home.php?message=Profile+updated+successfully');
        exit;
    } catch (mysqli_sql_exception $e) {
        echo 'Failed to update profile: ' . $e->getMessage();
    }
} else {
    foreach ($errorMessages as $message) {
        echo "<p>Error: $message</p>";
    }
}
?>
