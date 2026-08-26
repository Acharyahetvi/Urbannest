<?php

require_once __DIR__ . "/../../db_connection.php";

// Check amenity ID
if (!isset($_GET['id'])) {
    die("Amenity ID not found.");
}

$amenity_id = $_GET['id'];

// Delete amenity
$sql = "DELETE FROM amenities
        WHERE amenity_id = '$amenity_id'";

if (mysqli_query($con, $sql)) {

    header("Location: view.php");
    exit();
} else {

    echo "Error deleting amenity: " . mysqli_error($con);
}
