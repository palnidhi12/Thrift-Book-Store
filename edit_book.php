<?php
session_start();
$connection = require("connection.php");

$message = "";
$error="";
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "Session variable not set!";
    var_dump($_SESSION); 
    exit;
}


if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to access this page.";
    exit;
}


if (!isset($_GET['id'])) {
    echo "Book ID not provided.";
    exit;
}

$book_id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id=$book_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $book = $result->fetch_assoc();
} else {
    echo "Book not found.";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];

   
    if (!isValidISBN($isbn)) {
        $error = "Invalid ISBN format.";
    } elseif ($isbn < 0) {
        $error = "ISBN cannot be negative.";
    } else {
        $update_sql = "UPDATE books SET title='$title', author='$author', isbn='$isbn' WHERE id=$book_id";
        
        if ($conn->query($update_sql) === TRUE) {
            $_SESSION['message'] = "Book updated successfully.";
            header("Location: manage_books.php");
            exit;
        } else {
            echo "Error updating book: " . $conn->error;
        }
    }
}

function isValidISBN($isbn){
    return !empty($isbn) && preg_match('/^[0-9-]+$/',$isbn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <style>
        .nav-item {
            color: white;
            background-color: #4580d9;
            top: 0;
            left: 0;
            padding: 10px 0;
            text-align: center;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
        }
h1{
    text-align: center;
}
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4580d9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #325dab;
        }
        .message {
            margin-top: 20px;
            color: green;
        }
        .error {
            margin-top: 20px;
            color: red;
        }
        footer {
            background-color: black;
            color: white;
            position: relative;
            top:150px;
            text-align: center;
            padding: 10px 0;
            bottom: 0;
            left: 0;
            
        }
    </style>
</head>
<body>
<nav class="nav-item">
<a href="admin_dashboard.php" alt="">Back To Home</a>
</nav>
<div class="message"><?= $message ?></div>
<div class="error"><?=$error?></div>
    <h1>Edit Book</h1>
    <form method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required><br>
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required><br>
        <label for="isbn">ISBN:</label><br>
        <input type="text" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>" required><br><br>
        <input type="submit" value="Update Book">
    </form>
    <br>
    <footer>
    <p>&copy; 2024 Book Website. All rights reserved.</p>
</footer>
</body>
</html>

<?php

$conn->close();
?>
