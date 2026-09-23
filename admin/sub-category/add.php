<?php

require_once __DIR__ . "/../db_connection.php";

$message = "";
$message_type = "";


// =====================================
// ADD SUBCATEGORY
// =====================================

if (isset($_POST["add_category"])) {

    $category_id = (int) ($_POST["category_id"] ?? 0);
    $subcategory_name = trim($_POST["subcategory_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? "";


    // =====================================
    // VALIDATION
    // =====================================

    if ($category_id <= 0) {

        $message = "Please select a category.";
        $message_type = "error";
    } elseif ($subcategory_name == "") {

        $message = "Subcategory name is required.";
        $message_type = "error";
    } elseif (!in_array($status, ["Active", "Inactive"])) {

        $message = "Please select a valid status.";
        $message_type = "error";
    } else {

        // Check category exists

        $category_check = mysqli_query(
            $con,
            "SELECT category_id
             FROM categories
             WHERE category_id = $category_id"
        );


        if (!$category_check || mysqli_num_rows($category_check) == 0) {

            $message = "Selected category does not exist.";
            $message_type = "error";
        } else {

            // =====================================
            // PHOTO UPLOAD
            // =====================================

            $photo_name = "";

            if (
                isset($_FILES["photo"]) &&
                $_FILES["photo"]["error"] != UPLOAD_ERR_NO_FILE
            ) {

                if ($_FILES["photo"]["error"] == UPLOAD_ERR_OK) {

                    $photo_tmp = $_FILES["photo"]["tmp_name"];
                    $photo_size = $_FILES["photo"]["size"];

                    $extension = strtolower(
                        pathinfo(
                            $_FILES["photo"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );

                    $allowed_extensions = [
                        "jpg",
                        "jpeg",
                        "png",
                        "webp"
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


                        // Create folder if not exists

                        if (!is_dir($upload_folder)) {

                            mkdir($upload_folder, 0777, true);
                        }


                        // Unique file name

                        $photo_name = uniqid("subcategory_") .
                            "." . $extension;


                        // Move uploaded photo

                        if (
                            !move_uploaded_file(
                                $photo_tmp,
                                $upload_folder . $photo_name
                            )
                        ) {

                            $message = "Photo upload failed.";
                            $message_type = "error";
                        }
                    }
                } else {

                    $message = "Error uploading photo.";
                    $message_type = "error";
                }
            } else {

                $message = "Please select a subcategory photo.";
                $message_type = "error";
            }


            // =====================================
            // INSERT INTO DATABASE
            // =====================================

            if ($message == "") {

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

                $photo_name = mysqli_real_escape_string(
                    $con,
                    $photo_name
                );


                $sql = "INSERT INTO sub_categories
                        (
                            category_id,
                            subcategory_name,
                            description,
                            status,
                            photo
                        )
                        VALUES
                        (
                            '$category_id',
                            '$subcategory_name',
                            '$description',
                            '$status',
                            '$photo_name'
                        )";


                if (mysqli_query($con, $sql)) {

                    $message = "Subcategory added successfully!";
                    $message_type = "success";
                } else {

                    $message = "Error adding subcategory: " .
                        mysqli_error($con);

                    $message_type = "error";
                }
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

    <title>Add Subcategory | UrbanNest</title>


    <!-- Bootstrap CSS -->

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

        <div class="subcategory-add-page">


            <!-- PAGE HEADER -->

            <div class="edit-page-header">

                <div>

                    <div class="page-eyebrow">
                        SUB CATEGORY MANAGEMENT
                    </div>

                    <h1>Add Subcategory</h1>

                    <p>Create a new property subcategory</p>

                </div>


                <a href="view.php" class="back-btn">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Subcategories

                </a>

            </div>



            <!-- MESSAGE -->

            <?php if ($message != "") { ?>

                <div class="<?php echo $message_type == "success"
                                ? "success-message"
                                : "error-message"; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php } ?>



            <!-- FORM CARD -->

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


                            if ($fetch_category) {

                                while (
                                    $row = mysqli_fetch_assoc(
                                        $fetch_category
                                    )
                                ) {

                                    $selected = (
                                        isset($_POST["category_id"]) &&
                                        $_POST["category_id"] ==
                                        $row["category_id"]
                                    ) ? "selected" : "";

                            ?>

                                    <option
                                        value="<?php echo (int) $row["category_id"]; ?>"
                                        <?php echo $selected; ?>>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["category_name"]
                                        );
                                        ?>

                                    </option>

                            <?php
                                }
                            }
                            ?>

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
                            placeholder="Enter subcategory name"
                            value="<?php echo htmlspecialchars(
                                        $_POST["subcategory_name"] ?? ""
                                    ); ?>"
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
                                            $_POST["description"] ?? ""
                                        );
                                        ?></textarea>

                    </div>



                    <!-- PHOTO -->

                    <div class="form-group">

                        <label for="photo">
                            Subcategory Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            accept=".jpg,.jpeg,.png,.webp"
                            required>

                        <small>
                            Allowed formats: JPG, JPEG, PNG, WEBP.
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

                            <option value="">
                                -- Select Status --
                            </option>

                            <option
                                value="Active"
                                <?php
                                echo (
                                    ($_POST["status"] ?? "") == "Active"
                                ) ? "selected" : "";
                                ?>>

                                Active

                            </option>

                            <option
                                value="Inactive"
                                <?php
                                echo (
                                    ($_POST["status"] ?? "") == "Inactive"
                                ) ? "selected" : "";
                                ?>>

                                Inactive

                            </option>

                        </select>

                    </div>



                    <!-- BUTTONS -->

                    <div class="edit-form-actions">

                        <button
                            type="submit"
                            name="add_category"
                            class="submit-btn">

                            <i class="fa-solid fa-plus"></i>

                            Add Subcategory

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