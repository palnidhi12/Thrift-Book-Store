<?php
session_start();
$connection = require("connection.php");
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "Session variable not set!";
    var_dump($_SESSION); 
    exit;
}

$uploadMessage = "";
$messageClass = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $author = mysqli_real_escape_string($connection, $_POST['author']);
    $isbn = mysqli_real_escape_string($connection, $_POST['isbn']);
    $price = mysqli_real_escape_string($connection, $_POST['price']); 
    $fictional = mysqli_real_escape_string($connection, $_POST['fictional']);
    $description = mysqli_real_escape_string($connection, $_POST['description']); 
   
    if ($isbn < 0) {
        $uploadMessage = "ISBN cannot be negative.";
        $messageClass = "error-message";
    } elseif (empty($title) || empty($author) || empty($isbn) || empty($price) ||empty($description) ||!isset($_FILES['image'])) {
        $uploadMessage = "Please fill in all fields.";
        $messageClass = "error-message";
    } else {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $uploadMessage = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            $messageClass = "success-message";
            $sql = "INSERT INTO books (title, author, isbn, price, fictional,description, image) VALUES ('$title', '$author', '$isbn', '$price', '$fictional','$description', '$targetFile')";
            if ($connection->query($sql) === TRUE) {
                $uploadMessage .= " New book added successfully.";
            } else {
                $uploadMessage .= " Error: " . $sql . "<br>" . $connection->error;
                $messageClass = "error-message";
            }
        } else {
            $uploadMessage = "Sorry, there was an error uploading your file.";
            $messageClass = "error-message";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 8px;
}

input[type="text"],
input[type="number"],
input[type="file"] {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

input[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.uploaded-image {
    text-align: center;
    margin-top: 20px;
}

.uploaded-image img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
.nav-item {
            width: 100%;
            color: white;
            background-color: #4580d9;
            /* position: fixed; */
            top: 0;
            left: 0;
            padding: 10px 0;
            text-align: center;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
        }
        .success-message {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 4px;
        }

        .error-message {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 4px;
        }

        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            /* position: fixed; */
            bottom: 0;
            left: 0;
            width: 100%;
        }


    </style>
</head>
<body>

<nav class="nav-item">
<a href="admin_dashboard.php" alt="">Back To Home</a>
</nav>
<?php
if (!empty($uploadMessage)) {
    echo "<div class='$messageClass'>$uploadMessage</div>";
}
?>
<div class="container">
   
    <h1>Add Book</h1>

   
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" ><br>
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" ><br>
        <label for="isbn">ISBN:</label><br>
        <input type="number" id="isbn" name="isbn" ><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" ><br>
        <label for="fictional">Fictional (1 for fictional, 0 for non-fictional):</label><br>
        <input type="number" id="fictional" name="fictional" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" ></textarea><br>
        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" ><br><br>
        <input type="submit" value="Add Book">
    </form>
    <?php
    if (isset($targetFile)) {
        echo "<h2>Uploaded Image:</h2>";
        $imagesFolder = "images/";
        $imagePath = $imagesFolder . basename($targetFile);
        echo "<div class='uploaded-image'><img src='$imagePath' alt='Uploaded Image'></div>";
    }
    ?>
</div>

    <br>
    
    <footer>
    <p>&copy; 2024 Book Website. All rights reserved.</p>
</footer>
</body>
</html>

<?php

$connection->close();
?>
<!-- This incredible book beautifully explains the various activities that occur in our daily lives. It resonates with our vibes, offers entertainment, and provides motivation, making it a captivating and inspiring read. -->