<?php

require_once __DIR__ . "/../db_connection.php";

if (!isset($_GET['id'])) {
    die("Property ID not found.");
}

$property_id = $_GET['id'];

// Get property data
$sql = "SELECT * FROM properties WHERE property_id = '$property_id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("Property not found.");
}

$property = mysqli_fetch_assoc($result);


// Update property
if (isset($_POST['update'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE properties SET
        title = '$title',
        description = '$description',
        price = '$price',
        address = '$address',
        city_id = '$city_id',
        category_id = '$category_id',
        subcategory_id = '$subcategory_id',
        status = '$status'
        WHERE property_id = '$property_id'";

    if (mysqli_query($conn, $update_sql)) {

        echo "<script>
                alert('Property updated successfully!');
                window.location.href='index.php';
              </script>";
    } else {

        echo "Update Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Property</title>

    <!-- CSS Link -->
    <link rel="stylesheet" href="../assets/css/property_edit.css">

</head>

<body>

    <div class="container">

        <h2>Edit Property</h2>

        <form method="POST">

            <label>Property Title:</label>

            <input type="text"
                name="title"
                value="<?php echo htmlspecialchars($property['title'] ?? ''); ?>"
                required>


            <label>Description:</label>

            <textarea name="description" required><?php
                                                    echo htmlspecialchars($property['description'] ?? ''); ?>
                 </textarea>


            <label>Price:</label>

            <input type="number"
                name="price"
                value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>"
                required>


            <label>Address:</label>

            <textarea name="address" required><?php
                                                echo htmlspecialchars($property['address'] ?? '');
                                                ?></textarea>


            <label>City ID:</label>

            <input type="number"
                name="city_id"
                value="<?php echo htmlspecialchars($property['city_id'] ?? ''); ?>"
                required>


            <label>Category ID:</label>

            <input type="number"
                name="category_id"
                value="<?php echo htmlspecialchars($property['category_id'] ?? ''); ?>"
                required>


            <label>Subcategory ID:</label>

            <input type="number"
                name="subcategory_id"
                value="<?php echo htmlspecialchars($property['subcategory_id'] ?? ''); ?>"
                required>


            <label>Status:</label>

            <select name="status" required>

                <option value="Available"
                    <?php
                    if (($property['status'] ?? '') == 'Available') {
                        echo 'selected';
                    }
                    ?>>
                    Available
                </option>

                <option value="Sold"
                    <?php
                    if (($property['status'] ?? '') == 'Sold') {
                        echo 'selected';
                    }
                    ?>>
                    Sold
                </option>

                <option value="Pending"
                    <?php
                    if (($property['status'] ?? '') == 'Pending') {
                        echo 'selected';
                    }
                    ?>>
                    Pending
                </option>

            </select>


            <button type="submit" name="update">
                Update Property
            </button>

        </form>


        <a href="view.php" class="back">
            ← Back to Properties
        </a>

    </div>

</body>

</html>