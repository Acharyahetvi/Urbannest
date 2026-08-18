<?php
include "../connect.php";

$message = "";

// Check subcategory ID
if (!isset($_GET['id'])) {
    die("Subcategory ID not found.");
}

$subcategory_id = $_GET['id'];


// Fetch existing subcategory
$sql = "SELECT * FROM sub_categories 
        WHERE subcategory_id = '$subcategory_id'";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Subcategory not found.");
}

$subcategory = mysqli_fetch_assoc($result);


// Update subcategory
if (isset($_POST['update'])) {

    $category_id = $_POST['category_id'];
    $subcategory_name = $_POST['subcategory_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Keep old photo by default
    $photo_name = $subcategory['photo'];


    // If a new photo was selected
    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != "") {

        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];

        // Upload folder
        $upload_folder = "../assets/images/subcategory/";

        // Create unique filename
        $photo_name = time() . "_" . $photo;

        // Move new photo
        move_uploaded_file(
            $photo_tmp,
            $upload_folder . $photo_name
        );
    }


    $update_sql = "UPDATE sub_categories SET
                    category_id = '$category_id',
                    subcategory_name = '$subcategory_name',
                    description = '$description',
                    status = '$status',
                    photo = '$photo_name'
                   WHERE subcategory_id = '$subcategory_id'";


    if (mysqli_query($con, $update_sql)) {

        $message = "Subcategory updated successfully!";

        // Fetch updated data
        $result = mysqli_query(
            $con,
            "SELECT * FROM sub_categories 
             WHERE subcategory_id = '$subcategory_id'"
        );

        $subcategory = mysqli_fetch_assoc($result);

    } else {

        $message = "Error updating subcategory: " . mysqli_error($con);
    }
}
?>


<!DOCTYPE html>
<html>

<head>

    <title>Edit Subcategory</title>

    <link rel="stylesheet" href="../assets/css/subcategory.css">

</head>


<body>

<div class="subcategory-container">

    <h2>Edit Subcategory</h2>


    <?php if ($message != "") { ?>

        <div class="success-message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <form method="POST" enctype="multipart/form-data">


        <!-- Select Category -->

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

                ?>

                    <option
                        value="<?php echo $row['category_id']; ?>"
                        <?php
                        if ($row['category_id'] == $subcategory['category_id']) {
                            echo "selected";
                        }
                        ?>
                    >

                        <?php
                        echo htmlspecialchars($row['category_name']);
                        ?>

                    </option>

                <?php } ?>

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
                value="<?php echo htmlspecialchars($subcategory['subcategory_name']); ?>"
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
            ><?php echo htmlspecialchars($subcategory['description']); ?></textarea>

        </div>


        <!-- Current Photo -->

        <div class="form-group">

            <label>
                Current Photo
            </label>


            <?php if (!empty($subcategory['photo'])) { ?>

                <br>

                <img
                    src="../assets/images/subcategory/<?php echo htmlspecialchars($subcategory['photo']); ?>"
                    alt="Subcategory Photo"
                    width="150"
                    height="100"
                >

            <?php } else { ?>

                <p>No photo uploaded.</p>

            <?php } ?>

        </div>


        <!-- Change Photo -->

        <div class="form-group">

            <label for="photo">
                Change Photo
            </label>


            <input
                type="file"
                name="photo"
                id="photo"
                accept="image/*"
            >


            <small>
                Leave empty if you want to keep the current photo.
            </small>

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

                <option
                    value="Active"
                    <?php
                    if ($subcategory['status'] == 'Active') {
                        echo "selected";
                    }
                    ?>
                >
                    Active
                </option>


                <option
                    value="Inactive"
                    <?php
                    if ($subcategory['status'] == 'Inactive') {
                        echo "selected";
                    }
                    ?>
                >
                    Inactive
                </option>

            </select>

        </div>


        <!-- Update Button -->

        <button
            type="submit"
            name="update"
            class="submit-btn"
        >
            Update Subcategory
        </button>


    </form>

</div>

</body>

</html>