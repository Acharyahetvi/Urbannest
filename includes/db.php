<?php

// =====================================
// 1. START SESSION
// =====================================

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// =====================================
// 2. DATABASE CONNECTION
// =====================================

// Database details
$host = "localhost";
$username = "root";
$password = "";
$database = "db_urbannest";

// Connect to database
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}


// =====================================
// 3. SET CHARACTER SET
// =====================================

mysqli_set_charset($con, "utf8mb4");


// =====================================
// 4. HELPER FUNCTION
// =====================================

// Securely display text on the page
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}


// =====================================
// 5. GET ASSET PATH
// =====================================

function un_prefix()
{
    $script = trim($_SERVER['SCRIPT_NAME'], '/');
    $depth = substr_count($script, '/');

    return str_repeat('../', $depth);
}


// =====================================
// 6. ASSET FUNCTION
// =====================================

function asset($path)
{
    return un_prefix() . 'assets/' . ltrim($path, '/');
}


// =====================================
// 7. URL FUNCTION
// =====================================

function url($path)
{
    return un_prefix() . ltrim($path, '/');
}


// =====================================
// 8. ACTIVE MENU FUNCTION
// =====================================

function is_active($needle)
{
    if (strpos($_SERVER['SCRIPT_NAME'], $needle) !== false) {
        return 'active';
    }

    return '';
}
