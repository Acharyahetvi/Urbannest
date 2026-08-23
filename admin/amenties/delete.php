<?php

require_once __DIR__ . '/../../includes/db.php';

// Check amenity ID
if (!isset($_GET['id'])) {
    die("Amenity ID not found.");
}

$amentiy_id = $_GET['id'];

// Delete amenity
$sql = "DELETE FROM amenities
        WHERE amentiy_id = '$amentiy_id'";

if (mysqli_query($con, $sql)) {

    header("Location: view.php");
    exit();

} else {

    echo "Error deleting amenity: " . mysqli_error($con);
}

?>