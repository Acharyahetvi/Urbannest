<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "db_urbannest";

// Database Connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check Connection
if (!$conn) {

    die("Database Connection Failed: " . mysqli_connect_error());
}

// For files using $con
$con = $conn;
