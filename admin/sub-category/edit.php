<?php

require_once __DIR__ . "/../../db_connection.php";

$message = "";
$message_type = "";


// Check Subcategory ID

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Subcategory ID not found.");
}

$subcategory_id = (int) $_GET['id'];


// Fetch Existing Subcategory

$sql = "SELECT *
        FROM sub_categories
        WHERE subcategory_id = $subcategory_id";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

if (mysqli_num_rows($result) == 0) {
    die("Subcategory not found.");
}

$subcategory = mysqli_fetch_assoc($result);


// Update Subcategory

if (isset($_POST['update'])) {

    $category_id = (int) ($_POST['category_id'] ?? 0);

    $subcategory_name = trim($_POST['subcategory_name'] ?? '');

    $description = trim($_POST['description'] ?? '');

    $status = $_POST['status'] ?? '';

    $photo_name = $subcategory['photo'];


    // Validate Category

    $category_check = mysqli_query(
        $con,
        "SELECT category_id
         FROM categories
         WHERE category_id = $category_id"
    );


    if ($category_id <= 0 || mysqli_num_rows($category_check) == 0) {

        $message = "Please select a valid category.";
        $message_type = "error";
    } elseif ($subcategory_name == "") {

        $message = "Subcategory name is required.";
        $message_type = "error";
    } elseif (!in_array($status, ['Active', 'Inactive'])) {

        $message = "Please select a valid status.";
        $message_type = "error";
    } else {

        // Escape Form Values

        $subcategory_name = mysqli_real_escape_string(
            $con,
            $subcategory_name
        );

        $description = mysqli_real_escape_string(
            $con,
            $description
        );

        $status = mysqli_real_escape_string(
            $con,
            $status
        );


        // Upload New Photo if Selected

        if (
            isset($_FILES['photo']) &&
            $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES['photo']['error'] == UPLOAD_ERR_OK) {

                $photo_tmp = $_FILES['photo']['tmp_name'];

                $photo_size = $_FILES['photo']['size'];

                $extension = strtolower(
                    pathinfo(
                        $_FILES['photo']['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];


                if ($photo_size > 5 * 1024 * 1024) {

                    $message = "Photo must be less than 5 MB.";
                    $message_type = "error";
                } elseif (
                    !in_array($extension, $allowed_extensions) ||
                    getimagesize($photo_tmp) === false
                ) {

                    $message = "Please upload a valid JPG, PNG or WEBP image.";
                    $message_type = "error";
                } else {

                    $upload_folder = __DIR__ .
                        "/../assets/images/subcategory/";


                    if (!is_dir($upload_folder)) {
                        mkdir($upload_folder, 0777, true);
                    }


                    $new_photo_name = "subcategory_" .
                        $subcategory_id . "_" .
                        uniqid() . "." . $extension;


                    if (
                        move_uploaded_file(
                            $photo_tmp,
                            $upload_folder . $new_photo_name
                        )
                    ) {

                        $photo_name = $new_photo_name;
                    } else {

                        $message = "Photo upload failed.";
                        $message_type = "error";
                    }
                }
            } else {

                $message = "Error uploading photo.";
                $message_type = "error";
            }
        }


        // Update Database

        if ($message == "") {

            $photo_name = mysqli_real_escape_string(
                $con,
                $photo_name ?? ''
            );


            $update_sql = "UPDATE sub_categories SET
                            category_id = $category_id,
                            subcategory_name = '$subcategory_name',
                            description = '$description',
                            status = '$status',
                            photo = '$photo_name'
                           WHERE subcategory_id = $subcategory_id";


            if (mysqli_query($con, $update_sql)) {

                $message = "Subcategory updated successfully!";
                $message_type = "success";


                // Refresh Updated Data

                $result = mysqli_query(
                    $con,
                    "SELECT *
                     FROM sub_categories
                     WHERE subcategory_id = $subcategory_id"
                );

                $subcategory = mysqli_fetch_assoc($result);
            } else {

                $message = "Update Error: " . mysqli_error($con);
                $message_type = "error";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Subcategory - UrbanNest</title>


    <!-- Bootstrap -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">


    <!-- Common CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">


    <!-- Subcategory CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/sub-category.css">


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body class="admin-page">


    <!-- HEADER -->

    <?php include __DIR__ . "/../includes/header.php"; ?>


    <!-- SIDEBAR -->

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="subcategory-edit-page">


            <!-- PAGE HEADER -->

            <div class="edit-page-header">

                <div>

                    <div class="page-eyebrow">
                        SUB CATEGORY MANAGEMENT
                    </div>

                    <h1>Edit Subcategory</h1>

                    <p>Update subcategory information</p>

                </div>


                <a href="view.php" class="back-btn">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Subcategories

                </a>

            </div>



            <!-- MESSAGE -->

            <?php if ($message != "") { ?>

                <div class="<?php echo $message_type == 'success'
                                ? 'success-message'
                                : 'error-message'; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php } ?>



            <!-- EDIT FORM CARD -->

            <div class="subcategory-edit-card">

                <form method="POST"
                    enctype="multipart/form-data"
                    class="subcategory-edit-form">


                    <!-- CATEGORY -->

                    <div class="form-group">

                        <label for="category_id">
                            Select Category
                        </label>

                        <select
                            name="category_id"
                            id="category_id"
                            required>

                            <option value="">
                                -- Select Category --
                            </option>

                            <?php

                            $fetch_category = mysqli_query(
                                $con,
                                "SELECT category_id, category_name
                                 FROM categories
                                 ORDER BY category_name ASC"
                            );


                            while ($row = mysqli_fetch_assoc($fetch_category)) {

                                $selected = (
                                    $row['category_id'] ==
                                    $subcategory['category_id']
                                ) ? 'selected' : '';

                            ?>

                                <option
                                    value="<?php echo (int)$row['category_id']; ?>"
                                    <?php echo $selected; ?>>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['category_name']
                                    );
                                    ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>



                    <!-- SUBCATEGORY NAME -->

                    <div class="form-group">

                        <label for="subcategory_name">
                            Subcategory Name
                        </label>

                        <input
                            type="text"
                            name="subcategory_name"
                            id="subcategory_name"
                            value="<?php echo htmlspecialchars(
                                        $subcategory['subcategory_name']
                                    ); ?>"
                            placeholder="Enter subcategory name"
                            required>

                    </div>



                    <!-- DESCRIPTION -->

                    <div class="form-group">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            placeholder="Enter description"
                            required><?php
                                        echo htmlspecialchars(
                                            $subcategory['description']
                                        );
                                        ?></textarea>

                    </div>



                    <!-- CURRENT PHOTO -->

                    <div class="form-group">

                        <label>Current Photo</label>

                        <?php if (!empty($subcategory['photo'])) { ?>

                            <img
                                src="/urbannest/admin/assets/images/subcategory/<?php
                                                                                echo htmlspecialchars(
                                                                                    $subcategory['photo']
                                                                                );
                                                                                ?>"
                                alt="Current Subcategory Photo"
                                class="current-subcategory-photo">

                        <?php } else { ?>

                            <p class="no-current-photo">
                                No photo uploaded.
                            </p>

                        <?php } ?>

                    </div>



                    <!-- CHANGE PHOTO -->

                    <div class="form-group">

                        <label for="photo">
                            Change Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            accept=".jpg,.jpeg,.png,.webp">

                        <small>
                            Leave empty to keep the current photo.
                            Maximum size: 5 MB.
                        </small>

                    </div>



                    <!-- STATUS -->

                    <div class="form-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            required>

                            <option
                                value="Active"
                                <?php
                                if ($subcategory['status'] == 'Active') {
                                    echo 'selected';
                                }
                                ?>>

                                Active

                            </option>

                            <option
                                value="Inactive"
                                <?php
                                if ($subcategory['status'] == 'Inactive') {
                                    echo 'selected';
                                }
                                ?>>

                                Inactive

                            </option>

                        </select>

                    </div>



                    <!-- BUTTONS -->

                    <div class="edit-form-actions">

                        <button
                            type="submit"
                            name="update"
                            class="submit-btn">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Update Subcategory

                        </button>


                        <a href="view.php" class="cancel-btn">

                            Cancel

                        </a>

                    </div>


                </form>

            </div>

        </div>

    </main>


    <!-- FOOTER -->

    <?php include __DIR__ . "/../includes/footer.php"; ?>


</body>

</html>