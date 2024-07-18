<?php
session_start(); 


$connection = require("connection.php");


if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION["user_id"];
    $book_id = $_POST["book_id"];
    $quantity = $_POST["quantity"];
    $message = $_POST["message"];
    
   
    $query = "INSERT INTO orders (user_id, book_id, quantity, message, status) VALUES ('$user_id', '$book_id', '$quantity', '$message', 'Pending')";
    $result = mysqli_query($connection, $query);

    if ($result) {
       
        header("Location: order_confirmation.php");
        exit();
    } else {
       
        echo "<h1>Error</h1>";
        echo "<p>Failed to process your order. Please try again later.</p>";
    }
} else {
    
    echo "<h1>Error</h1>";
    echo "<p>Invalid request.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            padding:0;
            margin:0;
        }
        .nav-item{
           width:100%;
           background-color:blue;
           color:white;
           
        }
        .nav-item a{
            color:white;
            text-align:center;
            position: relative;
            margin-left: 190vh;
        }
       
        h2{
            text-align:center;
        }
        h1{
            text-align:center;
        }

        footer{
            width:100%;
        }
        .copyright{
            width:100%;
           background-color:black;
           position:relative;
           top:500px;
           color:white;
           padding:30px;
        }
    </style>
</head>
<body>

<nav class="nav-item">
<a href="user_dashboard.php"alt="">Back To Home</a>
<h1>Product Approval?:Dont worry notify here!</h1>

</nav>

<footer>
    <div class="copyright">
          <p>&copy; 2024 Book Website. All rights reserved.</p>
      </div>


</footer>

</body>
</html>
