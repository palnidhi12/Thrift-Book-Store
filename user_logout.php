<?php
session_start(); 
require('connection.php');

$_SESSION = array();


session_destroy();


header("Location: login.php");
exit();
?>
