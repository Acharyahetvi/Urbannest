<?php

include "../db_connection.php";

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
        isset($_POST["property_condition"]) &&
        trim($_POST["property_condition"]) !== ""
            ? trim($_POST["property_condition"])
            : null;


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
        isset($_POST["furnishing"]) &&
        trim($_POST["furnishing"]) !== ""
            ? trim($_POST["furnishing"])
            : null;

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
    | Main Photo Must Be Explicitly Selected
    |--------------------------------------------------------------------------
    |
    | The main photo is never inferred automatically. The user must
    | explicitly choose it in Step 6.
    |--------------------------------------------------------------------------
    */

    if (
        !isset(
            $_FILES["main_property_photo"]
        ) ||
        $_FILES["main_property_photo"]["error"]
            === UPLOAD_ERR_NO_FILE
    ) {

        die(
            "Please select a main property photo."
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
                "../assets/images/property/property" . $property_id . "/";


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


        $allowed_extensions = [
            "jpg",
            "jpeg",
            "png"
        ];


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


        /*
        |--------------------------------------------------------------------------
        | Main Property Photo (explicitly selected by the user)
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $_FILES["main_property_photo"]
            ) &&
            $_FILES["main_property_photo"]["error"]
                === UPLOAD_ERR_OK
        ) {

            $main_original_name =
                $_FILES["main_property_photo"]["name"];


            $main_tmp_name =
                $_FILES["main_property_photo"]["tmp_name"];


            /*
            |--------------------------------------------------------------------------
            | Check Image
            |--------------------------------------------------------------------------
            */

            $main_image_info =
                getimagesize(
                    $main_tmp_name
                );


            if (
                $main_image_info === false
            ) {

                throw new Exception(
                    "The main property photo is not a valid image."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Allowed Extensions
            |--------------------------------------------------------------------------
            */

            $main_extension =
                strtolower(
                    pathinfo(
                        $main_original_name,
                        PATHINFO_EXTENSION
                    )
                );


            if (
                !in_array(
                    $main_extension,
                    $allowed_extensions,
                    true
                )
            ) {

                throw new Exception(
                    "The main property photo must be a JPG, JPEG or PNG image."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Generate Unique File Name
            |--------------------------------------------------------------------------
            */

            $main_new_filename =
                "property_" .
                $property_id .
                "_main_" .
                uniqid() .
                "." .
                $main_extension;


            $main_destination =
                $upload_directory .
                $main_new_filename;


            /*
            |--------------------------------------------------------------------------
            | Move Image
            |--------------------------------------------------------------------------
            */

            if (
                !move_uploaded_file(
                    $main_tmp_name,
                    $main_destination
                )
            ) {

                throw new Exception(
                    "Could not upload the main property photo."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Store Image Record — Explicitly the Main Photo
            |--------------------------------------------------------------------------
            */

            $main_image_path =
                "assets/images/property/property" .
                $property_id .
                "/" .
                $main_new_filename;


            $is_main = 1;


            mysqli_stmt_bind_param(
                $image_stmt,
                "isi",
                $property_id,
                $main_image_path,
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


        /*
        |--------------------------------------------------------------------------
        | Additional Property Photos (never the main photo)
        |--------------------------------------------------------------------------
        */

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
                | Additional Photos Are Never The Main Photo
                |--------------------------------------------------------------------------
                */

                $is_main = 0;


                /*
                |--------------------------------------------------------------------------
                | Store Image Record
                |--------------------------------------------------------------------------
                */

                $image_path =
                    "assets/images/property/property" .
                    $property_id .
                    "/" .
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

        }


        mysqli_stmt_close(
            $image_stmt
        );


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

    <link
        rel="stylesheet"
        href="add.css"
    >

    <!-- =====================================================
         MINIMAL PHOTO UPLOAD CSS
         (add.css was not provided — move these rules there if
         it already styles #main_property_photo / #property_photos)
    ====================================================== -->

    <style>

        #main_property_photo,
        #property_photos {
            display: none;
        }

        .photo-upload-box {
            cursor: pointer;
        }

        .main-photo-preview-item {
            position: relative;
        }

        .main-photo-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #2e7d32;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

    </style>

</head>

<body>

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

                        <option value="New Launch">
                            New Launch
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
                        Photos
                    </h3>

                    <p>
                        Add a main property photo and any
                        additional photos.
                    </p>

                </div>


        <!-- =================================================
                     MAIN PROPERTY PHOTO
        ================================================== -->

        <div class="photo-upload-area">

            <label
                for="main_property_photo"
                class="photo-upload-box"
            >

                <span class="upload-icon">
                    +
                </span>

                <strong>
                    Add Main Property Photo
                </strong>

                <small>
                    Select one JPG, JPEG or PNG image
                </small>

            </label>

            <input
                type="file"
                name="main_property_photo"
                id="main_property_photo"
                accept="image/jpeg,image/png,image/jpg"
            >

        </div>


        <!-- =================================================
            MAIN PHOTO PREVIEW
        ================================================== -->

        <div
            id="main-photo-preview"
            class="photo-preview"
        ></div>


        <!-- =================================================
            ADDITIONAL PROPERTY PHOTOS
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
                    Add Additional Photos
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
            ADDITIONAL PHOTO PREVIEW
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

<script src="add.js"></script>


</body>

</html>