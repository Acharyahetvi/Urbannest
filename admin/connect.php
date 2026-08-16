<?php

$servername = "localhost";
$username = "root";
$password = "";
$database_name = "urbannest";

$con = mysqli_connect($servername, $username, $password, $database_name);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

//echo "Database connected successfully!";

?>