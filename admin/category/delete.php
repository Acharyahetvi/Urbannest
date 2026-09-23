<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../db_connection.php";

if (!isset($con) && isset($conn)) {
    $con = $conn;
}

if (!isset($con)) {
    die("Database connection not found.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Category ID.");
}

$id = (int)$_GET['id'];

/* Get category photo */

$stmt = mysqli_prepare(
    $con,
    "SELECT photo FROM categories WHERE category_id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$category = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$category) {
    die("Category not found.");
}

/* Delete category */

$stmt = mysqli_prepare(
    $con,
    "DELETE FROM categories WHERE category_id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    if (!empty($category['photo'])) {

        $photoPath = __DIR__ .
            "/../assets/images/category/" .
            $category['photo'];

        if (is_file($photoPath)) {
            unlink($photoPath);
        }
    }

    mysqli_stmt_close($stmt);

    header("Location: view.php");
    exit;
} else {

    mysqli_stmt_close($stmt);

    die("Unable to delete category. It may be used by another table.");
}
