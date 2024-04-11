<?php
require 'ddl/db_connect.php';

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>           
        #adminCharts{
            margin: 0 100px 0 100px;
            background-color: rgba(1,1,1,1,);
            padding: 15px;
            border: thick black;
            border-radius: 20px;
            border: 1px solid rgba(237, 170, 65);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <a href="profile.php"><button class="level-1-button">Back</button></a>
    <a href="home.php"><button class="level-1-button">Home</button></a>

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
                        <a href="?deleteUserId=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="adminCharts">
        <h1>Site Statistics</h1>
        <div>
            <h2>User Registration Over Time</h2>
            <canvas id="userRegistrationChart"></canvas>
        </div>
        <div>
            <h2>User Engagement</h2>
            <canvas id="engagementStatsChart"></canvas>
        </div>
        <div>
            <h2>Most Active Times</h2>
            <canvas id="mostActiveTimesChart"></canvas>
        </div>
        <div>
            <h2>Content Growth</h2>
            <canvas id="contentGrowthChart"></canvas>
        </div>
        <script src="./js/chart.js"></script>
    </div>
</body>
</html>
