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

$sql = "SELECT * FROM orders WHERE status='rejected' OR status='completed'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $orders = []; 
}


if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE orders SET status='$new_status' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Order status updated successfully.";
        header("Location: reject_completehistory.php"); 
        exit;
    } else {
        echo "Error updating order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - Rejected and Completed</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        form {
            display: inline-block;
        }
        
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        
        button {
            padding: 8px 12px;
            background-color: #4580d9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        button:hover {
            background-color: #325dab;
        }
        
        .message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        
        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<nav class="nav-item">
    <a href="admin_dashboard.php">Back To Home</a>
</nav>
<h1>Order History - Rejected and Completed</h1>

    <div class="container">

<?php if (!empty($_SESSION['message'])): ?>
    <div class="message"><?= $_SESSION['message'] ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['customer_name'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <select name="new_status">
                        <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="rejected" <?= ($order['status'] == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                        <option value="completed" <?= ($order['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <button type="submit" name="update_status">Update Status</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


    </div>
    <footer>
    <p>&copy; 2024 Book Website. All rights reserved.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
