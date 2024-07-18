<?php
session_start();

$connection = require ("connection.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $book_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $status = isset($_POST["status"]) ? $_POST["status"] : 'Pending';

    $customer_query = "SELECT username FROM users WHERE user_id = '$user_id'";
    $customer_result = mysqli_query($connection, $customer_query);

    if ($customer_result && mysqli_num_rows($customer_result) > 0) {
        $customer_data = mysqli_fetch_assoc($customer_result);
        $customer_name = $customer_data['username'];
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Failed to retrieve customer name. Please try again later.</p>";
        exit();
    }

    $customer_name_for_db = mysqli_real_escape_string($connection, $customer_name);

    $book_query = "SELECT title FROM books WHERE id = '$book_id'";
    $book_result = mysqli_query($connection, $book_query);

    if ($book_result && mysqli_num_rows($book_result) > 0) {
        $book_data = mysqli_fetch_assoc($book_result);
        $book_title = $book_data['title'];
    } else {
        $book_title = "Unknown Book";
    }

    $customer_name_for_display = htmlspecialchars($customer_name);
    $book_title_for_display = htmlspecialchars($book_title);

    $query = "INSERT INTO orders (user_id, book_id, quantity, status, customer_name, book_title) 
              VALUES ('$user_id', '$book_id', '$quantity', '$status', '$customer_name_for_db', '$book_title_for_display')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $order_success = true;
    } else {
        $order_success = false;
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
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .nav-item {
            width: 100%;
            color: white;
            background-color: #4580d9;
            position: fixed;
            top: 0;
            left: 0;
            padding: 10px 0;
            text-align: center;
            z-index: 1000;
        }

        .nav-item a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .confirmation-container {
            margin: 100px auto 20px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
            border-radius: 8px;
        }

        .confirmation-container h1,
        .confirmation-container p,
        .confirmation-container a.back-link {
            color: green;
        }

        .confirmation-container p {
            font-size: 16px;
            line-height: 1.6;
        }

        .confirmation-container .highlight {
            font-weight: bold;
            color: green;
        }

        .status-pending {
            color: green;
            font-weight: bold;
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
            z-index: 1000;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .back-link:hover {
            background-color: #356bb5;
        }
    </style>
</head>

<body>
    <nav class="nav-item">
        <a href="<?php echo isset($_SESSION['user_id']) ? 'user_dashboard.php' : 'thriftt.php'; ?>">Back To Home</a>
    </nav>

    <div class="confirmation-container">
        <?php if ($order_success): ?>
            <h1>Order Confirmation</h1>
            <p>Your order has been successfully placed:</p>
            <p><span class="highlight">Customer Name:</span> <?php echo $customer_name_for_display; ?></p>
            <p><span class="highlight">Book Title:</span> <?php echo $book_title_for_display; ?></p>
            <p><span class="highlight">Quantity:</span> <?php echo htmlspecialchars($quantity); ?></p>
            <p class="status-pending"><span class="highlight">Status:</span> Pending (Waiting for admin approval)</p>
        <?php else: ?>
            <h1>Error</h1>
            <p>Failed to process your order. Please try again later.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Book Website. All rights reserved.</p>
    </footer>
</body>

</html>