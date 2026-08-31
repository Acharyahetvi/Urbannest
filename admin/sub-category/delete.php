<?php

require_once __DIR__ . '/../../includes/db.php';

if (!isset($_GET['id'])) {
    die("Subcategory ID not found.");
}

$subcategory_id = $_GET['id'];

// Delete related properties first
$sql1 = "DELETE FROM properties 
         WHERE subcategory_id = '$subcategory_id'";

if (mysqli_query($con, $sql1)) {

    // Delete subcategory
    $sql2 = "DELETE FROM sub-ategories 
             WHERE subcategory_id = '$subcategory_id'";

    if (mysqli_query($con, $sql2)) {

        header("Location: view.php");
        exit();
    } else {
        echo "Error deleting subcategory: " . mysqli_error($con);
    }
} else {
    echo "Error deleting properties: " . mysqli_error($con);
}
