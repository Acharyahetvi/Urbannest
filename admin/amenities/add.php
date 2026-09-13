<?php

require_once __DIR__ . "/../../db_connection.php";

$message = "";

if (isset($_POST['add'])) {

    $amenity_name = trim($_POST['amenity_name']);

    if ($amenity_name == "") {

        $message = "Please enter an amenity name.";
    } else {

        $sql = "INSERT INTO amenities (amenity_name)
                VALUES ('$amenity_name')";

        if (mysqli_query($con, $sql)) {

            $message = "Amenity added successfully!";
        } else {

            $message = "Error adding amenity: " . mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Amenity - UrbanNest</title>

    <!-- CSS -->


    <link rel="stylesheet" href="/urbannest/admin/assets/css-new/amenities.css">

</head>

<body>

    <h1>Add Amenity</h1>

    <?php if ($message != ""): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST">

        <div>

            <label for="amenity_name">
                Amenity Name
            </label>

            <input
                type="text"
                id="amenity_name"
                name="amenity_name"
                placeholder="Enter amenity name"
                required>

        </div>

        <br>

        <button
            type="submit"
            name="add">
            Add Amenity
        </button>

    </form>

    <br>

    <a href="view.php">
        Back to Amenities
    </a>

</body>

</html>