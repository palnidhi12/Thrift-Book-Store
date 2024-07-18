<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$connection = require("connection.php");

$user_id = $_SESSION['user_id'];
$query = "SELECT b.title, b.author, b.price, o.created_at 
          FROM orders o
          JOIN books b ON o.book_id = b.id
          WHERE o.user_id = $user_id
          ORDER BY o.created_at DESC";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Purchase History</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    line-height: 1.6;
}

.nav-item {
    background-color: #4580d9;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-item a {
    color:white;
    text-align:center;
    position: relative;
    margin-left: 143vh;
}

.nav-item h1 {
    margin: 0;
    font-size: 1.5rem;
    position:relative;
    left:-600px;
}

.product-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.product {
    margin-bottom: 20px;
    padding: 10px;
    border-bottom: 1px solid #ccc;
}

.product h2 {
    font-size: 1.2rem;
    margin-bottom: 5px;
}

.product p {
    margin: 5px 0;
}

footer{
            width:100%;
            text-align:center;
        }
        .copyright{
            width:100%;
           background-color:black;
           position:relative;
           top:624px;
           color:white;
           padding:30px;
           text-align:center;
        }

        .for-form{
    line-height: 0px;
    color: #fff; 
  }
  .search-container h5{
    color: #fff !important;
  }
  .pt-0{
    margin-top: 29px !important;
  }

@media (max-width: 768px) {
    .nav-item {
        flex-direction: column;
        text-align: center;
    }
    .nav-item h1 {
        margin-top: 10px;
    }
}

    </style>
</head>
<body>

<nav class="nav-item">
    <a href="user_dashboard.php">Back To Home</a>
    <h1>User Purchase History</h1>
</nav>

<section class="product-container">

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<article class='product'>";
            echo "<h2>{$row['title']}</h2>";
            echo "<p>Author: {$row['author']}</p>";
            echo "<p>Price: Rs {$row['price']}</p>";
            echo "<p>Order Date: {$row['created_at']}</p>";
            echo "</article>";
        }
    } else {
        echo "<p>No purchase history found.</p>";
    }
    ?>

</section>

<footer>
    <div class="copyright">
        <p>&copy; 2024 Book Website. All rights reserved.</p>
    </div>
</footer>

</body>
</html>

<?php
mysqli_close($connection);
?>
