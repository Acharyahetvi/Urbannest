<?php

require_once __DIR__ . "/../../db_connection.php";

$message = "";

if (isset($_POST['save_property'])) {

    $user_id = $_POST['user_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $listing_type = $_POST['listing_type'];
    $price = $_POST['price'];
    $area_sqft = $_POST['area_sqft'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $furnishing = $_POST['furnishing'];
    $property_condition = $_POST['property_condition'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    if (
        $user_id == "" || $subcategory_id == "" || $title == "" ||
        $description == "" || $listing_type == "" || $price == "" ||
        $area_sqft == "" || $address == "" || $city == "" || $state == ""
    ) {

        $message = "Please fill all required fields.";
    } else {

        $sql = "INSERT INTO properties
        (user_id, subcategory_id, title, description, listing_type,
        price, area_sqft, bedrooms, bathrooms, furnishing,
        property_condition, address, city, state)
        VALUES
        ('$user_id', '$subcategory_id', '$title', '$description',
        '$listing_type', '$price', '$area_sqft', '$bedrooms',
        '$bathrooms', '$furnishing', '$property_condition',
        '$address', '$city', '$state')";

        if (mysqli_query($con, $sql)) {
            $message = "Property added successfully.";
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Property</title>

    <link rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

    <div class="container">

        <h2>Add Property</h2>

        <?php
        if ($message != "") {
            echo "<p class='message'>$message</p>";
        }
        ?>

        <form method="POST">

            <label>Property Owner</label>
            <select name="user_id" required>

                <option value="">Select Owner</option>

                <?php

                $sql = "SELECT user_id, full_name FROM users";
                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<option value='" . $row['user_id'] . "'>";
                    echo $row['full_name'];
                    echo "</option>";
                }

                ?>

            </select>


            <label>Property Type</label>
            <select name="subcategory_id" required>

                <option value="">Select Property Type</option>

                <?php

                $sql = "SELECT subcategory_id, subcategory_name 
                    FROM sub_categories";

                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<option value='" . $row['subcategory_id'] . "'>";
                    echo $row['subcategory_name'];
                    echo "</option>";
                }

                ?>

            </select>


            <label>Property Title</label>
            <input type="text" name="title" required>


            <label>Description</label>
            <textarea name="description" required></textarea>


            <label>Listing Type</label>
            <select name="listing_type" required>

                <option value="">Select</option>
                <option value="For Sale">For Sale</option>
                <option value="For Rent">For Rent</option>

            </select>


            <label>Price</label>
            <input type="number" name="price" required>


            <label>Area (sq.ft.)</label>
            <input type="number" name="area_sqft" required>


            <label>Bedrooms</label>
            <input type="number" name="bedrooms">


            <label>Bathrooms</label>
            <input type="number" name="bathrooms">


            <label>Furnishing</label>
            <select name="furnishing">

                <option value="">Select</option>
                <option value="Unfurnished">Unfurnished</option>
                <option value="Semi-Furnished">Semi-Furnished</option>
                <option value="Furnished">Furnished</option>

            </select>


            <label>Property Condition</label>
            <select name="property_condition">

                <option value="">Select</option>
                <option value="New">New</option>
                <option value="Ready to Move">Ready to Move</option>
                <option value="Under Construction">Under Construction</option>

            </select>


            <label>Address</label>
            <textarea name="address" required></textarea>


            <label>City</label>
            <input type="text" name="city" required>


            <label>State</label>
            <input type="text" name="state" required>


            <button type="submit" name="save_property">
                Add Property
            </button>

        </form>

    </div>

</body>

</html>