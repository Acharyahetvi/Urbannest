<?php
include "../connect.php";

$message = "";

if (isset($_POST["add_property"])) {

    $user_id = $_POST["user_id"];
    $subcategory_id = $_POST["subcategory_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $listing_type = $_POST["listing_type"];
    $price = $_POST["price"];
    $area_sqft = $_POST["area_sqft"];
    $bathrooms = $_POST["bathrooms"];
    $furnishing = $_POST["furnishing"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $status = $_POST["status"];

    $sql = "INSERT INTO properties
            (user_id, subcategory_id, title, description, listing_type, price,
             area_sqft, bathrooms, furnishing, address, city, state, status)
            VALUES
            ('$user_id', '$subcategory_id', '$title', '$description',
             '$listing_type', '$price', '$area_sqft', '$bathrooms',
             '$furnishing', '$address', '$city', '$state', '$status')";

    if (mysqli_query($con, $sql)) {
        $message = "Property added successfully";
    } else {
        $message = "Error adding property: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="../assets/css/property.css">
</head>

<body>

    <h2>Add Property</h2>

    <?php if ($message != "") { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">

        <!-- User -->
        <div>
            <label for="user_id">Select User</label>

            <select name="user_id" id="user_id" required>
                <option value="">-- Select User --</option>

                <?php
                $fetch_users = mysqli_query($con, "SELECT * FROM users");

                while ($row = mysqli_fetch_array($fetch_users)) {
                    echo "<option value='" . $row['user_id'] . "'>"
                        . $row['full_name'] .
                        "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Subcategory -->
        <div>
            <label for="subcategory_id">Select Subcategory</label>

            <select name="subcategory_id" id="subcategory_id" required>
                <option value="">-- Select Subcategory --</option>

                <?php
                $fetch_subcategories = mysqli_query(
                    $con,
                    "SELECT * FROM sub_categories"
                );

                while ($row = mysqli_fetch_array($fetch_subcategories)) {
                    echo "<option value='" . $row['subcategory_id'] . "'>"
                        . $row['subcategory_name'] .
                        "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Title -->
        <div>
            <label for="title">Property Title</label>

            <input
                type="text"
                name="title"
                id="title"
                placeholder="Enter property title"
                required
            >
        </div>

        <!-- Description -->
        <div>
            <label for="description">Description</label>

            <textarea
                name="description"
                id="description"
                placeholder="Enter property description"
                required
            ></textarea>
        </div>

        <!-- Listing Type -->
        <div>
            <label for="listing_type">Listing Type</label>

            <select name="listing_type" id="listing_type" required>
                <option value="">-- Select Listing Type --</option>
                <option value="Buy">Buy</option>
                <option value="Rent">Rent</option>
                <option value="Sell">Sell</option>
            </select>
        </div>

        <!-- Price -->
        <div>
            <label for="price">Price</label>

            <input
                type="number"
                name="price"
                id="price"
                placeholder="Enter price"
                required
            >
        </div>

        <!-- Area -->
        <div>
            <label for="area_sqft">Area (sq.ft.)</label>

            <input
                type="number"
                name="area_sqft"
                id="area_sqft"
                placeholder="Enter area in sq.ft."
                required
            >
        </div>

        <!-- Bathrooms -->
        <div>
            <label for="bathrooms">Bathrooms</label>

            <input
                type="number"
                name="bathrooms"
                id="bathrooms"
                placeholder="Enter number of bathrooms"
                required
            >
        </div>

        <!-- Furnishing -->
        <div>
            <label for="furnishing">Furnishing</label>

            <select name="furnishing" id="furnishing" required>
                <option value="">-- Select Furnishing --</option>
                <option value="Furnished">Furnished</option>
                <option value="Semi-Furnished">Semi-Furnished</option>
                <option value="Unfurnished">Unfurnished</option>
            </select>
        </div>

        <!-- Address -->
        <div>
            <label for="address">Address</label>

            <textarea
                name="address"
                id="address"
                placeholder="Enter property address"
                required
            ></textarea>
        </div>

        <!-- City -->
        <div>
            <label for="city">City</label>

            <input
                type="text"
                name="city"
                id="city"
                placeholder="Enter city"
                required
            >
        </div>

        <!-- State -->
        <div>
            <label for="state">State</label>

            <input
                type="text"
                name="state"
                id="state"
                placeholder="Enter state"
                required
            >
        </div>

        <!-- Status -->
        <div>
            <label for="status">Status</label>

            <select name="status" id="status" required>
                <option value="">-- Select Status --</option>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
                <option value="Rented">Rented</option>
            </select>
        </div>

        <br>

        <button type="submit" name="add_property">
            Add Property
        </button>

    </form>

</body>

</html>