<?php
require_once __DIR__ . '/../../includes/db.php';

// Check subcategory ID
if (!isset($_GET['id'])) {
    die("Subcategory ID not found.");
}

$subcategory_id = $_GET['id'];

// Delete subcategory
$sql = "DELETE FROM sub_categories 
        WHERE subcategory_id = '$subcategory_id'";

if (mysqli_query($con, $sql)) {

    header("Location: view.php");
    exit();

} else {

    echo "Error deleting subcategory: " . mysqli_error($con);
}
?>