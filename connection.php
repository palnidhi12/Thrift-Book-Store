<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    
    $connection = mysqli_connect('localhost', 'root', '');

   
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $sql = "CREATE DATABASE IF NOT EXISTS soniyya_login_container";

    if (mysqli_query($connection, $sql)) {
       
    } else {
        echo "Error creating database: " . mysqli_error($connection);
    }
    $conn = mysqli_connect("localhost", "root", "", "soniyya_login_container");
    return $conn;
    
} catch (Exception $ex) {
    die('Database Error: ' . $ex->getMessage());
}
?>
