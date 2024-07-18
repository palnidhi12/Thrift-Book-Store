<?php
session_start();
require("connection.php");
$message="";

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "Session variable not set!";
    var_dump($_SESSION); 
    exit;
}


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
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


mysqli_select_db($connection, "soniyya_login_container");


$delete_orders_sql = "DELETE FROM orders WHERE book_id=$book_id";
if ($connection->query($delete_orders_sql) === TRUE) {
    
    $delete_books_sql = "DELETE FROM books WHERE id=$book_id";
    if ($connection->query($delete_books_sql) === TRUE) {
        $_SESSION['message'] = "Book Deleted successfully.";
        header("Location: manage_books.php"); 
        exit;
    } else {
        echo "Error deleting book: " . $connection->error;
    }
} else {
    echo "Error deleting associated orders: " . $connection->error;
}

$connection->close();

header("Location: manage_books.php");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="message"><?= $message ?></div>
</body>
</html>