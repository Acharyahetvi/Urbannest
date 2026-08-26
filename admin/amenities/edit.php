<?php

require_once __DIR__ . "/../../db_connection.php";

$message = "";

// Check amenity ID
if (!isset($_GET['id'])) {
    die("Amenity ID not found.");
}

$amenity_id = $_GET['id'];


// Fetch existing amenity
$sql = "SELECT * FROM amenities
        WHERE amenity_id = '$amenity_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Failed to fetch amenity: " . mysqli_error($con));
}

if (mysqli_num_rows($result) == 0) {
    die("Amenity not found.");
}

$amenity = mysqli_fetch_assoc($result);


// Update amenity
if (isset($_POST['update'])) {

    $amenity_name = trim($_POST['amenity_name']);

    if ($amenity_name == "") {

        $message = "Please enter an amenity name.";
    } else {

        $update_sql = "UPDATE amenities SET
                        amenity_name = '$amenity_name'
                       WHERE amenity_id = '$amenity_id'";

        if (mysqli_query($con, $update_sql)) {

            $message = "Amenity updated successfully!";

            // Fetch updated data
            $result = mysqli_query(
                $con,
                "SELECT * FROM amenities
                 WHERE amenity_id = '$amenity_id'"
            );

            $amenity = mysqli_fetch_assoc($result);
        } else {

            $message = "Error updating amenity: " . mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Amenity</title>

    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">
</head>

<body>

    <h1>Edit Amenity</h1>

    <form method="POST">

        <label>Amenity Name</label><br>

        <input type="text" name="amenity_name"
            value="<?php echo htmlspecialchars($amenity['amenity_name']); ?>">

        <br>

        <button type="submit">Update Amenity</button>

    </form>

    <a href="index.php">Back to Amenities</a>

</body>

</html>