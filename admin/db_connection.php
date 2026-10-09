<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "db_urbannest";
$port = 3306;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set UTF-8 character encoding for the database connection
mysqli_set_charset($conn, "utf8mb4");

// Make both variable names available
$con = $conn;

require_once __DIR__ . "/includes/auth.php";

// echo "Database connection established successfully.";