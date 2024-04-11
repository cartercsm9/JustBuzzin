<?php
include './db_connect.php'; 

function executeQuery($conn, $query) {
    $result = $conn->query($query);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// User Registration Over Time
$registrationQuery = "
SELECT DATE(registration_date) AS date, 
       COUNT(id) AS users_count,
       SUM(COUNT(id)) OVER (ORDER BY DATE(registration_date) ASC) AS cumulative_users_count
FROM users
GROUP BY DATE(registration_date)
ORDER BY date ASC;
";
$userRegistration = executeQuery($conn, $registrationQuery);


// user engagement
$engagementQuery = "
SELECT AVG(post_counts.posts_count) AS avg_posts_per_user, 
       AVG(comment_counts.comments_count) AS avg_comments_per_user
FROM (
    SELECT u.id AS user_id, COUNT(p.id) AS posts_count
    FROM users u
    LEFT JOIN posts p ON u.id = p.user_id
    GROUP BY u.id
) AS post_counts
INNER JOIN (
    SELECT u.id AS user_id, COUNT(c.id) AS comments_count
    FROM users u
    LEFT JOIN comments c ON u.id = c.user_id
    GROUP BY u.id
) AS comment_counts
ON post_counts.user_id = comment_counts.user_id;
";
$engagementStats = executeQuery($conn, $engagementQuery);


//activity times
$activeTimesQuery = "
SELECT HOUR(creation_date) AS hour_of_day, 
       COUNT(*) AS activity_count
FROM (
    SELECT creation_date FROM posts
    UNION ALL
    SELECT creation_date FROM comments
) AS combined
GROUP BY HOUR(creation_date)
ORDER BY activity_count DESC
LIMIT 10;
";
$mostActiveTimes = executeQuery($conn, $activeTimesQuery);


// content added over time
$contentGrowthQuery = "
SELECT DATE(p.creation_date) AS date, 
       COUNT(DISTINCT p.id) AS posts_count, 
       COUNT(DISTINCT c.id) AS comments_count
FROM posts p
LEFT JOIN comments c ON p.id = c.post_id
GROUP BY DATE(creation_date)
ORDER BY date ASC;
";
$contentGrowth = executeQuery($conn, $contentGrowthQuery);

// Combine all data into one array
$statsData = [
    'userRegistration' => $userRegistration,
    'engagementStats' => $engagementStats,
    'mostActiveTimes' => $mostActiveTimes,
    'contentGrowth' => $contentGrowth
];

// Close the database connection
$conn->close();

// Output the data in JSON format for JavaScript
echo json_encode($statsData);
?>
