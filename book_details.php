<?php

session_start();

$connection = require ("connection.php");

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    $query_book = "SELECT id, title, author, isbn, price, image, description FROM books WHERE id = $book_id";
    $result_book = mysqli_query($connection, $query_book);

    if ($result_book && mysqli_num_rows($result_book) > 0) {
        $book = mysqli_fetch_assoc($result_book);
    } else {
        echo "<p>Book not found.</p>";
        exit();
    }
} else {
    echo "<p>Invalid request.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['title']; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        nav {
            width: 100%;
            height: 10vh;
            background-color: #4580d9;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
        }

        .book-image {
            max-width: 250px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin: 10px 0;
        }



        input[type="submit"] {
            background-color: #4580d9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;

        }

        .book-details {
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }

        .description {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .description strong {
            display: inline-block;
            width: 120px;
        }

        .description p {
            margin: 5px 0;
        }

        footer {
            width: 100%;
            height: 10vh;
            background-color: #000;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <nav class="nav-item">

        <a href="<?php echo isset($_SESSION['user_id']) ? 'user_dashboard.php' : 'index.php'; ?>" alt="">Back To
            Home</a>

    </nav>

    <div class="book-details">
        <h1><?php echo $book['title']; ?></h1>
        <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" class="book-image">
        <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
        <p><strong>ISBN:</strong> <?php echo $book['isbn']; ?></p>
        <p><strong>Price:</strong> Rs<?php echo $book['price']; ?></p>
        <!-- <p><strong>Description:</strong> <?php echo $book['description']; ?></p> -->
        <div class="description">
            <strong>Description:</strong><br>
            <?php
            if ($book['description'] !== null) {
                echo nl2br($book['description']);
            } else {
                echo "No description available. ";
            }
            ?>
        </div>

        <b>Please enter the quantity to order!</b>
        <form action="order_confirmation.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $book['id']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" required><br><br>
            <input type="submit" value="Order">
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Book Website. All rights reserved.</p>
    </footer>

</body>

</html>