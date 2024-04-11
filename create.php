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
    <script src="https://cdn.tiny.cloud/1/irg2l8zpaoxlgwq5z103ghjkk91vnte0gxnrv5yiz5ngl2ud/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#content',
        plugins: 'link image code',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
    </script>

    <style>
        .level-2-button{
            margin: 10px 5px;
            padding: 10px;
        }
        #content{
            height:80vh;
        }
    </style>
</head>
<body>

<div class="post" style="margin-top:50px;">
    <a href="home.php"><button class="level-2-button">Home</button></a>
    <form action="" method="post">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <br/>
        <div class="form-group">
            <textarea id="content" name="content" required></textarea>
        </div>
        </br>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
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
            </br>
        <button type="submit" class="level-2-button">Submit Post</button>
    </form>
</div>
</body>
</html>