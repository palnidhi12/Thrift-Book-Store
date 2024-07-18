<?php
session_start();
$connection = require("connection.php");


if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "Session variable not set!";
    var_dump($_SESSION);
    exit;
}


if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to access this page.";
    exit;
}


mysqli_select_db($connection, "soniyya_login_container");

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = $_GET['id'];
   
    $deleteSql = "DELETE FROM users WHERE user_id = $userId";
    if ($connection->query($deleteSql) === TRUE) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $connection->error;
    }
}


$sql = "SELECT * FROM users";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
            margin-bottom: 10px;
            color: #007bff;
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

        .delete-link {
            color: #ff3333;
            margin-left: 5px;
            cursor: pointer;
        }

        .delete-link:hover {
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
    <h1>Manage Users</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["user_id"]."</td>";
                echo "<td>".$row["username"]."</td>";
                echo "<td>".$row["role"]."</td>";
                echo "<td><a href='manage_users.php?action=delete&id=".$row["user_id"]."'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }
        ?>
    </table>
    <br>
    <footer>
    <p>&copy; 2024 Book Website. All rights reserved.</p>
</footer>
    
</body>
</html>

<?php
$connection->close();
?>
