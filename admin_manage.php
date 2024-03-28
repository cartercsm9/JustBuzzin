<?php
require 'ddl/db_connect.php'; // Adjust this path as necessary

// Check if a request to delete a user has been made
if (isset($_GET['deleteUserId'])) {
    $deleteUserId = $_GET['deleteUserId'];
    // Assuming you have proper authorization checks here
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $deleteUserId);
    if ($stmt->execute()) {
        echo "<script>alert('User removed successfully');</script>";
    } else {
        echo "<script>alert('Error removing user');</script>";
    }
    // Redirect to prevent resubmission
    header("Location: admin.php");
    exit;
}

// Query to fetch users and their post count
$query = "SELECT users.id, users.display_name, users.email, COUNT(posts.id) AS post_count 
          FROM users LEFT JOIN posts ON users.id = posts.user_id 
          GROUP BY users.id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Users and Posts</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Display Name</th>
            <th>Email</th>
            <th>Post Count</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['display_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['post_count'] ?></td>
                <td>
                    <!-- Add a query parameter for deletion with the user's ID -->
                    <a href="?deleteUserId=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">Remove</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="home.php"><button class="level-1-button">Home</button></a>
<br><br>
<a href="profile.php"><button class="level-1-button">Back</button></a>

</body>
</html>
