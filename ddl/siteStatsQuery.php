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

// Active Users
$activeUsersQuery = "
SELECT u.display_name AS user, 
       COUNT(DISTINCT p.id) AS posts_count, 
       COUNT(DISTINCT c.id) AS comments_count
FROM users u
LEFT JOIN posts p ON u.id = p.user_id
LEFT JOIN comments c ON u.id = c.user_id
GROUP BY u.id
ORDER BY posts_count DESC, comments_count DESC
LIMIT 10;
";
$activeUsers = executeQuery($conn, $activeUsersQuery);

// Hot Threads
$hotThreadsQuery = "
SELECT p.title AS post_title, 
       COUNT(c.id) AS comments_count
FROM posts p
JOIN comments c ON p.id = c.post_id
GROUP BY p.id
ORDER BY comments_count DESC
LIMIT 10;
";
$hotThreads = executeQuery($conn, $hotThreadsQuery);

// User Registration Over Time
$registrationQuery = "
SELECT DATE(registration_date) AS date, 
       COUNT(id) AS users_count
FROM users
GROUP BY DATE(registration_date)
ORDER BY date ASC;
";
$userRegistration = executeQuery($conn, $registrationQuery);

// Most Popular Categories
$popularCategoriesQuery = "
SELECT cat.name AS category_name, 
       COUNT(post.id) AS posts_count
FROM categories cat
JOIN posts post ON cat.id = post.category_id
GROUP BY cat.id
ORDER BY posts_count DESC
LIMIT 10;
";
$popularCategories = executeQuery($conn, $popularCategoriesQuery);

// Site Activity By Date
$activityQuery = "
SELECT DATE(a.date) AS activity_date, 
       SUM(a.posts) AS posts, 
       SUM(a.comments) AS comments
FROM (
    SELECT creation_date AS date, 
           COUNT(id) AS posts, 
           0 AS comments 
    FROM posts 
    GROUP BY creation_date
    UNION ALL
    SELECT creation_date AS date, 
           0 AS posts, 
           COUNT(id) AS comments 
    FROM comments 
    GROUP BY creation_date
) a
GROUP BY DATE(a.date)
ORDER BY activity_date ASC;
";
$siteActivity = executeQuery($conn, $activityQuery);

// Combine all data into one array
$statsData = [
    'activeUsers' => $activeUsers,
    'hotThreads' => $hotThreads,
    'userRegistration' => $userRegistration,
    'popularCategories' => $popularCategories,
    'siteActivity' => $siteActivity
];

// Close the database connection
$conn->close();

// Output the data in JSON format for JavaScript
echo json_encode($statsData);
?>