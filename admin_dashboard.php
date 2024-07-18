<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$connection = require ("connection.php");




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

        switch ($status) {
            case 'approved':
                $notification_message = "Your order with ID $order_id has been approved.";
                break;
            case 'rejected':
                $notification_message = "Your order with ID $order_id has been rejected.";
                break;
            case 'pending':
                $notification_message = "Your order with ID $order_id is pending.";
                break;
            case 'completed':
                $notification_message = "Your order with ID $order_id has been completed. Thank you for shopping with us.";
                break;
            default:
                $notification_message = "";
                break;
        }

        if (!empty($notification_message)) {
            $query_user_id = "SELECT user_id FROM orders WHERE id = $order_id";
            $result_user_id = mysqli_query($connection, $query_user_id);

            if ($result_user_id && mysqli_num_rows($result_user_id) > 0) {
                $row = mysqli_fetch_assoc($result_user_id);
                $user_id = $row['user_id'];

                $query_check_user = "SELECT user_id FROM users WHERE user_id = $user_id";
                $result_check_user = mysqli_query($connection, $query_check_user);

                if ($result_check_user && mysqli_num_rows($result_check_user) > 0) {

                    $query_insert_notification = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$notification_message')";
                    mysqli_query($connection, $query_insert_notification);
                }
            }
        }


        $_SESSION['message'] = "Order status updated successfully";
        header("Location: admin_dashboard.php");
        exit();
    } else {

        $error_message = "Error updating order status: " . mysqli_error($connection);
    }
}



if (isset($_GET['admin_logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}


$query_total_books = "SELECT COUNT(*) AS total_books FROM books";
$result_total_books = mysqli_query($connection, $query_total_books);
$total_books_row = mysqli_fetch_assoc($result_total_books);
$total_books = $total_books_row['total_books'];





$query_total_users = "SELECT COUNT(*) AS total_users FROM users";
$result_total_users = mysqli_query($connection, $query_total_users);
$total_users = mysqli_fetch_assoc($result_total_users)['total_users'];







$query_books_added_this_week = "SELECT COUNT(*) AS books_added_this_week FROM books WHERE DATE(added_date) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
$result_books_added_this_week = mysqli_query($connection, $query_books_added_this_week);
$books_added_this_week = mysqli_fetch_assoc($result_books_added_this_week)['books_added_this_week'];

$query_users_registered_this_month = "SELECT COUNT(*) AS users_registered_this_month FROM users WHERE MONTH(register_date) = MONTH(CURRENT_DATE())";
$result_users_registered_this_month = mysqli_query($connection, $query_users_registered_this_month);
$users_registered_this_month = mysqli_fetch_assoc($result_users_registered_this_month)['users_registered_this_month'];


$admin_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;

            background: linear-gradient(45deg, #ff6ec4, #4bcaff);
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            overflow: hidden;
        }


        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
            /* text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); */
        }

        .welcome {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }


        .overview,
        .statistics,
        .pending-orders {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }

        .logout {
            text-align: right;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            color: #555;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .logout a:hover {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #555;
        }

        .status-dropdown {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            /* background-color: #fff; */
            background-color: #007bff;
            color: white;
        }

        .update-btn {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <div class="logout">
            <a href="admin_logout.php">Logout</a>
        </div>
        <div class="welcome">
            Welcome, <?php echo $admin_name; ?>!
        </div>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p style='color: green;'>{$_SESSION['message']}</p>";
            unset($_SESSION['message']);
        }
        ?>
        <div class="overview">
            <h3>Quick Overview:</h3>
            <p>Total Books: <?php echo $total_books; ?></p>
            <p>Total Users: <?php echo $total_users; ?></p>
        </div>

        <div class="statistics">
            <h3>Statistics:</h3>
            <p>Books Added This Week: <?php echo $books_added_this_week; ?></p>
            <p>Users Registered This Month: <?php echo $users_registered_this_month; ?></p>
        </div>
        <div class="managebook">
            <h3> <a href="manage_books.php">Manage Books</a></h3>
        </div>
        <div class="managuser">
            <h3> <a href="manage_users.php">Manage users</a></h3>
        </div>
        <div class="addbook">
            <h3> <a href="add_book.php">Add books</a></h3>
        </div>
        <div class="updatestatus">
            <h3> <a href="reject_completehistory.php">Order History</a></h3>
        </div>
        <div class="pending-orders">
            <h3>Pending Orders:</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Book ID</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>CustomerName</th>
                    <th>Book Title</th>
                </tr>
                <?php

                $query_pending_approved_orders = "SELECT orders.id, orders.book_id, orders.quantity, orders.status, users.username AS customer_name,books.title AS book_title
        FROM orders
        LEFT JOIN users ON orders.user_id = users.user_id
         LEFT JOIN books ON orders.book_id = books.id
        WHERE orders.status IN ('pending', 'approved')";
                $result_pending_approved_orders = mysqli_query($connection, $query_pending_approved_orders);
                while ($row = mysqli_fetch_assoc($result_pending_approved_orders)) {

                    $orderId = $row['id'];
                    $bookId = $row['book_id'];
                    $bookTitle = $row['book_title'];
                    $quantity = $row['quantity'];
                    $status = $row['status'];
                    $customerName = $row['customer_name'];

                    echo "<tr>";
                    echo "<td>$orderId</td>";
                    echo "<td>$bookId</td>";
                    echo "<td>$quantity</td>";
                    echo "<td>$status</td>";
                    echo "<td>$customerName</td>";
                    echo "<td> $bookTitle</td>";


                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='order_id' value='$orderId'>";
                    echo "<select class='status-dropdown' name='status'>";
                    echo "<option value='pending'";
                    if ($status === 'pending')
                        echo " selected";
                    echo ">Pending</option>";
                    echo "<option value='rejected'>Rejected</option>";
                    echo "<option value='approved'";
                    if ($status === 'approved')
                        echo " selected";
                    echo ">Approved</option>";
                    echo "<option value='completed'>Completed</option>";
                    echo "</select>";
                    echo "<input type='submit'class='update-btn' name='submit' value='Update'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>




    </div>



    <script>

        const statusDropdowns = document.querySelectorAll('.status-dropdown');


        statusDropdowns.forEach(dropdown => {

            dropdown.addEventListener('change', function () {
                const orderId = this.closest('tr').id.split('-')[2]; // Extract order ID from row ID
                const selectedStatus = this.value;


                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_order_status.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {

                        if (selectedStatus === 'rejected' || selectedStatus === 'completed') {
                            document.getElementById(`order-row-${orderId}`).style.display = 'none';
                        }
                    } else {
                        console.error('Error updating order status');
                    }
                };
                xhr.send(`order_id=${orderId}&status=${selectedStatus}`);
            });
        });


    </script>



</body>

</html>