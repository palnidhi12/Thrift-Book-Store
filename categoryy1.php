<?php
session_start();

$connection = require ("connection.php");

$query_fictional = "SELECT id, title, author, isbn, price, image FROM books WHERE fictional = 1";
$result_fictional = mysqli_query($connection, $query_fictional);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fictional Category</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        .nav-item {
            width: 100%;
            height: 10vh;
            background-color: #4580d9;
            color: white;
            padding: 21px 0;
            text-align: center;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-item a {
            color: white;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10%;
        }

        h1 {
            text-align: center;
        }

        .product-container {
            display: grid;
            grid-template-columns: 20% 20% 20% 20%;
            gap: 25px;
            justify-content: center;
            padding: 20px;
        }

        .product {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            background-color: #fff;
        }

        .image {
            max-width: 100%;
            height: 250px;
            content: "";
            /* background-color: red; */
        }

        .product .image img {
            max-width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .product h3 {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .product p {
            margin: 5px 0;
        }

        .product p:first-child {
            font-weight: bold;
        }

        footer {
            width: 100%;
            height: 10vh;
            min-width: 10vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0);
            color: white;
        }

        .copyright {
            padding: 30px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="content">
        <nav class="nav-item">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="user_dashboard.php">Back To Home</a>
            <?php else: ?>
                <a href="index.php">Back To Home</a>
            <?php endif; ?>
            <h1>List of Fictional Books</h1>
        </nav>

        <section class="product-container">
            <?php
            if ($result_fictional && mysqli_num_rows($result_fictional) > 0) {
                while ($row = mysqli_fetch_assoc($result_fictional)) { ?>
                    <article class="product">
                        <a href="<?php echo 'book_details.php?id=' . $row['id']; ?>" class="details-link">
                            <div class="image">

                                <img src="<?php echo $row['image'] ?>" alt="<?php echo $row['title'] ?>" srcset="">
                            </div>
                            <h3><?php echo $row['title'] ?></h3>
                            <h3><?php echo $row['author'] ?></h3>
                            <h3><?php echo $row['price'] ?></h3>

                        </a>
                    </article>
                    <?php
                }
            } else {
                echo "<p>No fictional books found.</p>";
            }
            ?>
        </section>
    </div>

    <footer>
        <div class="copyright">
            <p>&copy; 2024 Book Website. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>