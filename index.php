<?php
session_start();
$connection = require ("connection.php");

$query_fictional = "SELECT id, title, author, isbn, price, image FROM books WHERE fictional = 1";
$result_fictional = mysqli_query($connection, $query_fictional);

$query_non_fictional = "SELECT id, title, author, isbn, price, image FROM books WHERE fictional = 0";
$result_non_fictional = mysqli_query($connection, $query_non_fictional);

$query_most_ordered_books = "SELECT b.id, b.title, b.author, b.isbn, b.price, b.image, COUNT(*) AS order_count 
                             FROM books b
                             JOIN orders o ON b.id = o.book_id
                             GROUP BY b.id
                             ORDER BY order_count DESC
                             LIMIT 4";
$result_most_ordered_books = mysqli_query($connection, $query_most_ordered_books);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Thrift Book Store</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      text-decoration: none;
    }

    a {
      text-decoration: none;
    }

    .backgroundh {
      background-color: #973131;
      padding: 5px;
      height: 5vh;
    }

    h1 {
      font-size: 14px;
      margin-left: 40px;
      color: white;
    }

    nav {
      background-color: #365E32;
      display: flex;
      justify-content: space-evenly;
      align-items: center;
      padding: 10px 20px;
      height: 10vh;
    }

    nav a {
      color: white;
      text-decoration: none;
      padding: 14px 16px;
    }

    nav a:hover {
      background-color: #ddd;
      color: black;
    }

    .logo {
      max-width: 100px;
      border-radius: 50%;
      object-fit: cover;
    }


    summary {
      list-style: none;
      cursor: pointer;
    }

    details ul {
      list-style: none;
      position: absolute;

    }

    details ul li {
      background-color: white;
      padding: 10px;
      color: black;
    }

    details ul li a {
      color: black;
      padding: 10px;

    }

    .search-container {
      display: flex;
      align-items: center;
    }

    input[type=text] {
      padding: 10px;
      border: none;
      font-size: 17px;
    }

    button {
      padding: 12px 39px;
      background-color: #e44d26;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }

    button:hover {
      background-color: #333;
    }

    .backgroundimage {
      /* background-image: url('images/good6.jpg'); */
      background-repeat: no-repeat;
      /* background-size: cover; */
      /* object-fit: cover */
    }

    .backgroundimage img {
      width: 100%;
      height: 80vh;
      object-fit: cover;
      object-position: top;

    }

    header {
      color: white;
      text-align: center;
      padding: 1rem 0;
      background-color: #365E32;
    }

    .container h1 {
      background-color: #365E32;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 20px;
    }



    .product-container {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      padding: 2rem;
      background-color: #E0A75E;
    }

    .product {
      border: 1px solid #ccc;
      padding: 1rem;
      margin: 1rem;
      text-align: center;
      width: 250px;
      background-color: #F9D689;
      border-radius: 10px;
    }

    .product img {
      max-width: 100%;

      height: 200px;
      object-fit: cover;
      margin-bottom: 0.5rem;
    }

    .bold {
      color: blue;
      font-weight: bold;
    }

    footer {
      color: white;
      text-align: center;
      padding: 1rem 0;
      width: 100%;
      background-color: #973131;
    }

    .footer-content {
      display: flex;
      justify-content: space-around;
      align-items: center;
    }

    .footer-content p {
      margin: 0;
    }

    .contact-info a {
      color: white;
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .social-icons {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .social-icons li {
      display: inline-block;
      margin-right: 10px;
    }

    .social-icons a {
      color: white;
      text-decoration: none;
      font-size: 20px;
    }

    .social-icons a:hover {
      opacity: 0.7;
    }

    @media screen and (max-width: 600px) {
      .search-container {
        flex-direction: column;
        align-items: stretch;
      }

      input[type=text],
      button {
        width: 100%;
      }

      nav a {
        text-align: left;
      }
    }
  </style>
</head>

<body>
  <div class="backgroundh">
    <h1>Welcome to Thrift Book Store[LITERATURE BOOK!]</h1>
  </div>
  <nav>
    <img class="logo" src="images/book1.png" alt="Logo">
    <a href="#home">Home</a>
    <a href="about.php">About</a>
    <details>
      <summary><span>Categories</span></summary>
      <ul>
        <li><a href="categoryy1.php">Fictional</a></li>
        <li><a href="categoryy2.php">Non-Fictional</a></li>
      </ul>
    </details>
    <div class="search-container">
      <form class="for-form" action="search_results.php" method="get">
        <input placeholder="Search Book by Author" type="text" id="author" name="author" required>
        <button type="submit">Search</button>
      </form>
    </div>
    <a href="login.php">Login</a>
    <a href="Register.php">Register</a>
  </nav>
  <div class="backgroundimage">
    <img src="images/coverimage.jpg" alt="">
  </div>
  <header>
    <h1>Mostly Ordered Books</h1>
  </header>
  <div class="content-container">
    <section class="product-container">
      <?php while ($row = mysqli_fetch_assoc($result_most_ordered_books)) { ?>
        <p><a href="book_details.php?id=<?php echo $row['id']; ?>">
            <article class="product">
              <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
              <p class="bold"><?php echo $row['title']; ?></p>
              <p>Author: <?php echo $row['author']; ?></p>
              <p>ISBN: <?php echo $row['isbn']; ?></p>
              <p>Price: Rs <?php echo $row['price']; ?></p>

            </article>
          </a></p>
      <?php } ?>
    </section>
  </div>
  <footer>
    <div class="copyright">
      <p>&copy; 2024 Book Website. All rights reserved.</p>
    </div>
    <div class="phone">
      <p><i class="fa-solid fa-phone"></i> 9843482910</p>
    </div>
    <div class="social-media">
      <a href="https://www.facebook.com/YourPage" target="_blank" rel="noopener noreferrer">Facebook</a> ||
      <a href="https://twitter.com/YourPage" target="_blank" rel="noopener noreferrer">Twitter</a> ||
      <a href="https://www.instagram.com/YourPage" target="_blank" rel="noopener noreferrer">Instagram</a>
    </div>

  </footer>
</body>

</html>