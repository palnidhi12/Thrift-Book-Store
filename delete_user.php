<?php
session_start();
$connection = require("connection.php");

$message="";
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
    echo "User ID not provided.";
    exit;
}

$userId = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deleteNotificationsSql = "DELETE FROM notifications WHERE user_id = $userId";
    if ($connection->query($deleteNotificationsSql) === FALSE) {
        $_SESSION['message'] = "Book deleted successfully.";
        header("Location: manage_books.php"); 
        exit;
    }


$deleteSql = "DELETE FROM users WHERE user_id = $userId";
if ($connection->query($deleteSql) === TRUE) {
    $message= "User deleted successfully.";
} else {
    echo "Error deleting user: " . $connection->error;
}
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .nav-item {
            width: 100%;
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
        .message {
            margin-top: 20px;
            color: green;
        }
        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
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
<div class="message"><?= $message ?></div>
<form method="post">
    <input type="submit" value="Delete User" id="deleteButton">
</form>
<footer>
    <p>&copy; 2024 Book Website. All rights reserved.</p>
</footer>
<script>
        document.getElementById("deleteButton").addEventListener("click", function(event){
            if (!confirm("Are you sure you want to delete this user?")) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>