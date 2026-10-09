<?php

require_once __DIR__ . '/../db_connection.php';

/*
|--------------------------------------------------------------------------
| Fetch Users
|--------------------------------------------------------------------------
*/

$users = mysqli_query(
    $con,
    "SELECT user_id, full_name
     FROM users
     ORDER BY full_name ASC"
);

if (!$users) {
    die(
        "Failed to fetch users: " .
        mysqli_error($con)
    );
}


/*
|--------------------------------------------------------------------------
| Fetch Categories
|--------------------------------------------------------------------------
*/

$categories = mysqli_query(
    $con,
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name ASC"
);

if (!$categories) {
    die(
        "Failed to fetch categories: " .
        mysqli_error($con)
    );
}


/*
|--------------------------------------------------------------------------
| Fetch Subcategories
|--------------------------------------------------------------------------
*/

$subcategories = [];

$subcategory_result = mysqli_query(
    $con,
    "SELECT subcategory_id, subcategory_name, category_id
     FROM sub_categories
     ORDER BY subcategory_name ASC"
);

if (!$subcategory_result) {
    die(
        "Failed to fetch subcategories: " .
        mysqli_error($con)
    );
}

while (
    $row =
        mysqli_fetch_assoc(
            $subcategory_result
        )
) {

    $subcategories[] =
        $row;

}


/*
|--------------------------------------------------------------------------
| Fetch Amenities
|--------------------------------------------------------------------------
*/

$amenities = [];

$amenity_result = mysqli_query(
    $con,
    "SELECT amenity_id, amenity_name
     FROM amenities
     ORDER BY amenity_id ASC"
);

if (!$amenity_result) {
    die(
        "Failed to fetch amenities: " .
        mysqli_error($con)
    );
}

while (
    $row =
        mysqli_fetch_assoc(
            $amenity_result
        )
) {

    $amenities[] =
        $row;

}

/*
|--------------------------------------------------------------------------
| SAVE PROPERTY
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["save_property"])
) {

    /*
    |--------------------------------------------------------------------------
    | Basic Property Data
    |--------------------------------------------------------------------------
    */

    $user_id =
        isset($_POST["user_id"])
            ? (int) $_POST["user_id"]
            : 0;

    $subcategory_id =
        isset($_POST["subcategory_id"])
            ? (int) $_POST["subcategory_id"]
            : 0;

    $title =
        trim($_POST["title"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $listing_type =
        trim($_POST["listing_type"] ?? "");

    $price =
        isset($_POST["price"])
            ? (float) $_POST["price"]
            : 0;

    $area_sqft =
        isset($_POST["area_sqft"])
            ? (float) $_POST["area_sqft"]
            : 0;

    $property_condition =
        trim($_POST["property_condition"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Dynamic Property Details
    |--------------------------------------------------------------------------
    */

    $bedrooms =
        isset($_POST["bedrooms"]) &&
        $_POST["bedrooms"] !== ""
            ? (int) $_POST["bedrooms"]
            : null;

    $bathrooms =
        isset($_POST["bathrooms"]) &&
        $_POST["bathrooms"] !== ""
            ? (int) $_POST["bathrooms"]
            : null;

    $furnishing =
        trim($_POST["furnishing"] ?? "");

    $floor_number =
        isset($_POST["floor_number"]) &&
        $_POST["floor_number"] !== ""
            ? (int) $_POST["floor_number"]
            : null;

    $total_floors =
        isset($_POST["total_floors"]) &&
        $_POST["total_floors"] !== ""
            ? (int) $_POST["total_floors"]
            : null;

    $plot_length =
        isset($_POST["plot_length"]) &&
        $_POST["plot_length"] !== ""
            ? (float) $_POST["plot_length"]
            : null;

    $plot_width =
        isset($_POST["plot_width"]) &&
        $_POST["plot_width"] !== ""
            ? (float) $_POST["plot_width"]
            : null;


    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    $address =
        trim($_POST["address"] ?? "");

    $city =
        trim($_POST["city"] ?? "");

    $state =
        trim($_POST["state"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Basic Validation
    |--------------------------------------------------------------------------
    */

    if (
        $user_id <= 0 ||
        $subcategory_id <= 0 ||
        $title === "" ||
        $description === "" ||
        $listing_type === "" ||
        $price <= 0 ||
        $area_sqft <= 0 ||
        $address === "" ||
        $city === "" ||
        $state === ""
    ) {

        die(
            "Please complete all required property information."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Start Database Transaction
    |--------------------------------------------------------------------------
    */

    mysqli_begin_transaction($con);


    try {

        /*
        |--------------------------------------------------------------------------
        | Insert Property
        |--------------------------------------------------------------------------
        */

        $property_sql = "
            INSERT INTO properties
            (
                user_id,
                subcategory_id,
                title,
                description,
                listing_type,
                price,
                area_sqft,
                bedrooms,
                bathrooms,
                furnishing,
                floor_number,
                total_floors,
                plot_length,
                plot_width,
                address,
                city,
                state,
                property_condition
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";


        $property_stmt =
            mysqli_prepare(
                $con,
                $property_sql
            );


        if (!$property_stmt) {

            throw new Exception(
                "Could not prepare property query."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Bind Property Values
        |--------------------------------------------------------------------------
        */
            mysqli_stmt_bind_param(
            $property_stmt,
            "iisssddiisiiddssss",
            $user_id,
            $subcategory_id,
            $title,
            $description,
            $listing_type,
            $price,
            $area_sqft,
            $bedrooms,
            $bathrooms,
            $furnishing,
            $floor_number,
            $total_floors,
            $plot_length,
            $plot_width,
            $address,
            $city,
            $state,
            $property_condition
        );


        if (
            !mysqli_stmt_execute(
                $property_stmt
            )
        ) {

            throw new Exception(
                mysqli_stmt_error(
                    $property_stmt
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Get Newly Created Property ID
        |--------------------------------------------------------------------------
        */

        $property_id =
            mysqli_insert_id($con);


        mysqli_stmt_close(
            $property_stmt
        );


        /*
        |--------------------------------------------------------------------------
        | Save Amenities
        |--------------------------------------------------------------------------
        */

        if (
            isset($_POST["amenities"]) &&
            is_array($_POST["amenities"])
        ) {

            $amenity_sql = "
                INSERT INTO property_amenities
                (
                    property_id,
                    amenity_id
                )
                VALUES
                (
                    ?,
                    ?
                )
            ";


            $amenity_stmt =
                mysqli_prepare(
                    $con,
                    $amenity_sql
                );


            if (!$amenity_stmt) {

                throw new Exception(
                    "Could not prepare amenity query."
                );

            }


            foreach (
                $_POST["amenities"]
                as $amenity_id
            ) {

                $amenity_id =
                    (int) $amenity_id;


                if (
                    $amenity_id <= 0
                ) {

                    continue;

                }


                mysqli_stmt_bind_param(
                    $amenity_stmt,
                    "ii",
                    $property_id,
                    $amenity_id
                );


                if (
                    !mysqli_stmt_execute(
                        $amenity_stmt
                    )
                ) {

                    throw new Exception(
                        mysqli_stmt_error(
                            $amenity_stmt
                        )
                    );

                }

            }


            mysqli_stmt_close(
                $amenity_stmt
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Other Amenities
        |--------------------------------------------------------------------------
        |
        | The current property_amenities table only stores amenity IDs.
        | Therefore Other Amenities cannot be stored there yet.
        |
        | We will handle this separately later if needed.
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Upload Property Images
        |--------------------------------------------------------------------------
        */

        $upload_directory =
            "../assets/images/property/property1/";


        if (
            !is_dir(
                $upload_directory
            )
        ) {

            if (
                !mkdir(
                    $upload_directory,
                    0777,
                    true
                )
            ) {

                throw new Exception(
                    "Could not create property image directory."
                );

            }

        }


        if (
            isset(
                $_FILES["property_photos"]
            ) &&
            isset(
                $_FILES["property_photos"]["name"]
            ) &&
            is_array(
                $_FILES["property_photos"]["name"]
            )
        ) {

            $image_sql = "
                INSERT INTO property_images
                (
                    property_id,
                    image_path,
                    is_main
                )
                VALUES
                (
                    ?,
                    ?,
                    ?
                )
            ";


            $image_stmt =
                mysqli_prepare(
                    $con,
                    $image_sql
                );


            if (!$image_stmt) {

                throw new Exception(
                    "Could not prepare image query."
                );

            }


            $photo_count =
                count(
                    $_FILES[
                        "property_photos"
                    ]["name"]
                );


            for (
                $i = 0;
                $i < $photo_count;
                $i++
            ) {

                if (
                    $_FILES[
                        "property_photos"
                    ]["error"][$i]
                    !== UPLOAD_ERR_OK
                ) {

                    continue;

                }


                $original_name =
                    $_FILES[
                        "property_photos"
                    ]["name"][$i];


                $tmp_name =
                    $_FILES[
                        "property_photos"
                    ]["tmp_name"][$i];


                /*
                |--------------------------------------------------------------------------
                | Check Image
                |--------------------------------------------------------------------------
                */

                $image_info =
                    getimagesize(
                        $tmp_name
                    );


                if (
                    $image_info === false
                ) {

                    throw new Exception(
                        "One of the uploaded files is not a valid image."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Allowed Extensions
                |--------------------------------------------------------------------------
                */

                $extension =
                    strtolower(
                        pathinfo(
                            $original_name,
                            PATHINFO_EXTENSION
                        )
                    );


                $allowed_extensions = [
                    "jpg",
                    "jpeg",
                    "png"
                ];


                if (
                    !in_array(
                        $extension,
                        $allowed_extensions,
                        true
                    )
                ) {

                    throw new Exception(
                        "Only JPG, JPEG and PNG images are allowed."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Generate Unique File Name
                |--------------------------------------------------------------------------
                */

                $new_filename =
                    "property_" .
                    $property_id .
                    "_" .
                    uniqid() .
                    "." .
                    $extension;


                $destination =
                    $upload_directory .
                    $new_filename;


                /*
                |--------------------------------------------------------------------------
                | Move Image
                |--------------------------------------------------------------------------
                */

                if (
                    !move_uploaded_file(
                        $tmp_name,
                        $destination
                    )
                ) {

                    throw new Exception(
                        "Could not upload property image."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | First Image = Main Image
                |--------------------------------------------------------------------------
                */

                $is_main =
                    ($i === 0)
                        ? 1
                        : 0;


                /*
                |--------------------------------------------------------------------------
                | Store Image Record
                |--------------------------------------------------------------------------
                */

                $image_path =
                    "assets/images/property/property1/" .
                    $new_filename;


                mysqli_stmt_bind_param(
                    $image_stmt,
                    "isi",
                    $property_id,
                    $image_path,
                    $is_main
                );


                if (
                    !mysqli_stmt_execute(
                        $image_stmt
                    )
                ) {

                    throw new Exception(
                        mysqli_stmt_error(
                            $image_stmt
                        )
                    );

                }

            }


            mysqli_stmt_close(
                $image_stmt
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Everything Successful
        |--------------------------------------------------------------------------
        */

        mysqli_commit(
            $con
        );


        echo "
            <script>
                alert(
                    'Property added successfully!'
                );

                window.location.href =
                    'add.php';
            </script>
        ";

        exit;


    } catch (
        Exception $e
    ) {

        /*
        |--------------------------------------------------------------------------
        | Roll Back Database Changes
        |--------------------------------------------------------------------------
        */

        mysqli_rollback(
            $con
        );


        die(
            "Property could not be saved: " .
            htmlspecialchars(
                $e->getMessage()
            )
        );

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Property | UrbanNest</title>

    <style>
/* =========================================================
   UrbanNest - Add Property
   ========================================================= */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f6f8;
    color: #172033;
}


/* =========================================================
   MAIN CONTAINER
   ========================================================= */

.property-container {
    width: min(1100px, 92%);
    margin: 45px auto;
    background: #ffffff;
    border-radius: 14px;
    padding: 35px 45px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}


/* =========================================================
   PAGE HEADER
   ========================================================= */

.page-header {
    text-align: center;
    margin-bottom: 35px;
}

.page-header h2 {
    margin: 0 0 8px;
    font-size: 30px;
    font-weight: 600;
    color: #14213d;
}

.page-header p {
    margin: 0;
    color: #737b89;
    font-size: 14px;
}


/* =========================================================
   STEPPER
   ========================================================= */

.property-stepper {
    display: flex;
    align-items: flex-start;
    width: 100%;
    margin-bottom: 45px;
}


/* Individual step */

.property-stepper .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 75px;
    position: relative;
}


/* Circle */

.step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #e5e7eb;
    color: #6b7280;

    font-size: 14px;
    font-weight: 600;

    border: 2px solid #e5e7eb;

    transition: 0.2s ease;
}


/* Step label */

.property-stepper .step span {
    margin-top: 9px;

    font-size: 12px;
    font-weight: 500;

    color: #737b89;

    white-space: nowrap;
}


/* Active step */

.property-stepper .step.active .step-circle {
    background: #14213d;
    border-color: #14213d;
    color: #ffffff;
}

.property-stepper .step.active span {
    color: #14213d;
    font-weight: 600;
}


/* Completed step */

.property-stepper .step.completed .step-circle {
    background: #d4a72c;
    border-color: #d4a72c;
    color: #ffffff;
}

.property-stepper .step.completed span {
    color: #14213d;
}


/* Connecting line */

.step-line {
    flex: 1;
    height: 2px;

    background: #e5e7eb;

    margin-top: 19px;
    min-width: 20px;
}


/* =========================================================
   PROPERTY STEPS
   ========================================================= */

/*
   Important:
   Only the active section is displayed.
*/

.property-step {
    display: none;
}

.property-step.active {
    display: block;
}


/* =========================================================
   STEP CONTENT
   ========================================================= */

.step-content {
    max-width: 720px;
    margin: 0 auto;
}


/* Step heading */

.step-heading {
    margin-bottom: 30px;
}

.step-number {
    display: inline-block;

    margin-bottom: 10px;

    font-size: 12px;
    font-weight: 600;

    color: #d4a72c;

    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.step-heading h3 {
    margin: 0 0 8px;

    font-size: 23px;
    font-weight: 600;

    color: #14213d;
}

.step-heading p {
    margin: 0;

    color: #737b89;
    font-size: 14px;
    line-height: 1.6;
}


/* =========================================================
   FORM GROUP
   ========================================================= */

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;

    margin-bottom: 8px;

    font-size: 14px;
    font-weight: 600;

    color: #263247;
}


/* Select */

.form-group select,
.form-group input,
.form-group textarea {
    width: 100%;

    border: 1px solid #d9dde5;
    border-radius: 8px;

    padding: 13px 14px;

    font-family: inherit;
    font-size: 14px;

    background: #ffffff;
    color: #172033;

    outline: none;

    transition: 0.2s ease;
}


/* Focus */

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus {
    border-color: #14213d;

    box-shadow:
        0 0 0 3px rgba(20, 33, 61, 0.08);
}


/* Textarea */

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}


/* =========================================================
   BUTTONS
   ========================================================= */


  .step-actions {
    display: flex !important;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;

    margin-top: 35px;
    width: 100%;
    min-height: 45px;
    visibility: visible !important;
    opacity: 1 !important;
}
.step-actions .next-btn,
.step-actions .back-btn,
.step-actions .save-btn {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}


/* Back */

.back-btn,
.next-btn,
.save-btn {
    border: none;
    border-radius: 8px;

    padding: 12px 24px;

    font-family: inherit;
    font-size: 14px;
    font-weight: 600;

    cursor: pointer;

    transition: 0.2s ease;
}


/* Continue / Save */

.next-btn,
.save-btn {
    background: #14213d;
    color: #ffffff;
}

.next-btn:hover,
.save-btn:hover {
    background: #0e172b;
}


/* Back */

.back-btn {
    background: #eef0f3;
    color: #263247;
}

.back-btn:hover {
    background: #e1e4e8;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 850px) {

    .property-container {
        width: 95%;
        padding: 30px 25px;
    }

    .property-stepper {
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .property-stepper .step {
        min-width: 70px;
    }

    .step-line {
        min-width: 15px;
    }

}


@media (max-width: 600px) {

    .property-container {
        margin: 20px auto;
        padding: 25px 18px;
    }

    .page-header h2 {
        font-size: 25px;
    }

    .step-circle {
        width: 34px;
        height: 34px;
    }

    .property-stepper .step span {
        font-size: 11px;
    }

    .step-line {
        margin-top: 17px;
    }

    .step-actions {
        flex-direction: column-reverse;
    }

    .back-btn,
    .next-btn,
    .save-btn {
        width: 100%;
    }

}

/* =========================================================
   PROPERTY SUBHEADING
   ========================================================= */

.property-subheading {
    margin-top: 35px;
    margin-bottom: 22px;
    padding-top: 25px;
    border-top: 1px solid #e5e7eb;
}

.property-subheading h4 {
    margin: 0 0 6px;
    font-size: 18px;
    font-weight: 600;
    color: #14213d;
}

.property-subheading p {
    margin: 0;
    font-size: 13px;
    color: #737b89;
}

/* =========================================================
   PROPERTY PHOTOS
   ========================================================= */

.photo-upload-area {
    margin-bottom: 25px;
}

#property_photos {
    display: none;
}

.photo-upload-box {
    min-height: 180px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    gap: 8px;

    border: 2px dashed #d8dde5;
    border-radius: 12px;

    background: #fafbfc;

    cursor: pointer;

    transition: 0.2s ease;
}

.photo-upload-box:hover {
    border-color: #14213d;
    background: #f7f8fa;
}

.upload-icon {
    width: 45px;
    height: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    font-size: 28px;
    font-weight: 300;

    background: #eef1f5;
    color: #14213d;
}

.photo-upload-box strong {
    font-size: 15px;
    color: #263247;
}

.photo-upload-box small {
    font-size: 12px;
    color: #7b8492;
}


/* =========================================================
   PHOTO PREVIEW
   ========================================================= */

.photo-preview {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 15px;

    margin-bottom: 30px;
}

.photo-preview-item {
    position: relative;

    height: 160px;

    overflow: hidden;

    border-radius: 10px;

    background: #f1f3f5;
}

.photo-preview-item img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}

.remove-photo {
    position: absolute;

    top: 8px;
    right: 8px;

    width: 28px;
    height: 28px;

    border: none;
    border-radius: 50%;

    background: rgba(0, 0, 0, 0.65);
    color: white;

    font-size: 20px;
    line-height: 1;

    cursor: pointer;
}

.remove-photo:hover {
    background: rgba(0, 0, 0, 0.85);
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

    .photo-preview {
        grid-template-columns: 1fr;
    }

}

/* =========================================================
   REVIEW
   ========================================================= */

.review-section {
    padding: 22px 0;

    border-bottom: 1px solid #e8ebef;
}

.review-section:last-of-type {
    border-bottom: none;
}

.review-section h4 {
    margin: 0 0 18px;

    font-size: 17px;

    color: #14213d;
}

.review-grid {
    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 14px;
}

.review-item {
    padding: 14px 16px;

    border: 1px solid #e3e6eb;

    border-radius: 8px;

    background: #fafbfc;
}

.review-item span {
    display: block;

    margin-bottom: 5px;

    font-size: 12px;

    color: #7b8492;
}

.review-item strong {
    font-size: 14px;

    color: #1e2a3d;

    word-break: break-word;
}

.review-description {
    margin: 0;

    padding: 15px;

    border-radius: 8px;

    background: #fafbfc;

    color: #4f5b6d;

    line-height: 1.6;
}

.review-tags {
    display: flex;

    flex-wrap: wrap;

    gap: 8px;
}

.review-tag {
    padding: 8px 12px;

    border-radius: 20px;

    background: #eef1f5;

    color: #263247;

    font-size: 13px;
}

.other-amenities-review {
    margin-top: 15px;

    font-size: 14px;

    color: #4f5b6d;
}

.review-photos {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;
}

.review-photos img {
    width: 100%;
    height: 150px;

    object-fit: cover;

    border-radius: 9px;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

    .review-grid {
        grid-template-columns: 1fr;
    }

    .review-photos {
        grid-template-columns: 1fr;
    }

}

    </style>

</head>

<body>

<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="property-container">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-header">

        <h2>
            Add Property
        </h2>

        <p>
            Add a new property to UrbanNest
        </p>

    </div>


    <!-- =====================================================
         STEPPER
    ====================================================== -->

    <div class="property-stepper">

        <div class="step active">
            <div class="step-circle">1</div>
            <span>Owner</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">2</div>
            <span>Type</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">3</div>
            <span>Details</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">4</div>
            <span>Location</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">5</div>
            <span>Amenities</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">6</div>
            <span>Photos</span>
        </div>

        <div class="step-line"></div>

        <div class="step">
            <div class="step-circle">7</div>
            <span>Review</span>
        </div>

    </div>


    <!-- =====================================================
         PROPERTY FORM
    ====================================================== -->

    <form
        method="POST"
        id="propertyForm"
        enctype="multipart/form-data"
    >


        <!-- =================================================
             STEP 1 — OWNER
        ================================================== -->

        <section
            class="property-step active"
            id="step-1"
        >

            <div class="step-content">

                <div class="step-heading">

                    <span class="step-number">
                        Step 1
                    </span>

                    <h3>
                        Select Property Owner
                    </h3>

                    <p>
                        Choose the user who owns or is listing
                        this property.
                    </p>

                </div>

                <div class="form-group">

                    <label for="user_id">
                        Property Owner
                    </label>

                    <select
                        name="user_id"
                        id="user_id"
                        required
                    >

                        <option value="">
                            -- Select Property Owner --
                        </option>

                        <?php while ($user = mysqli_fetch_assoc($users)): ?>

                            <option
                                value="<?php echo (int) $user['user_id']; ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $user['full_name']
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="step-actions">

                    <button
                        type="button"
                        class="next-btn"
                        onclick="nextStep()"
                    >
                        Continue
                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             STEP 2 — PROPERTY TYPE
        ================================================== -->

        <section
            class="property-step"
            id="step-2"
        >

            <div class="step-content">

                <div class="step-heading">

                    <span class="step-number">
                        Step 2
                    </span>

                    <h3>
                        Property Type
                    </h3>

                    <p>
                        Select the category and subcategory
                        for this property.
                    </p>

                </div>


                <div class="form-group">

                    <label for="category_id">
                        Category
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                        required
                    >

                        <option value="">
                            -- Select Category --
                        </option>

                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>

                            <option
                                value="<?php echo (int) $category['category_id']; ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $category['category_name']
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label for="subcategory_id">
                        Subcategory
                    </label>

                    <select
                        name="subcategory_id"
                        id="subcategory_id"
                        required
                        disabled
                    >

                        <option value="">
                            -- Select Category First --
                        </option>

                    </select>

                </div>


                <div class="step-actions">

                    <button
                        type="button"
                        class="back-btn"
                        onclick="previousStep()"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        class="next-btn"
                        onclick="nextStep()"
                    >
                        Continue
                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             STEP 3 — DYNAMIC DETAILS
        ================================================== -->

        <section
            class="property-step"
            id="step-3"
        >

            <div class="step-content">

                <div class="step-heading">

                    <span class="step-number">
                        Step 3
                    </span>

                    <h3>
                        Property Details
                    </h3>

                    <p>
                        Enter the information for this property.
                    </p>

                </div>


                <!-- Common details -->

                <div class="form-group">

                    <label for="title">
                        Property Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        placeholder="Enter property title"
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        placeholder="Describe the property..."
                    ></textarea>

                </div>


                <div class="form-group">

                    <label for="listing_type">
                        Listing Type
                    </label>

                    <select
                        name="listing_type"
                        id="listing_type"
                    >

                        <option value="">
                            -- Select Listing Type --
                        </option>

                        <option value="For Sale">
                            For Sale
                        </option>

                        <option value="For Rent">
                            For Rent
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="price">
                        Price
                    </label>

                    <input
                        type="number"
                        name="price"
                        id="price"
                        min="0"
                        step="0.01"
                        placeholder="Enter property price"
                    >

                </div>


                <div class="form-group">

                    <label for="area_sqft">
                        Area (sq.ft.)
                    </label>

                    <input
                        type="number"
                        name="area_sqft"
                        id="area_sqft"
                        min="0"
                        placeholder="Enter area in sq.ft."
                    >

                </div>

                <!-- Property Condition -->

                <div class="form-group">

                    <label for="property_condition">
                        Property Condition
                    </label>

                    <select
                        name="property_condition"
                        id="property_condition"
                    >

                        <option value="">
                            -- Select Property Condition --
                        </option>

                        <option value="New">
                            New
                        </option>

                        <option value="Ready to Move">
                            Ready to Move
                        </option>

                        <option value="Under Construction">
                            Under Construction
                        </option>

                    </select>

                </div>




                <!-- Dynamic specification area -->

                <div
                    id="dynamic-details"
                    style="display: none;"
                >

                    <div class="property-subheading">

                        <h4 id="dynamic-details-title">
                            Property Specifications
                        </h4>

                        <p id="dynamic-details-description">
                            Enter the specifications for this property.
                        </p>

                    </div>


                    <!-- Bedrooms -->

                    <div
                        class="form-group dynamic-field"
                        id="field-bedrooms"
                        style="display: none;"
                    >

                        <label for="bedrooms">
                            Bedrooms
                        </label>

                        <input
                            type="number"
                            name="bedrooms"
                            id="bedrooms"
                            min="0"
                            placeholder="Enter number of bedrooms"
                        >

                    </div>


                    <!-- Bathrooms -->

                    <div
                        class="form-group dynamic-field"
                        id="field-bathrooms"
                        style="display: none;"
                    >

                        <label for="bathrooms">
                            Bathrooms
                        </label>

                        <input
                            type="number"
                            name="bathrooms"
                            id="bathrooms"
                            min="0"
                            placeholder="Enter number of bathrooms"
                        >

                    </div>


                    <!-- Furnishing -->

                    <div
                        class="form-group dynamic-field"
                        id="field-furnishing"
                        style="display: none;"
                    >

                        <label for="furnishing">
                            Furnishing
                        </label>

                        <select
                            name="furnishing"
                            id="furnishing"
                        >

                            <option value="">
                                -- Select Furnishing --
                            </option>

                            <option value="Unfurnished">
                                Unfurnished
                            </option>

                            <option value="Semi-Furnished">
                                Semi-Furnished
                            </option>

                            <option value="Furnished">
                                Furnished
                            </option>

                        </select>

                    </div>


                    <!-- Floor Number -->

                    <div
                        class="form-group dynamic-field"
                        id="field-floor-number"
                        style="display: none;"
                    >

                        <label for="floor_number">
                            Floor Number
                        </label>

                        <input
                            type="number"
                            name="floor_number"
                            id="floor_number"
                            min="0"
                            placeholder="Enter floor number"
                        >

                    </div>


                    <!-- Total Floors -->

                    <div
                        class="form-group dynamic-field"
                        id="field-total-floors"
                        style="display: none;"
                    >

                        <label for="total_floors">
                            Total Floors
                        </label>

                        <input
                            type="number"
                            name="total_floors"
                            id="total_floors"
                            min="1"
                            placeholder="Enter total floors"
                        >

                    </div>


                    <!-- Plot Length -->

                    <div
                        class="form-group dynamic-field"
                        id="field-plot-length"
                        style="display: none;"
                    >

                        <label for="plot_length">
                            Plot Length
                        </label>

                        <input
                            type="number"
                            name="plot_length"
                            id="plot_length"
                            min="0"
                            step="0.01"
                            placeholder="Enter plot length"
                        >

                    </div>


                    <!-- Plot Width -->

                    <div
                        class="form-group dynamic-field"
                        id="field-plot-width"
                        style="display: none;"
                    >

                        <label for="plot_width">
                            Plot Width
                        </label>

                        <input
                            type="number"
                            name="plot_width"
                            id="plot_width"
                            min="0"
                            step="0.01"
                            placeholder="Enter plot width"
                        >

                    </div>

                </div>


                <div class="step-actions">

                    <button
                        type="button"
                        class="back-btn"
                        onclick="previousStep()"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        class="next-btn"
                        onclick="nextStep()"
                    >
                        Continue
                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             STEP 4 — LOCATION
        ================================================== -->

        <section
            class="property-step"
            id="step-4"
        >

            <div class="step-content">

                <div class="step-heading">

                    <span class="step-number">
                        Step 4
                    </span>

                    <h3>
                        Property Location
                    </h3>

                    <p>
                        Enter the location where this property
                        is situated.
                    </p>

                </div>


                <div class="form-group">

                    <label for="address">
                        Property Address
                    </label>

                    <textarea
                        name="address"
                        id="address"
                        placeholder="Enter the complete property address"
                        required
                    ></textarea>

                </div>


                <div class="form-group">

                    <label for="city">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        id="city"
                        placeholder="Enter city"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="state">
                        State
                    </label>

                    <input
                        type="text"
                        name="state"
                        id="state"
                        placeholder="Enter state"
                        required
                    >

                </div>


                <div class="step-actions">

                    <button
                        type="button"
                        class="back-btn"
                        onclick="previousStep()"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        class="next-btn"
                        onclick="nextStep()"
                    >
                        Continue
                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             STEP 5 — AMENITIES
        ================================================== -->

        <section
            class="property-step"
            id="step-5"
        >

            <div class="step-content">


                <div class="step-heading">

                    <span class="step-number">
                        Step 5
                    </span>

                    <h3>
                        Amenities
                    </h3>

                    <p>
                        Select the amenities available at
                        this property.
                    </p>

                </div>


                <!-- =================================================
                     DYNAMIC AMENITIES
                ================================================== -->

                <div
                    id="amenities-container"
                    class="amenities-container"
                >

                    <p
                        id="amenities-message"
                    >
                        Select a property type first to see
                        available amenities.
                    </p>

                </div>


                <!-- =================================================
                     OTHER AMENITIES
                ================================================== -->

                <div class="form-group">

                    <label for="other_amenities">
                        Other Amenities
                    </label>

                    <textarea
                        name="other_amenities"
                        id="other_amenities"
                        placeholder="Don't see an amenity? Write it here..."
                    ></textarea>

                </div>


                <!-- =================================================
                     BUTTONS
                ================================================== -->

                <div class="step-actions">

                    <button
                        type="button"
                        class="back-btn"
                        onclick="previousStep()"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        class="next-btn"
                        onclick="nextStep()"
                    >
                        Continue
                    </button>

                </div>

            </div>

        </section>

        <!-- =================================================
                STEP 6 — PHOTOS
        ================================================== -->

        <section
            class="property-step"
            id="step-6"
        >

        <div class="step-content">

            <div class="step-heading">

                <span class="step-number">
                    Step 6
                </span>

                <h3>
                    Property Photos
                </h3>

                <p>
                    Add clear photos of the property.
                </p>

            </div>


            <!-- =================================================
                PHOTO UPLOAD
            ================================================== -->

            <div class="photo-upload-area">

                <label
                    for="property_photos"
                    class="photo-upload-box"
                >

                    <span class="upload-icon">
                        +
                    </span>

                    <strong>
                        Add Property Photos
                    </strong>

                    <small>
                        Select multiple JPG, JPEG or PNG images
                    </small>

                </label>

            <input
                type="file"
                name="property_photos[]"
                id="property_photos"
                accept="image/jpeg,image/png,image/jpg"
                multiple
            >

        </div>


        <!-- =================================================
             PHOTO PREVIEW
        ================================================== -->

        <div
            id="photo-preview"
            class="photo-preview"
        ></div>


        <!-- =================================================
             BUTTONS
        ================================================== -->

        <div class="step-actions">

            <button
                type="button"
                class="back-btn"
                onclick="previousStep()"
            >
                Back
            </button>

            <button
                type="button"
                class="next-btn"
                onclick="nextStep()"
            >
                Continue
            </button>

        </div>

        </div>

        </section>

        <!-- =================================================
     STEP 7 — REVIEW
================================================== -->

<section
    class="property-step"
    id="step-7"
>

    <div class="step-content">

        <div class="step-heading">

            <span class="step-number">
                Step 7
            </span>

            <h3>
                Review Property
            </h3>

            <p>
                Review your property information before saving.
            </p>

        </div>


        <!-- =================================================
             BASIC INFORMATION
        ================================================== -->

        <div class="review-section">

            <h4>
                Property Information
            </h4>

            <div class="review-grid">

                <div class="review-item">

                    <span>
                        Property Owner
                    </span>

                    <strong id="review-owner">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        Property Type
                    </span>

                    <strong id="review-type">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        Property Title
                    </span>

                    <strong id="review-title">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        Listing Type
                    </span>

                    <strong id="review-listing">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        Price
                    </span>

                    <strong id="review-price">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        Area
                    </span>

                    <strong id="review-area">
                        —
                    </strong>

                </div>

                <div class="review-item">

                    <span>
                        Property Condition
                    </span>

                    <strong id="review-condition">
                        —
                    </strong>

                </div>

            </div>

        </div>


        <!-- =================================================
             DESCRIPTION
        ================================================== -->

        <div class="review-section">

            <h4>
                Description
            </h4>

            <p
                class="review-description"
                id="review-description"
            >
                —
            </p>

        </div>


        <!-- =================================================
             PROPERTY SPECIFICATIONS
        ================================================== -->

        <div class="review-section">

            <h4>
                Property Specifications
            </h4>

            <div
                class="review-grid"
                id="review-specifications"
            >
            </div>

        </div>


        <!-- =================================================
             LOCATION
        ================================================== -->

        <div class="review-section">

            <h4>
                Location
            </h4>

            <div class="review-grid">

                <div class="review-item">

                    <span>
                        Address
                    </span>

                    <strong id="review-address">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        City
                    </span>

                    <strong id="review-city">
                        —
                    </strong>

                </div>


                <div class="review-item">

                    <span>
                        State
                    </span>

                    <strong id="review-state">
                        —
                    </strong>

                </div>

            </div>

        </div>


        <!-- =================================================
             AMENITIES
        ================================================== -->

        <div class="review-section">

            <h4>
                Amenities
            </h4>

            <div
                id="review-amenities"
                class="review-tags"
            >
                —
            </div>


            <div
                id="review-other-amenities"
                class="other-amenities-review"
            >
            </div>

        </div>


        <!-- =================================================
             PHOTOS
        ================================================== -->

        <div class="review-section">

            <h4>
                Property Photos
            </h4>

            <div
                id="review-photos"
                class="review-photos"
            >
                —
            </div>

        </div>


        <!-- =================================================
             BUTTONS
        ================================================== -->

        <div class="step-actions">

            <button
                type="button"
                class="back-btn"
                onclick="previousStep()"
            >
                Back
            </button>

            <button
                type="submit"
                name="save_property"
                class="save-btn"
            >
                Save Property
            </button>

        </div>

    </div>

</section>


    </form>

</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<!-- =========================================================
     PHP DATA → JAVASCRIPT
========================================================= -->

<script>

const subcategories =
    <?php echo json_encode($subcategories); ?>;

const amenities =
    <?php echo json_encode($amenities); ?>;

</script>


<!-- =========================================================
     ADD PROPERTY JAVASCRIPT
========================================================= -->

<script>

/*
|--------------------------------------------------------------------------
| UrbanNest - Add Property JavaScript
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Current Step
|--------------------------------------------------------------------------
*/

let currentStep = 1;

const totalSteps = 7;


/*
|--------------------------------------------------------------------------
| Elements
|--------------------------------------------------------------------------
*/

const categorySelect =
    document.getElementById("category_id");

const subcategorySelect =
    document.getElementById("subcategory_id");


/*
|--------------------------------------------------------------------------
| Category → Subcategory
|--------------------------------------------------------------------------
*/

categorySelect.addEventListener(
    "change",
    function () {

        const categoryId =
            this.value;


        subcategorySelect.innerHTML =
            "";


        if (
            categoryId === ""
        ) {

            subcategorySelect.disabled =
                true;

            const option =
                document.createElement(
                    "option"
                );

            option.value =
                "";

            option.textContent =
                "-- Select Category First --";

            subcategorySelect.appendChild(
                option
            );

            hideDynamicFields();

            clearAmenities();

            return;

        }


        const defaultOption =
            document.createElement(
                "option"
            );

        defaultOption.value =
            "";

        defaultOption.textContent =
            "-- Select Subcategory --";

        subcategorySelect.appendChild(
            defaultOption
        );


        subcategories.forEach(
            function (subcategory) {

                if (
                    String(
                        subcategory.category_id
                    ) ===
                    String(categoryId)
                ) {

                    const option =
                        document.createElement(
                            "option"
                        );

                    option.value =
                        subcategory.subcategory_id;

                    option.textContent =
                        subcategory.subcategory_name;

                    subcategorySelect.appendChild(
                        option
                    );

                }

            }
        );


        subcategorySelect.disabled =
            false;

    }
);


/*
|--------------------------------------------------------------------------
| Subcategory → Dynamic Details
|--------------------------------------------------------------------------
*/

subcategorySelect.addEventListener(
    "change",
    function () {

        const selectedSubcategoryId =
            this.value;


        const selectedSubcategory =
            subcategories.find(
                function (subcategory) {

                    return String(
                        subcategory.subcategory_id
                    ) ===
                    String(
                        selectedSubcategoryId
                    );

                }
            );


        if (
            !selectedSubcategory
        ) {

            hideDynamicFields();

            clearAmenities();

            return;

        }


        const subcategoryId =
            selectedSubcategory.subcategory_id;

        const subcategoryName =
            selectedSubcategory.subcategory_name.trim();


        showDynamicFields(
            subcategoryId,
            subcategoryName
        );


        showAmenities(
            subcategoryId
        );

    }
);


/*
|--------------------------------------------------------------------------
| Property Field Configuration
|--------------------------------------------------------------------------
*/

const propertyFieldConfig = {

    // Residential
    1: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    2: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    3: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    4: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    5: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],


    // Commercial
    6: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    7: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    8: [
        "total-floors"
    ],

    9: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],


    // Luxury
    10: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    11: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    12: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    13: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],


    // Land
    14: [
        "plot-length",
        "plot-width"
    ],

    15: [
        "plot-length",
        "plot-width"
    ],

    16: [
        "plot-length",
        "plot-width"
    ],


    // Industrial
    17: [
        "total-floors"
    ],

    18: [
        "total-floors"
    ]

};


/*
|--------------------------------------------------------------------------
| Show Dynamic Details
|--------------------------------------------------------------------------
*/

function showDynamicFields(
    subcategoryId,
    subcategoryName
) {

    const dynamicDetails =
        document.getElementById(
            "dynamic-details"
        );


    hideDynamicFields();


    const fields =
        propertyFieldConfig[
            subcategoryId
        ];


    if (
        !fields ||
        fields.length === 0
    ) {

        return;

    }


    dynamicDetails.style.display =
        "block";


    fields.forEach(
        function (fieldName) {

            const field =
                document.getElementById(
                    "field-" + fieldName
                );


            if (field) {

                field.style.display =
                    "block";

            }

        }
    );


    const title =
        document.getElementById(
            "dynamic-details-title"
        );


    const description =
        document.getElementById(
            "dynamic-details-description"
        );


    title.textContent =
        subcategoryName +
        " Specifications";


    description.textContent =
        "Enter the specifications for this " +
        subcategoryName.toLowerCase() +
        ".";

}


/*
|--------------------------------------------------------------------------
| Hide Dynamic Details
|--------------------------------------------------------------------------
*/

function hideDynamicFields() {

    const dynamicDetails =
        document.getElementById(
            "dynamic-details"
        );


    dynamicDetails.style.display =
        "none";


    const fields =
        document.querySelectorAll(
            ".dynamic-field"
        );


    fields.forEach(
        function (field) {

            field.style.display =
                "none";

        }
    );


    const inputs =
        dynamicDetails.querySelectorAll(
            "input, select"
        );


    inputs.forEach(
        function (input) {

            input.value =
                "";

        }
    );

}


/*
|--------------------------------------------------------------------------
| Amenity Configuration
|--------------------------------------------------------------------------
|
| These names must match the amenities table.
|
|--------------------------------------------------------------------------
*/

const amenityConfig = {

    // Apartment
    1: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Villa
    2: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Bungalow
    3: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Duplex
    4: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Independent House
    5: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Gated Community",
        "Pet Friendly"
    ],

    // Office
    6: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Air Conditioning",
        "EV Charging",
        "Gated Community"
    ],

    // Shop
    7: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Air Conditioning",
        "Gated Community"
    ],

    // Warehouse
    8: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Gated Community"
    ],

    // Showroom
    9: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Air Conditioning",
        "EV Charging",
        "Gated Community"
    ],

    // Penthouse
    10: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Farmhouse
    11: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Gated Community",
        "Pet Friendly"
    ],

    // Luxury Villa
    12: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Luxury Bungalow
    13: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Residential Plot
    14: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Commercial Plot
    15: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Agriculture
    16: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Factory
    17: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "EV Charging",
        "Gated Community"
    ],

    // Industrial Shed
    18: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "EV Charging",
        "Gated Community"
    ]

};


/*
|--------------------------------------------------------------------------
| Show Amenities
|--------------------------------------------------------------------------
*/

function showAmenities(
    subcategoryId
) {

    const container =
        document.getElementById(
            "amenities-container"
        );


    const message =
        document.getElementById(
            "amenities-message"
        );


    /*
    |--------------------------------------------------------------------------
    | Clear Previous Amenities
    |--------------------------------------------------------------------------
    */

    container.innerHTML =
        "";


    /*
    |--------------------------------------------------------------------------
    | Get Allowed Amenities
    |--------------------------------------------------------------------------
    */

    const allowedAmenities =
        amenityConfig[
            subcategoryId
        ];


    /*
    |--------------------------------------------------------------------------
    | No Configuration
    |--------------------------------------------------------------------------
    */

    if (
        !allowedAmenities ||
        allowedAmenities.length === 0
    ) {

        message.textContent =
            "No predefined amenities available.";

        container.appendChild(
            message
        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Create Amenity Checkboxes
    |--------------------------------------------------------------------------
    */

    amenities.forEach(
        function (amenity) {

            if (
                allowedAmenities.includes(
                    amenity.amenity_name
                )
            ) {

                const wrapper =
                    document.createElement(
                        "div"
                    );

                wrapper.className =
                    "amenity-option";


                const checkbox =
                    document.createElement(
                        "input"
                    );

                checkbox.type =
                    "checkbox";

                checkbox.name =
                    "amenities[]";

                checkbox.value =
                    amenity.amenity_id;

                checkbox.id =
                    "amenity-" +
                    amenity.amenity_id;


                const label =
                    document.createElement(
                        "label"
                    );

                label.htmlFor =
                    checkbox.id;

                label.textContent =
                    amenity.amenity_name;


                wrapper.appendChild(
                    checkbox
                );

                wrapper.appendChild(
                    label
                );


                container.appendChild(
                    wrapper
                );

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Clear Amenities
|--------------------------------------------------------------------------
*/

function clearAmenities() {

    const container =
        document.getElementById(
            "amenities-container"
        );


    container.innerHTML =
        "";


    const message =
        document.createElement(
            "p"
        );


    message.id =
        "amenities-message";


    message.textContent =
        "Select a property type first to see available amenities.";


    container.appendChild(
        message
    );

}


/*
|--------------------------------------------------------------------------
| Show Step
|--------------------------------------------------------------------------
*/

function showStep(
    stepNumber
) {

    const steps =
        document.querySelectorAll(
            ".property-step"
        );


    const indicators =
        document.querySelectorAll(
            ".property-stepper .step"
        );


    steps.forEach(
        function (step) {

            step.classList.remove(
                "active"
            );

        }
    );


    const currentSection =
        document.getElementById(
            "step-" + stepNumber
        );


    if (currentSection) {

        currentSection.classList.add(
            "active"
        );

    }


    indicators.forEach(
        function (
            indicator,
            index
        ) {

            indicator.classList.remove(
                "active",
                "completed"
            );


            if (
                index + 1 ===
                stepNumber
            ) {

                indicator.classList.add(
                    "active"
                );

            }


            if (
                index + 1 <
                stepNumber
            ) {

                indicator.classList.add(
                    "completed"
                );

            }

        }
    );


    currentStep =
        stepNumber;


    if (
        stepNumber === 7
    ) {

        buildPropertyReview();

    }


    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}
/*
|--------------------------------------------------------------------------
| Validate Current Step
|--------------------------------------------------------------------------
*/

function validateCurrentStep() {

    /*
    |--------------------------------------------------------------------------
    | STEP 1 — OWNER
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 1
    ) {

        const owner =
            document.getElementById(
                "user_id"
            );


        if (
            !owner.value
        ) {

            alert(
                "Please select a property owner."
            );

            owner.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2 — TYPE
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 2
    ) {

        const category =
            document.getElementById(
                "category_id"
            );


        const subcategory =
            document.getElementById(
                "subcategory_id"
            );


        if (
            !category.value
        ) {

            alert(
                "Please select a category."
            );

            category.focus();

            return false;

        }


        if (
            !subcategory.value
        ) {

            alert(
                "Please select a subcategory."
            );

            subcategory.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 3 — DETAILS
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 3
    ) {

        const title =
            document.getElementById(
                "title"
            );


        const description =
            document.getElementById(
                "description"
            );


        const listingType =
            document.getElementById(
                "listing_type"
            );


        const price =
            document.getElementById(
                "price"
            );


        const area =
            document.getElementById(
                "area_sqft"
            );


        if (
            !title.value.trim()
        ) {

            alert(
                "Please enter a property title."
            );

            title.focus();

            return false;

        }


        if (
            !description.value.trim()
        ) {

            alert(
                "Please enter the property description."
            );

            description.focus();

            return false;

        }


        if (
            !listingType.value
        ) {

            alert(
                "Please select a listing type."
            );

            listingType.focus();

            return false;

        }


        if (
            !price.value
        ) {

            alert(
                "Please enter the property price."
            );

            price.focus();

            return false;

        }


        if (
            !area.value
        ) {

            alert(
                "Please enter the property area."
            );

            area.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 4 — LOCATION
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 4
    ) {

        const address =
            document.getElementById(
                "address"
            );


        const city =
            document.getElementById(
                "city"
            );


        const state =
            document.getElementById(
                "state"
            );


        if (
            !address.value.trim()
        ) {

            alert(
                "Please enter the property address."
            );

            address.focus();

            return false;

        }


        if (
            !city.value.trim()
        ) {

            alert(
                "Please enter the city."
            );

            city.focus();

            return false;

        }


        if (
            !state.value.trim()
        ) {

            alert(
                "Please enter the state."
            );

            state.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 5 — AMENITIES
    |--------------------------------------------------------------------------
    |
    | Amenities are optional.
    |
    */

    if (
        currentStep === 5
    ) {

        /*
        | No validation required.
        */

    }


    return true;

}


/*
|--------------------------------------------------------------------------
| Next Step
|--------------------------------------------------------------------------
*/

function nextStep() {

    if (
        !validateCurrentStep()
    ) {

        return;

    }


    if (
        currentStep <
        totalSteps
    ) {

        showStep(
            currentStep + 1
        );

    }

}


/*
|--------------------------------------------------------------------------
| Previous Step
|--------------------------------------------------------------------------
*/

function previousStep() {

    if (
        currentStep > 1
    ) {

        showStep(
            currentStep - 1
        );

    }

}


/*
|--------------------------------------------------------------------------
| Property Photo Preview
|--------------------------------------------------------------------------
*/

const photoInput =
    document.getElementById(
        "property_photos"
    );

const photoPreview =
    document.getElementById(
        "photo-preview"
    );


let selectedPhotos = [];


/*
|--------------------------------------------------------------------------
| Photo Selection
|--------------------------------------------------------------------------
*/

if (
    photoInput
) {

    photoInput.addEventListener(
        "change",
        function () {

            const newPhotos =
                Array.from(
                    this.files
                );


            newPhotos.forEach(
                function (file) {

                    const alreadyExists =
                        selectedPhotos.some(
                            function (existingFile) {

                                return (
                                    existingFile.name ===
                                    file.name &&
                                    existingFile.size ===
                                    file.size &&
                                    existingFile.lastModified ===
                                    file.lastModified
                                );

                            }
                        );


                    if (
                        !alreadyExists
                    ) {

                        selectedPhotos.push(
                            file
                        );

                    }

                }
            );


            displayPhotoPreviews();


            /*
            |--------------------------------------------------------------------------
            | Reset input
            |--------------------------------------------------------------------------
            */

            this.value = "";

        }
    );

}


/*
|--------------------------------------------------------------------------
| Display Photo Previews
|--------------------------------------------------------------------------
*/

function displayPhotoPreviews() {

    if (
        !photoPreview
    ) {

        return;

    }


    photoPreview.innerHTML =
        "";


    selectedPhotos.forEach(
        function (
            file,
            index
        ) {

            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    const photoBox =
                        document.createElement(
                            "div"
                        );

                    photoBox.className =
                        "photo-preview-item";


                    const image =
                        document.createElement(
                            "img"
                        );

                    image.src =
                        event.target.result;


                    const removeButton =
                        document.createElement(
                            "button"
                        );

                    removeButton.type =
                        "button";

                    removeButton.className =
                        "remove-photo";

                    removeButton.textContent =
                        "×";


                    removeButton.onclick =
                        function () {

                            removePhoto(
                                index
                            );

                        };


                    photoBox.appendChild(
                        image
                    );

                    photoBox.appendChild(
                        removeButton
                    );


                    photoPreview.appendChild(
                        photoBox
                    );

                };


            reader.readAsDataURL(
                file
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Remove Photo
|--------------------------------------------------------------------------
*/

function removePhoto(
    index
) {

    selectedPhotos.splice(
        index,
        1
    );


    displayPhotoPreviews();


    if (
        !photoInput
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Update File Input
    |--------------------------------------------------------------------------
    */

    const dataTransfer =
        new DataTransfer();


    selectedPhotos.forEach(
        function (file) {

            dataTransfer.items.add(
                file
            );

        }
    );


    photoInput.files =
        dataTransfer.files;

}


/*
|--------------------------------------------------------------------------
| Photo Count Helper
|--------------------------------------------------------------------------
*/

function getSelectedPhotoCount() {

    return selectedPhotos.length;

}


/*
|--------------------------------------------------------------------------
| Clear Selected Photos
|--------------------------------------------------------------------------
*/

function clearSelectedPhotos() {

    selectedPhotos = [];


    if (
        photoInput
    ) {

        photoInput.value =
            "";

    }


    displayPhotoPreviews();

}


/*
|--------------------------------------------------------------------------
| Property Review — Basic Information
|--------------------------------------------------------------------------
*/

function buildPropertyReview() {

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    const ownerSelect =
        document.getElementById(
            "user_id"
        );


    const ownerText =
        ownerSelect.options[
            ownerSelect.selectedIndex
        ]?.text ||
        "—";


    const reviewOwner =
        document.getElementById(
            "review-owner"
        );


    if (
        reviewOwner
    ) {

        reviewOwner.textContent =
            ownerText;

    }


    /*
    |--------------------------------------------------------------------------
    | Property Type
    |--------------------------------------------------------------------------
    */

    const subcategorySelect =
        document.getElementById(
            "subcategory_id"
        );


    const typeText =
        subcategorySelect.options[
            subcategorySelect.selectedIndex
        ]?.text ||
        "—";


    const reviewType =
        document.getElementById(
            "review-type"
        );


    if (
        reviewType
    ) {

        reviewType.textContent =
            typeText;

    }


    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    const reviewTitle =
        document.getElementById(
            "review-title"
        );


    if (
        reviewTitle
    ) {

        reviewTitle.textContent =
            document.getElementById(
                "title"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Listing Type
    |--------------------------------------------------------------------------
    */

    const reviewListing =
        document.getElementById(
            "review-listing"
        );


    if (
        reviewListing
    ) {

        reviewListing.textContent =
            document.getElementById(
                "listing_type"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Price
    |--------------------------------------------------------------------------
    */

    const reviewPrice =
        document.getElementById(
            "review-price"
        );


    if (
        reviewPrice
    ) {

        const price =
            document.getElementById(
                "price"
            ).value;


        reviewPrice.textContent =
            price
                ? "₹ " +
                  Number(
                      price
                  ).toLocaleString(
                      "en-IN"
                  )
                : "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Area
    |--------------------------------------------------------------------------
    */

    const reviewArea =
        document.getElementById(
            "review-area"
        );


    if (
        reviewArea
    ) {

        const area =
            document.getElementById(
                "area_sqft"
            ).value;


        reviewArea.textContent =
            area
                ? area +
                  " sq.ft."
                : "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Condition
    |--------------------------------------------------------------------------
    */

    const reviewCondition =
        document.getElementById(
            "review-condition"
        );


    if (
        reviewCondition
    ) {

        reviewCondition.textContent =
            document.getElementById(
                "property_condition"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    const reviewDescription =
        document.getElementById(
            "review-description"
        );


    if (
        reviewDescription
    ) {

        reviewDescription.textContent =
            document.getElementById(
                "description"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    const reviewAddress =
        document.getElementById(
            "review-address"
        );


    if (
        reviewAddress
    ) {

        reviewAddress.textContent =
            document.getElementById(
                "address"
            ).value ||
            "—";

    }


    const reviewCity =
        document.getElementById(
            "review-city"
        );


    if (
        reviewCity
    ) {

        reviewCity.textContent =
            document.getElementById(
                "city"
            ).value ||
            "—";

    }


    const reviewState =
        document.getElementById(
            "review-state"
        );


    if (
        reviewState
    ) {

        reviewState.textContent =
            document.getElementById(
                "state"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Specifications
    |--------------------------------------------------------------------------
    */

    buildSpecificationReview();


    /*
    |--------------------------------------------------------------------------
    | Amenities
    |--------------------------------------------------------------------------
    */

    buildAmenityReview();


    /*
    |--------------------------------------------------------------------------
    | Other Amenities
    |--------------------------------------------------------------------------
    */

    buildOtherAmenityReview();


    /*
    |--------------------------------------------------------------------------
    | Photos
    |--------------------------------------------------------------------------
    */

    buildPhotoReview();

}


/*
|--------------------------------------------------------------------------
| Specification Review
|--------------------------------------------------------------------------
*/

function buildSpecificationReview() {

    const specifications =
        document.getElementById(
            "review-specifications"
        );


    if (
        !specifications
    ) {

        return;

    }


    specifications.innerHTML =
        "";


    const specificationFields = [

        {
            id: "bedrooms",
            label: "Bedrooms"
        },

        {
            id: "bathrooms",
            label: "Bathrooms"
        },

        {
            id: "furnishing",
            label: "Furnishing"
        },

        {
            id: "floor_number",
            label: "Floor Number"
        },

        {
            id: "total_floors",
            label: "Total Floors"
        },

        {
            id: "plot_length",
            label: "Plot Length"
        },

        {
            id: "plot_width",
            label: "Plot Width"
        }

    ];


    specificationFields.forEach(
        function (field) {

            const element =
                document.getElementById(
                    field.id
                );


            if (
                !element ||
                !element.value
            ) {

                return;

            }


            const item =
                document.createElement(
                    "div"
                );

            item.className =
                "review-item";


            const label =
                document.createElement(
                    "span"
                );

            label.textContent =
                field.label;


            const value =
                document.createElement(
                    "strong"
                );

            value.textContent =
                element.value;


            item.appendChild(
                label
            );

            item.appendChild(
                value
            );


            specifications.appendChild(
                item
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Amenity Review
|--------------------------------------------------------------------------
*/

function buildAmenityReview() {

    const reviewAmenities =
        document.getElementById(
            "review-amenities"
        );


    if (
        !reviewAmenities
    ) {

        return;

    }


    reviewAmenities.innerHTML =
        "";


    const selectedAmenities =
        document.querySelectorAll(
            '#amenities-container input[name="amenities[]"]:checked'
        );


    if (
        selectedAmenities.length === 0
    ) {

        reviewAmenities.textContent =
            "No predefined amenities selected.";

        return;

    }


    selectedAmenities.forEach(
        function (checkbox) {

            const label =
                document.querySelector(
                    'label[for="' +
                    checkbox.id +
                    '"]'
                );


            if (
                !label
            ) {

                return;

            }


            const tag =
                document.createElement(
                    "span"
                );


            tag.className =
                "review-tag";


            tag.textContent =
                label.textContent;


            reviewAmenities.appendChild(
                tag
            );

        }
    );

}
/*
|--------------------------------------------------------------------------
| Other Amenities Review
|--------------------------------------------------------------------------
*/

function buildOtherAmenityReview() {

    const otherInput =
        document.getElementById(
            "other_amenities"
        );


    const reviewOther =
        document.getElementById(
            "review-other-amenities"
        );


    if (
        !reviewOther
    ) {

        return;

    }


    reviewOther.innerHTML =
        "";


    if (
        !otherInput
    ) {

        return;

    }


    const otherAmenities =
        otherInput.value.trim();


    if (
        otherAmenities
    ) {

        const label =
            document.createElement(
                "strong"
            );


        label.textContent =
            "Other Amenities: ";


        const text =
            document.createElement(
                "span"
            );


        text.textContent =
            otherAmenities;


        reviewOther.appendChild(
            label
        );


        reviewOther.appendChild(
            text
        );

    }

}


/*
|--------------------------------------------------------------------------
| Photo Review
|--------------------------------------------------------------------------
*/

function buildPhotoReview() {

    const reviewPhotos =
        document.getElementById(
            "review-photos"
        );


    if (
        !reviewPhotos
    ) {

        return;

    }


    reviewPhotos.innerHTML =
        "";


    if (
        selectedPhotos.length === 0
    ) {

        reviewPhotos.textContent =
            "No photos selected.";

        return;

    }


    selectedPhotos.forEach(
        function (file) {

            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    const image =
                        document.createElement(
                            "img"
                        );


                    image.src =
                        event.target.result;


                    image.alt =
                        "Property Photo";


                    reviewPhotos.appendChild(
                        image
                    );

                };


            reader.readAsDataURL(
                file
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Review Refresh Helper
|--------------------------------------------------------------------------
*/

function refreshPropertyReview() {

    if (
        currentStep === 7
    ) {

        buildPropertyReview();

    }

}


/*
|--------------------------------------------------------------------------
| Review Data Helpers
|--------------------------------------------------------------------------
*/

function getInputValue(
    elementId
) {

    const element =
        document.getElementById(
            elementId
        );


    if (
        !element
    ) {

        return "";

    }


    return element.value;

}


/*
|--------------------------------------------------------------------------
| Selected Subcategory Helper
|--------------------------------------------------------------------------
*/

function getSelectedSubcategory() {

    const select =
        document.getElementById(
            "subcategory_id"
        );


    if (
        !select ||
        !select.value
    ) {

        return null;

    }


    return subcategories.find(
        function (subcategory) {

            return String(
                subcategory.subcategory_id
            ) ===
            String(
                select.value
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| Selected Category Helper
|--------------------------------------------------------------------------
*/

function getSelectedCategory() {

    const select =
        document.getElementById(
            "category_id"
        );


    if (
        !select ||
        !select.value
    ) {

        return null;

    }


    return categories.find(
        function (category) {

            return String(
                category.category_id
            ) ===
            String(
                select.value
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| Scroll To Top
|--------------------------------------------------------------------------
*/

function scrollToTop() {

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/*
|--------------------------------------------------------------------------
| Step Indicator Helper
|--------------------------------------------------------------------------
*/

function updateStepIndicators(
    stepNumber
) {

    const indicators =
        document.querySelectorAll(
            ".property-stepper .step"
        );


    indicators.forEach(
        function (
            indicator,
            index
        ) {

            const number =
                index + 1;


            indicator.classList.remove(
                "active",
                "completed"
            );


            if (
                number ===
                stepNumber
            ) {

                indicator.classList.add(
                    "active"
                );

            }


            if (
                number <
                stepNumber
            ) {

                indicator.classList.add(
                    "completed"
                );

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Direct Step Navigation
|--------------------------------------------------------------------------
*/

function goToStep(
    stepNumber
) {

    if (
        stepNumber < 1 ||
        stepNumber > totalSteps
    ) {

        return;

    }


    showStep(
        stepNumber
    );

}


/*
|--------------------------------------------------------------------------
| Step Validation Helper
|--------------------------------------------------------------------------
*/

function isStepValid(
    stepNumber
) {

    const originalStep =
        currentStep;


    currentStep =
        stepNumber;


    const valid =
        validateCurrentStep();


    currentStep =
        originalStep;


    return valid;

}


/*
|--------------------------------------------------------------------------
| Form Submit Protection
|--------------------------------------------------------------------------
*/

const propertyForm =
    document.getElementById(
        "property-form"
    );


if (
    propertyForm
) {

    propertyForm.addEventListener(
        "submit",
        function (event) {

            /*
            |--------------------------------------------------------------------------
            | Make sure the form is complete enough before submission.
            |--------------------------------------------------------------------------
            */

            const owner =
                document.getElementById(
                    "user_id"
                );


            const subcategory =
                document.getElementById(
                    "subcategory_id"
                );


            if (
                !owner ||
                !owner.value
            ) {

                event.preventDefault();


                alert(
                    "Please select a property owner."
                );


                showStep(1);


                return;

            }


            if (
                !subcategory ||
                !subcategory.value
            ) {

                event.preventDefault();


                alert(
                    "Please select a property subcategory."
                );


                showStep(2);


                return;

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Dynamic Details — Preserve Selected Values
|--------------------------------------------------------------------------
*/

function refreshDynamicDetails() {

    const selectedSubcategory =
        getSelectedSubcategory();


    if (
        !selectedSubcategory
    ) {

        hideDynamicFields();

        clearAmenities();

        return;

    }


    showDynamicFields(
        selectedSubcategory.subcategory_id,
        selectedSubcategory.subcategory_name
    );


    showAmenities(
        selectedSubcategory.subcategory_id
    );

}


/*
|--------------------------------------------------------------------------
| Amenity Search / Matching Helper
|--------------------------------------------------------------------------
|
| This does not alter database values.
| It only makes matching safer when capitalization or spaces differ.
|--------------------------------------------------------------------------
*/

function normalizeAmenityName(
    name
) {

    return String(
        name ?? ""
    )
        .trim()
        .toLowerCase();

}


/*
|--------------------------------------------------------------------------
| Improved Amenity Renderer
|--------------------------------------------------------------------------
|
| Kept as a separate function so the existing showAmenities()
| remains compatible with the current form.
|--------------------------------------------------------------------------
*/

function renderAmenitiesForSubcategory(
    subcategoryId
) {

    const container =
        document.getElementById(
            "amenities-container"
        );


    if (
        !container
    ) {

        return;

    }


    container.innerHTML =
        "";


    const allowedAmenities =
        amenityConfig[
            subcategoryId
        ];


    if (
        !allowedAmenities ||
        allowedAmenities.length === 0
    ) {

        const message =
            document.createElement(
                "p"
            );


        message.id =
            "amenities-message";


        message.textContent =
            "No predefined amenities available.";


        container.appendChild(
            message
        );


        return;

    }


    const normalizedAllowed =
        allowedAmenities.map(
            function (name) {

                return normalizeAmenityName(
                    name
                );

            }
        );


    let matchFound =
        false;


    amenities.forEach(
        function (amenity) {

            const amenityName =
                normalizeAmenityName(
                    amenity.amenity_name
                );


            if (
                !normalizedAllowed.includes(
                    amenityName
                )
            ) {

                return;

            }


            matchFound =
                true;


            const wrapper =
                document.createElement(
                    "div"
                );


            wrapper.className =
                "amenity-option";


            const checkbox =
                document.createElement(
                    "input"
                );


            checkbox.type =
                "checkbox";


            checkbox.name =
                "amenities[]";


            checkbox.value =
                amenity.amenity_id;


            checkbox.id =
                "amenity-" +
                amenity.amenity_id;


            const label =
                document.createElement(
                    "label"
                );


            label.htmlFor =
                checkbox.id;


            label.textContent =
                amenity.amenity_name;


            wrapper.appendChild(
                checkbox
            );


            wrapper.appendChild(
                label
            );


            container.appendChild(
                wrapper
            );

        }
    );


    if (
        !matchFound
    ) {

        const message =
            document.createElement(
                "p"
            );


        message.id =
            "amenities-message";


        message.textContent =
            "No predefined amenities available for this property type.";


        container.appendChild(
            message
        );

    }

}


/*
|--------------------------------------------------------------------------
| Re-render Amenities When Needed
|--------------------------------------------------------------------------
*/

function refreshAmenities() {

    const selectedSubcategory =
        getSelectedSubcategory();


    if (
        !selectedSubcategory
    ) {

        clearAmenities();

        return;

    }


    renderAmenitiesForSubcategory(
        selectedSubcategory.subcategory_id
    );

}


/*
|--------------------------------------------------------------------------
| Review Edit Helpers
|--------------------------------------------------------------------------
|
| These are ready for the V2 Edit buttons.
| They do not change the current V1 UI.
|--------------------------------------------------------------------------
*/

function editOwnerStep() {

    showStep(1);

}


function editTypeStep() {

    showStep(2);

}


function editDetailsStep() {

    showStep(3);

}


function editLocationStep() {

    showStep(4);

}


function editAmenitiesStep() {

    showStep(5);

}


function editPhotosStep() {

    showStep(6);

}


/*
|--------------------------------------------------------------------------
| Keyboard Safety
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "keydown",
    function (event) {

        /*
        |--------------------------------------------------------------------------
        | Prevent accidental Enter submission while editing fields.
        |--------------------------------------------------------------------------
        */

        if (
            event.key !== "Enter"
        ) {

            return;

        }


        const target =
            event.target;


        if (
            !target
        ) {

            return;

        }


        const tagName =
            target.tagName
                .toLowerCase();


        if (
            tagName === "textarea"
        ) {

            return;

        }


        if (
            tagName === "input" ||
            tagName === "select"
        ) {

            event.preventDefault();

        }

    }
);


/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
        |--------------------------------------------------------------------------
        | Make sure Step 1 is displayed.
        |--------------------------------------------------------------------------
        */

        showStep(1);


        /*
        |--------------------------------------------------------------------------
        | Make sure the subcategory is initially disabled.
        |--------------------------------------------------------------------------
        */

        if (
            subcategorySelect
        ) {

            subcategorySelect.disabled =
                true;

        }


        /*
        |--------------------------------------------------------------------------
        | Clear dynamic sections.
        |--------------------------------------------------------------------------
        */

        hideDynamicFields();

        clearAmenities();

    }
);
/*
|--------------------------------------------------------------------------
| Final Initialization
|--------------------------------------------------------------------------
|
| Keep the form in a clean initial state when the page loads.
|--------------------------------------------------------------------------
*/

(function initializePropertyForm() {

    /*
    |--------------------------------------------------------------------------
    | Category / Subcategory
    |--------------------------------------------------------------------------
    */

    if (
        categorySelect
    ) {

        /*
        | If a category is already selected by PHP,
        | populate its subcategories.
        */

        if (
            categorySelect.value
        ) {

            categorySelect.dispatchEvent(
                new Event("change")
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Step
    |--------------------------------------------------------------------------
    */

    showStep(1);


    /*
    |--------------------------------------------------------------------------
    | Initial Dynamic Details
    |--------------------------------------------------------------------------
    */

    hideDynamicFields();


    /*
    |--------------------------------------------------------------------------
    | Initial Amenities
    |--------------------------------------------------------------------------
    */

    clearAmenities();

})();


/*
|--------------------------------------------------------------------------
| Expose Functions
|--------------------------------------------------------------------------
|
| The HTML buttons can call these functions directly:
|
|   nextStep()
|   previousStep()
|   showStep()
|
| Keeping them on window makes them available to inline onclick handlers.
|--------------------------------------------------------------------------
*/

window.nextStep =
    nextStep;


window.previousStep =
    previousStep;


window.showStep =
    showStep;


window.goToStep =
    goToStep;


window.buildPropertyReview =
    buildPropertyReview;


window.showDynamicFields =
    showDynamicFields;


window.showAmenities =
    showAmenities;


window.clearAmenities =
    clearAmenities;


window.displayPhotoPreviews =
    displayPhotoPreviews;


window.removePhoto =
    removePhoto;


/*
|--------------------------------------------------------------------------
| Final Safety Check
|--------------------------------------------------------------------------
*/

if (
    typeof subcategories === "undefined"
) {

    console.error(
        "UrbanNest: subcategories data was not loaded from PHP."
    );

}


if (
    typeof amenities === "undefined"
) {

    console.error(
        "UrbanNest: amenities data was not loaded from PHP."
    );

}


if (
    typeof amenityConfig === "undefined"
) {

    console.error(
        "UrbanNest: amenity configuration was not loaded."
    );

}


/*
|--------------------------------------------------------------------------
| END OF ADD PROPERTY JAVASCRIPT
|--------------------------------------------------------------------------
*/

</script>


</body>

</html>