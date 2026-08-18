<?php
include "../connect.php";

if (isset($_GET['id'])) {

    $category_id = $_GET['id'];

    $sql = "DELETE FROM categories WHERE category_id = '$category_id'";

    if (mysqli_query($con, $sql)) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error deleting category: " . mysqli_error($con);
    }

} else {

    echo "Category ID not found.";
}
?>