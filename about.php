<?php
session_start()
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift Book Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .nav-item {
            width: 100%;
            height: 10vh;
            /* background-color:blue; */
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
            text-decoration: none;
            color: white;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10%;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 20px;
            gap: 20px;
        }




        .explanation {
            /* text-align: center; */
            text-align: justify;
            width: 800px;
            max-width: 100%;
            margin-top: 20px;
        }

        .explanation h2 {
            color: #333;
            margin-bottom: 25px;
        }

        .explanation p {
            color: #666;
            font-size: 1rem;
        }

        footer {
            width: 100%;
            height: 10vh;
            background-color: black;
            width: 100%;
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
        <h1>Creative Behind the beautiful creation!</h1>

    </nav>




    <div class="container">


        <div class="explanation">
            <h2>Meet the Mind: Nidhi Kumari Pal</h2>
            <p>Greetings, fellow book enthusiasts! Welcome to our cozy online thrift book nook, where every page
                holds the promise
                of adventure and discovery. We're Soniya Pantha and Nidhi Kumari Pal, the passionate minds behind
                this digital
                sanctuary for literature lovers. Our mission is simple yet profound: to offer you an expansive
                selection of
                affordable reads that not only entertain but also enrich your life. From well-loved classics that
                have stood
                the test of time to hidden literary gems waiting to be unearthed, our curated collection spans
                genres, eras, and
                cultures. But we're more than just purveyors of books; we're champions of the written word,
                committed to
                fostering a vibrant community of readers who share our love for storytelling. So whether you're
                seeking an escape
                into the past, a glimpse into the future, or simply a moment of solace in the present, we invite you
                to peruse
                our virtual shelves with curiosity and delight. Let the magic of literature captivate your
                imagination, spark your
                intellect, and nourish your soul as you embark on a journey through the realms of fiction and
                non-fiction alike.
                Welcome to our literary haven—where every book is a portal to endless possibilities.</p>
        </div>
    </div>

    <footer class="footer">
        <div class="copyright">
            <p>&copy; 2024 Book Website. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>