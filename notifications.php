<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}


$connection = require("connection.php");


$user_id = $_SESSION['user_id'];
$query_notifications = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
$result_notifications = mysqli_query($connection, $query_notifications);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        *{
            padding:0;
            margin:0;
        }
        .nav-item{
           width:100%;
           /* background-color:blue; */
           background-color: #4580d9;
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
            margin-top:22px;
        }
        h1{
            text-align:center;
        }
        .notification {
            background-color: lightgreen;
            padding: 10px;
            margin: 5px 0;
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
        .copyright p{
            text-align:center;
        }
    </style>
    
</head>
<body>
    <nav class="nav-item">
<a href="user_dashboard.php"alt="">Back To Home</a>
<h1>Your Product Approval: Stay Updated and Informed Here</h1>

</nav>

    <h2>Notifications</h2>
    <div class="notifications">
        <?php
        
        
        if (mysqli_num_rows($result_notifications) > 0) {
            while ($row = mysqli_fetch_assoc($result_notifications)) {
                echo "<div class='notification'>";
                echo "<p>{$row['message']}</p>";
                echo "<p>{$row['created_at']}</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No notifications.</p>";
        }
        ?>
    </div>
    <footer>
    <div class="copyright">
          <p>&copy; 2024 Book Website. All rights reserved.</p>
      </div>


</footer>
</body>
</html>
