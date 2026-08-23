<?php
include "../connect.php";

$message = "";

if (isset($_POST["add_category"])) {

    $category_id = $_POST["category_id"];
    $subcategory_name = $_POST["subcategory_name"];
    $description = $_POST["description"];
    $status = $_POST["status"];

    // Photo
    $photo_name = "";

    if (isset($_FILES["photo"]) && $_FILES["photo"]["name"] != "") {

        $photo = $_FILES["photo"]["name"];
        $photo_tmp = $_FILES["photo"]["tmp_name"];

        // Upload folder
        $upload_folder = "../assets/images/subcategory/";

        // Create unique filename
        $photo_name = time() . "_" . $photo;

        // Move photo to folder
        move_uploaded_file(
            $photo_tmp,
            $upload_folder . $photo_name
        );
    }


    $sql = "INSERT INTO sub_categories
            (category_id, subcategory_name, description, status, photo)
            VALUES
            ('$category_id', '$subcategory_name', '$description', '$status', '$photo_name')";


    if (mysqli_query($con, $sql)) {

        $message = "Subcategory added successfully";

    } else {

        $message = "Error adding subcategory: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Subcategory</title>

    <link rel="stylesheet" href="../assets/css/subcategory.css">

</head>

<body>

<div class="subcategory-container">

    <h2>Add Subcategory</h2>


    <?php if ($message != "") { ?>

        <div class="success-message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <form method="POST" enctype="multipart/form-data">


        <!-- Category -->

        <div class="form-group">

            <label for="category">
                Select Category
            </label>

            <select
                name="category_id"
                id="category"
                required
            >

                <option value="">
                    -- Select Category --
                </option>

                <?php

                $fetch_category = mysqli_query(
                    $con,
                    "SELECT * FROM categories"
                );

                while ($row = mysqli_fetch_assoc($fetch_category)) {

                    echo "<option value='" . $row['category_id'] . "'>"
                        . htmlspecialchars($row['category_name'])
                        . "</option>";
                }

                ?>

            </select>

        </div>


        <!-- Subcategory Name -->

        <div class="form-group">

            <label for="subcategory_name">
                Subcategory Name
            </label>

            <input
                type="text"
                name="subcategory_name"
                id="subcategory_name"
                placeholder="Enter subcategory name"
                required
            >

        </div>


        <!-- Description -->

        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                name="description"
                id="description"
                placeholder="Enter description"
                required
            ></textarea>

        </div>


        <!-- Status -->

        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                name="status"
                id="status"
                required
            >

                <option value="">
                    -- Select Status --
                </option>

                <option value="Active">
                    Active
                </option>

                <option value="Inactive">
                    Inactive
                </option>

            </select>

        </div>


        <!-- Photo -->

        <div class="form-group">

            <label for="photo">
                Subcategory Photo
            </label>

            <input
                type="file"
                name="photo"
                id="photo"
                accept="image/*"
                required
            >

        </div>


        <!-- Submit -->

        <button
            type="submit"
            name="add_category"
            class="submit-btn"
        >
            Add Subcategory
        </button>


    </form>

</div>

</body>

</html>