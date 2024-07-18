<?php
session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit(); 
}

$connection = require("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if ($status === 'completed') {
        $query_update_status = "UPDATE orders SET status = '$status', completed_at = NOW() WHERE id = $order_id";
    } else {
        $query_update_status = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    }

    $result_update_status = mysqli_query($connection, $query_update_status);

    if ($result_update_status) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Error updating order status: " . mysqli_error($connection);
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
