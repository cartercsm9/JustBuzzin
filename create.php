<?php
session_start();

require_once 'ddl/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);


    // Validate and sanitize inputs
    
    $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss",$_SESSION['id'],$category,$title,$content);

    if($stmt->execute()){
        echo "post created";
        header('Location: home.php');
        exit(); 
    } else{
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/style-sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div class="post">
    <form action="" method="post">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <textarea id="content" name="content" required></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">Select a category</option>
                <?php
                // SQL to select all categories
                $sql = "SELECT id, name FROM categories ORDER BY name";
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["name"]) . '</option>';
                    }
                } else {
                    echo '<option value="0">No categories found</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="level-1-button">Submit Post</button>
    </form>
</div>

<script>
    
</script>

</body>
</html>
