<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/errors/custom_error_log.txt');


session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../home.php');
    exit;
}

require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn->begin_transaction();

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

    if ($emailToUpdate) {
        $query .= "email = ?, ";
        $params[] = $emailToUpdate;
        $types .= "s";
    }

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

    if ($newPassword && $newPassword === $newPasswordTest) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query .= "password = ?, ";
        $params[] = $hashedPassword;
        $types .= "s";
    } elseif ($newPassword && $newPassword !== $newPasswordTest) {
        throw new Exception("Passwords do not match!");
    }

    if ($newDisplayName) {
        $query .= "display_name = ?, ";
        $params[] = $newDisplayName;
        $types .= "s";
    }

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
    error_log($e->getMessage(), 3, "/error/error_log.txt");

} catch (Exception $e) {
    $conn->rollback();
    error_log($e->getMessage(), 3, "/error/error_log.txt");
}
?>
