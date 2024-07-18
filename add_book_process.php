<?php
$connection = require("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];

    $query = "INSERT INTO books (title, author, isbn) VALUES ('$title', '$author', '$isbn')";
    if ($connection->query($query) === TRUE) {
        echo "New book added successfully.";
    } else {
        echo "Error: " . $connection->error;
    }

    $connection->close();
}
?>
