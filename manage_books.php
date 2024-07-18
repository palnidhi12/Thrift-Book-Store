<?php
session_start();
$connection = require("connection.php");
$message="";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}
$sql = "SELECT * FROM books";

$result = $connection->query($sql);


if (!$result) {
    echo "Error: " . $connection->error;
    exit;
}

$file = 'manage_books.php';
if (!is_readable($file)) {
    echo "The file is not readable.";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
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
.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

.action-links {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.action-links a {
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    transition: background-color 0.3s;
}

.action-links a:hover {
    background-color: #0056b3;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}
.message {
            margin-top: 20px;
            color: green;
        }
    </style>
</head>
<body>
<nav class="nav-item">
<a href="admin_dashboard.php" alt="">Back To Home</a>

</nav>
<?php if ($message) : ?>
    <div class="message"><?= $message ?></div> 
<?php endif; ?>
    <h1>Manage Books</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["title"]."</td>";
                echo "<td>".$row["author"]."</td>";
                echo "<td>";
            if ($row["isbn"] >= 0) {
                echo $row["isbn"];
            } else {
                echo "Invalid ISBN";
            }
            echo "</td>";
                echo "<td><a href='edit_book.php?id=".$row["id"]."'>Edit</a> | <a href='delete_book.php?id=".$row["id"]."'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No books found</td></tr>";
        }
        ?>
    </table>
    
</body>
</html>

<?php

$connection->close();
?>
