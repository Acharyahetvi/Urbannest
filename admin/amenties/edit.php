

<?php

require_once __DIR__ . '/../../includes/db.php';

$message = "";

// Check amenity ID
if (!isset($_GET['id'])) {
    die("Amenity ID not found.");
}

$amentiy_id = $_GET['id'];


// Fetch existing amenity
$sql = "SELECT * FROM amenities
        WHERE amentiy_id = '$amentiy_id'";

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

    $amentiy_name = trim($_POST['amentiy_name']);

    if ($amentiy_name == "") {

        $message = "Please enter an amenity name.";

    } else {

        $update_sql = "UPDATE amenities SET
                        amentiy_name = '$amentiy_name'
                       WHERE amentiy_id = '$amentiy_id'";

        if (mysqli_query($con, $update_sql)) {

            $message = "Amenity updated successfully!";

            // Fetch updated data
            $result = mysqli_query(
                $con,
                "SELECT * FROM amenities
                 WHERE amentiy_id = '$amentiy_id'"
            );

            $amenity = mysqli_fetch_assoc($result);

        } else {

            $message = "Error updating amenity: " . mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Amenity - UrbanNest</title>

</head>

<body>

    <h1>Edit Amenity</h1>


    <?php if ($message != ""): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST">

        <div>

            <label for="amentiy_name">
                Amenity Name
            </label>

            <input
                type="text"
                id="amentiy_name"
                name="amentiy_name"
                value="<?php echo htmlspecialchars($amenity['amentiy_name']); ?>"
                required
            >

        </div>


        <br>


        <button
            type="submit"
            name="update"
        >
            Update Amenity
        </button>

    </form>


    <br>


    <a href="view.php">
        Back to Amenities
    </a>

</body>

</html>