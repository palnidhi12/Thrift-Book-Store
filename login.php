<?php
session_start();
require ("connection.php");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$db_name = "soniyya_login_container";
if (!mysqli_select_db($connection, $db_name)) {
    die("Database selection failed: " . mysqli_error($connection));
}

$validationMessages = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    if (empty($username)) {
        $validationMessages['username'] = "Please enter your username.";
    }

    if (empty($password)) {
        $validationMessages['password'] = "Please enter your password.";
    }

    if (empty($role)) {
        $validationMessages['role'] = "Please select a role.";
    }

    if (empty($validationMessages)) {
        $username = mysqli_real_escape_string($connection, $username);
        $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
        $result = $connection->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                if ($row['role'] === $role) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['customer_name'] = $user['name'];
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    if (isset($_SESSION['redirect_url'])) {
                        $redirect_url = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("Location: $redirect_url");
                        exit();
                    } else {
                        if ($role === 'admin') {
                            header("Location: admin_dashboard.php");
                            exit();
                        } else {
                            header("Location: user_dashboard.php");
                            exit();
                        }
                    }
                } else {
                    $validationMessages['role'] = "Incorrect role selected.";
                }
            } else {
                $validationMessages['password'] = "Invalid username or password.";
            }
        } else {
            $validationMessages['username'] = "User does not exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .loginparent {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: auto;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #4caf50;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-container a {
            text-decoration: none;
            color: #4caf50;
        }

        .validate {
            position: absolute;
            top: 124px;
            color: red;
            width: 100%;
            text-align: center;
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
    </style>
</head>

<body>
    <nav class="nav-item">
        <a href="index.php" alt="">Back To Home</a>
    </nav>
        
    <div class="loginparent">
        <?php if (isset($validationMessages['role'])): ?>
            <div class="validate"><?php echo $validationMessages['role']; ?></div>
        <?php endif; ?>
        <div class="login-container">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <input type="text" id="username" name="username" placeholder="Username">
                <?php if (isset($validationMessages['username'])): ?>
                    <span style="color: red;"><?php echo $validationMessages['username']; ?></span>
                <?php endif; ?>
                <br><br>
                <input type="password" id="password" name="password" placeholder="Password">
                <?php if (isset($validationMessages['password'])): ?>
                    <span style="color: red;"><?php echo $validationMessages['password']; ?></span>
                <?php endif; ?>
                <br><br>
                <input type="radio" id="user" name="role" value="user" checked>
                <label for="user">User</label>
                <input type="radio" id="admin" name="role" value="admin">
                <label for="admin">Admin</label><br><br>
                <input type="submit" value="Login">
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>

    <footer>
        <div class="copyright">
            <p>&copy; 2024 Book Website. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>