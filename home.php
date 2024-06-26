<?php 
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Just Buzzin</title>
    <link rel="stylesheet" href="./css/style-sheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <?php include 'ddl/db_connect.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'siteStats.html'; ?>
<div class="post-wrapper">

<div class="form-group">
    <div class="selection-bar">
        <button type="button" id="showStats">Insights and Hot Posts</button>

        <form action="" method="get" style="display:inline;">
            <select id="category" name="category">
                <option value="0">Select a category</option>
                <?php
                // SQL to select all categories
                $sql = "SELECT id, name FROM categories ORDER BY name";
                $result = $conn->query($sql);

                // Retrieve the selected category ID from the URL parameter (if any)
                $selectedCategoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        // Determine if this option should be marked as selected
                        $selected = ($row["id"] == $selectedCategoryId) ? 'selected' : '';
                        echo '<option value="' . $row["id"] . '" ' . $selected . '>' . htmlspecialchars($row["name"]) . '</option>';
                    }
                } else {
                    echo '<option value="0">No categories found</option>';
                }
                ?>
            </select>
            <button type="submit" style="display: inline;" id="filter">Filter</button>
            <button type="button" id="myBtn">Create Categroy</button>
        </form>
    </div>
    <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form action="./ddl/categorySubmit.php" method="post">
                    <h2>Add New Category</h2>
                    <label for="categoryName">Name:</label>
                    <input type="text" id="categoryName" name="categoryName" required><br><br>
                    <label for="categoryDescription">Description:</label>
                    <textarea id="categoryDescription" name="categoryDescription" rows="4" cols="50"></textarea><br><br>
                    <label for="categoryColor">Color: </label>
                    <input type="color" id="categoryColor" name="categoryColor" required><br><br>
                    <button type="submit" name="submitCategory">Add Category</button>
                </form>
            </div>
        </div>
    

</div>
    <?php
    $searchTerm = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : null;
    if (isset($_GET['category']) && $_GET['category'] != 0 && $searchTerm) {
        $categoryId = (int) $_GET['category'];
        // Category filter and search term are provided
        $sql = "SELECT posts.id, posts.title, posts.content, categories.name AS category_name, categories.color AS category_color, users.display_name, posts.creation_date,
                COALESCE(SUM(post_votes.vote), 0) AS total_upvotes
                FROM posts
                JOIN users ON posts.user_id = users.id
                LEFT JOIN categories ON posts.category_id = categories.id
                LEFT JOIN post_votes ON posts.id = post_votes.post_id
                WHERE categories.id = ? AND (posts.title LIKE ? OR posts.content LIKE ?)
                GROUP BY posts.id
                ORDER BY total_upvotes DESC, posts.creation_date DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $categoryId, $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();
    } elseif ($searchTerm) {
        // Only search term is provided
        $sql = "SELECT posts.id, posts.title, posts.content, categories.name AS category_name, categories.color AS category_color, users.display_name, posts.creation_date,
                COALESCE(SUM(post_votes.vote), 0) AS total_upvotes
                FROM posts
                JOIN users ON posts.user_id = users.id
                LEFT JOIN categories ON posts.category_id = categories.id
                LEFT JOIN post_votes ON posts.id = post_votes.post_id
                WHERE posts.title LIKE ? OR posts.content LIKE ?
                GROUP BY posts.id
                ORDER BY total_upvotes DESC, posts.creation_date DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();
    } else {
        if (isset($_GET['category']) && $_GET['category'] != 0) {
            $categoryId = (int) $_GET['category'];
            $sql = "SELECT posts.id, posts.title, posts.content, categories.name AS category_name, categories.color AS category_color, users.display_name, posts.creation_date,
                    COALESCE(SUM(post_votes.vote), 0) AS total_upvotes
                    FROM posts
                    JOIN users ON posts.user_id = users.id
                    LEFT JOIN categories ON posts.category_id = categories.id
                    LEFT JOIN post_votes ON posts.id = post_votes.post_id
                    WHERE categories.id = $categoryId
                    GROUP BY posts.id
                    ORDER BY total_upvotes DESC, posts.creation_date DESC";
                    $result = $conn->query($sql);
        } else {
            $sql = "SELECT posts.id, posts.title, posts.content, categories.name AS category_name, categories.color AS category_color, users.display_name, posts.creation_date,
                    COALESCE(SUM(post_votes.vote), 0) AS total_upvotes
                    FROM posts
                    JOIN users ON posts.user_id = users.id
                    LEFT JOIN categories ON posts.category_id = categories.id
                    LEFT JOIN post_votes ON posts.id = post_votes.post_id
                    GROUP BY posts.id
                    ORDER BY total_upvotes DESC, posts.creation_date DESC";
                    $result = $conn->query($sql);
        }    
    }
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="post-container">
                    <div class="vote-buttons">
                        <button class="upvote-button" data-post-id="' . $row["id"] . '">&#8679;</button>
                        <span id="votes-count-' . $row["id"] . '">' . $row["total_upvotes"] . '</span>
                        <button class="downvote-button" data-post-id="' . $row["id"] . '">&#8681;</button>
                    </div>
                    <div class="post-title">
                        <a href="post.php?id=' . $row["id"] . '">
                         <h1>' . htmlspecialchars($row["title"]) . '</h1>
                        </a>
                        <div class="post-category" style="background-color: ' . htmlspecialchars($row['category_color']) . ';">' . htmlspecialchars($row['category_name']) . '</div>
                    </div>
                    <div class="post-details">
                        <span>posted by ' . htmlspecialchars($row["display_name"]) . '</span>
                        <span>' . $row["creation_date"] . '</span>
                    </div>
                </div>';
        }
    } else {
        echo "0 results";
    }
    
    $conn->close();
    ?>
</div>
<script>
$(document).ready(function() {
    $("#myBtn").click(function() {
        $("#myModal").show();
    });
    $(".close").click(function() {
        $("#myModal").hide();
    });
    $(window).click(function(event) {
        if ($(event.target).is("#myModal")) {
            $("#myModal").hide();
        }
    });
    
    $("#showStats").click(function() {
        $("#categoryPopup").show();
    });
    $(".popup-close-btn").click(function() {
        $("#categoryPopup").hide();
    });
    $(window).click(function(event) {
        if ($(event.target).is("#categoryPopup")) {
            $("#categoryPopup").hide();
        }
    });
});
</script>

<script src="./js/votelogic.js"></script>
<script src="./js/adjustPostWrapperPadding.js"></script>
  
</body>
</html>

<?php ob_end_flush(); ?>