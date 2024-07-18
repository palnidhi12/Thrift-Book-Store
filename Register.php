<?php
session_start();
$connection = require ("connection.php");

$username = $password = $phone = $email = $dob = $address = "";
$username_err = $password_err = $phone_err = $email_err = $dob_err = $address_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }


    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = md5($_POST["password"]);
    }


    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }


    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter your date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }

    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter your address.";
    } else {
        $address = trim($_POST["address"]);
    }


    if (empty($username_err) && empty($password_err) && empty($phone_err) && empty($email_err) && empty($dob_err) && empty($address_err)) {

        $query = "SELECT user_id FROM users WHERE username = '$username'";
        $result = $connection->query($query);
        if ($result->num_rows > 0) {

            $username_err = "Username already taken.";
        } else {

            $query = "INSERT INTO users (username, password, phone, email,dob,address) VALUES ('$username', '$password', '$phone', '$email' ,'$dob','$address')";

            if ($connection->query($query) === TRUE) {
                echo "Registration successful. You can now login.";
                header("Location: login.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .nav-item {
            width: 100%;
            background-color: #4580d9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 10vh;
            color: white;
        }

        .registerparent {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 500px;

            padding: 50px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        footer {
            width: 100%;
            height: 10vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0);
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 16px;
            color: #333333;
            margin-top: -22px;

        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 7px;
            margin-bottom: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        input[type="date"]:focus {
            border-color: #007bff;
        }

        .error {
            color: #dc3545;
            font-size: 14px;
        }

        form {
            position: relative;
            bottom: 12px;
        }
    </style>
</head>

<body>
    <nav class="nav-item">
        <a href="index.php" alt="">Back To Home</a>

    </nav>
    <div class="registerparent">

        <div class="container">
            <h2>Registration Form</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span class="error"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password"
                        value="<?php echo htmlspecialchars($password); ?>">
                    <span class="error"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <span class="error"><?php echo $phone_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="error"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                    <span class="error"><?php echo $dob_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address"><?php echo htmlspecialchars($address); ?></textarea>
                    <span class="error"><?php echo $address_err; ?></span>
                </div>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
    </div>
    <footer>
        <div class="copyright">
            <p>&copy; 2024 Book Website. All rights reserved.</p>
        </div>


    </footer>
</body>

</html>