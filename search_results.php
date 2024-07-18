<?php
session_start();
$connection = require ("connection.php");
$search_result = null;

if (isset($_GET['author'])) {
    $search_query = mysqli_real_escape_string($connection, $_GET['author']);
    $query = "SELECT id, title, author, isbn, price, image FROM books WHERE author LIKE '%$search_query%'";
    $search_result = mysqli_query($connection, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Thrift Book Store</title>
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

        .items {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            min-height: 80vh;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin: 20px;
            padding: 20px;
            height: fit-content;
            width: 300px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            text-align: center;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product h2 {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
            margin-bottom: 10px;
        }

        .product p {
            margin: 5px 10px;
            font-size: 16px;
            color: #666;
        }

        .product .price {
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            margin: 20px 0;
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
    <nav>
        <a href="<?php echo isset($_SESSION['user_id']) ? 'user_dashboard.php' : 'index.php'; ?>">Back To Home</a>
    </nav>
    <div class="items">
        <?php
        if ($search_result) {
            if (mysqli_num_rows($search_result) > 0) {
                while ($row = mysqli_fetch_assoc($search_result)) { ?>

                    <a href='<?php echo "book_details.php?id=" . $row['id'] ?>' class='details-link'>
                        <article class="product">
                            <img src='<?php echo $row['image'] ?>' alt='<?php echo $row['title'] ?>' />
                            <h2>
                                <?php echo $row['title'] ?>
                            </h2>
                            <p>Author: <?php echo $row['author'] ?></p>
                            <p class='price'>Price: Rs<?php echo $row['price'] ?></p>

                        </article>
                    </a>
                    <?php
                }
            } else {
                echo "<div class='error'>No books found with the given author.</div>";
            }
        } else {
            echo "<div class='error'>Please provide an author name to search for books.</div>";
        }
        ?>
    </div>
    <footer>
        &copy; 2024 Book Website. All rights reserved.
    </footer>
</body>

</html>