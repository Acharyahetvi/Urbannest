<?php
/*
|--------------------------------------------------------------------------
| UrbanNest - Add Property
|--------------------------------------------------------------------------
| Updated flow:
| 1. User must be logged in.
| 2. Logged-in user is automatically attached to the property.
| 3. User chooses whether they are listing as Owner or Agent.
| 4. Existing 7-step property form follows.
| 5. CSS + JavaScript are included in this single PHP file.
| 6. Every failure is shown on screen with the real MySQL/PHP message.
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/db_connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/*
|--------------------------------------------------------------------------
| Logged-in user
|--------------------------------------------------------------------------
| This supports the usual UrbanNest session key: $_SESSION['user_id'].
| A fallback for $_SESSION['id'] is included so the page is tolerant of
| an existing authentication implementation.
|--------------------------------------------------------------------------
*/
$logged_in_user_id = 0;

if (isset($_SESSION['user_id'])) {
    $logged_in_user_id = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['id'])) {
    $logged_in_user_id = (int) $_SESSION['id'];
}

/*
|--------------------------------------------------------------------------
| If not logged in
|--------------------------------------------------------------------------
*/
if ($logged_in_user_id <= 0) {
    $login_url = 'auth/login.php';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Required | UrbanNest</title>
        <style>
            *{box-sizing:border-box}
            body{
                margin:0;
                min-height:100vh;
                display:flex;
                align-items:center;
                justify-content:center;
                padding:25px;
                font-family:Arial,sans-serif;
                background:#f5f6f8;
                color:#172033;
            }
            .login-gate{
                width:min(480px,100%);
                background:#fff;
                border-radius:18px;
                padding:42px;
                text-align:center;
                box-shadow:0 12px 40px rgba(0,0,0,.08);
            }
            .login-gate .icon{
                width:58px;
                height:58px;
                margin:0 auto 18px;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                background:#14213d;
                color:#fff;
                font-size:25px;
            }
            .login-gate h2{margin:0 0 10px;color:#14213d}
            .login-gate p{margin:0 0 25px;color:#737b89;line-height:1.6}
            .login-btn{
                display:inline-block;
                padding:12px 25px;
                border-radius:8px;
                background:#14213d;
                color:#fff;
                text-decoration:none;
                font-weight:600;
            }
        </style>
    </head>
    <body>
        <div class="login-gate">
            <div class="icon">↪</div>
            <h2>Login Required</h2>
            <p>
                Please sign in to your UrbanNest account before
                listing a property.
            </p>
            <a class="login-btn" href="<?php echo htmlspecialchars($login_url); ?>">
                Login to Continue
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

/*
|--------------------------------------------------------------------------
| Fetch logged-in user
|--------------------------------------------------------------------------
*/
$user_stmt = mysqli_prepare(
    $con,
    "SELECT user_id, full_name, email
     FROM users
     WHERE user_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($user_stmt, "i", $logged_in_user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$current_user = mysqli_fetch_assoc($user_result);
mysqli_stmt_close($user_stmt);

if (!$current_user) {
    session_destroy();
    die("Your account could not be found. Please log in again.");
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
    die("Failed to fetch categories: " . mysqli_error($con));
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
     WHERE status = 'Active'
     ORDER BY subcategory_name ASC"
);

if (!$subcategory_result) {
    die("Failed to fetch subcategories: " . mysqli_error($con));
}

while ($row = mysqli_fetch_assoc($subcategory_result)) {
    $subcategories[] = $row;
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
    die("Failed to fetch amenities: " . mysqli_error($con));
}

while ($row = mysqli_fetch_assoc($amenity_result)) {
    $amenities[] = $row;
}

/*
|--------------------------------------------------------------------------
| Visible error page (nothing fails silently any more)
|--------------------------------------------------------------------------
*/
function urbannest_fail($message, $detail = "")
{
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Property not saved | UrbanNest</title>
        <style>
            *{box-sizing:border-box}
            body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:25px;font-family:Arial,sans-serif;background:#f5f6f8;color:#172033}
            .err-box{width:min(640px,100%);background:#fff;border-radius:16px;padding:34px;box-shadow:0 12px 40px rgba(0,0,0,.08);border-top:5px solid #c0392b}
            .err-box h2{margin:0 0 10px;color:#c0392b}
            .err-box p{margin:0 0 14px;line-height:1.6}
            .err-box pre{white-space:pre-wrap;word-break:break-word;background:#f5f6f8;border-radius:8px;padding:12px;font-size:13px;margin:0 0 20px}
            .err-box a{display:inline-block;padding:11px 22px;border-radius:8px;background:#14213d;color:#fff;text-decoration:none;font-weight:600}
        </style>
    </head>
    <body>
        <div class="err-box">
            <h2>Property could not be saved</h2>
            <p><?php echo htmlspecialchars($message); ?></p>
            <?php if ($detail !== "") { ?>
                <pre><?php echo htmlspecialchars($detail); ?></pre>
            <?php } ?>
            <a href="javascript:history.back()">Go back to the form</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

function urbannest_upload_error_text($code)
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            return "The file is larger than upload_max_filesize (" . ini_get("upload_max_filesize") . ") in php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The file is larger than the form limit.";
        case UPLOAD_ERR_PARTIAL:
            return "The file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "PHP has no temporary upload folder (upload_tmp_dir).";
        case UPLOAD_ERR_CANT_WRITE:
            return "PHP could not write the uploaded file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the upload.";
        default:
            return "Unknown upload error code " . (int) $code . ".";
    }
}

/*
|--------------------------------------------------------------------------
| SAVE PROPERTY
|--------------------------------------------------------------------------
| ANY POST to this page is treated as a save attempt. It no longer depends
| on the submit button's name being present in $_POST.
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    | If the request is bigger than post_max_size, PHP throws away ALL of
    | $_POST and $_FILES without any error. Detect and report that.
    */
    if (
        empty($_POST) &&
        empty($_FILES) &&
        (int) ($_SERVER["CONTENT_LENGTH"] ?? 0) > 0
    ) {
        urbannest_fail(
            "The submitted data was larger than the server allows, so PHP discarded the whole form.",
            "post_max_size = " . ini_get("post_max_size") .
            "\nupload_max_filesize = " . ini_get("upload_max_filesize") .
            "\nmax_file_uploads = " . ini_get("max_file_uploads") .
            "\nIncrease these values in php.ini (XAMPP: C:\\xampp\\php\\php.ini) and restart Apache, or upload smaller / fewer images."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Logged-in user is ALWAYS the property user
    |--------------------------------------------------------------------------
    */
    $user_id = $logged_in_user_id;

    /*
    | Owner / Agent listing choice. The `properties` table has no column for
    | it, so it is validated but NOT stored, and users.role_id is untouched.
    */
    $listing_role = trim($_POST["listing_role"] ?? "");

    if (!in_array($listing_role, ["Owner", "Agent"], true)) {
        urbannest_fail("Please select whether you are listing as Owner or Agent.");
    }

    $subcategory_id = isset($_POST["subcategory_id"])
        ? (int) $_POST["subcategory_id"]
        : 0;

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $listing_type = trim($_POST["listing_type"] ?? "");

    $price_raw = trim((string) ($_POST["price"] ?? ""));
    $price_number = is_numeric($price_raw) ? (float) $price_raw : 0;
    // properties.price is DECIMAL(50,0): send it as an exact whole-number string.
    $price = number_format($price_number, 0, ".", "");

    $area_sqft = isset($_POST["area_sqft"])
        ? (int) $_POST["area_sqft"]
        : 0;

    $property_condition =
        isset($_POST["property_condition"]) &&
        trim($_POST["property_condition"]) !== ""
            ? trim($_POST["property_condition"])
            : null;

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

    $address = trim($_POST["address"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $state = trim($_POST["state"] ?? "");

    /*
    | Required-field validation.
    */
    if (
        $subcategory_id <= 0 ||
        $title === "" ||
        $description === "" ||
        $listing_type === "" ||
        $price_number <= 0 ||
        $area_sqft <= 0 ||
        $address === "" ||
        $city === "" ||
        $state === ""
    ) {
        urbannest_fail(
            "Please complete all required property information.",
            "subcategory_id=" . $subcategory_id .
            "\ntitle=" . ($title === "" ? "(empty)" : "ok") .
            "\ndescription=" . ($description === "" ? "(empty)" : "ok") .
            "\nlisting_type=" . ($listing_type === "" ? "(empty)" : $listing_type) .
            "\nprice=" . $price_raw .
            "\narea_sqft=" . $area_sqft .
            "\naddress=" . ($address === "" ? "(empty)" : "ok") .
            "\ncity=" . ($city === "" ? "(empty)" : "ok") .
            "\nstate=" . ($state === "" ? "(empty)" : "ok")
        );
    }

    /*
    | Values must match the ENUM columns in the database exactly.
    */
    if (!in_array($listing_type, ["For Sale", "For Rent"], true)) {
        urbannest_fail("Invalid listing type.", "Received: " . $listing_type);
    }

    if (
        $property_condition !== null &&
        !in_array(
            $property_condition,
            ["Ready to Move", "Under Construction", "New Launch", "Resale", "Ready to Build"],
            true
        )
    ) {
        urbannest_fail("Invalid property condition.", "Received: " . $property_condition);
    }

    if (
        $furnishing !== null &&
        !in_array($furnishing, ["Unfurnished", "Semi-Furnished", "Furnished"], true)
    ) {
        urbannest_fail("Invalid furnishing value.", "Received: " . $furnishing);
    }

    /*
    | Foreign keys: the subcategory must exist (properties_ibfk_2).
    */
    $found_subcategory = false;

    foreach ($subcategories as $sub_row) {
        if ((int) $sub_row["subcategory_id"] === $subcategory_id) {
            $found_subcategory = true;
            break;
        }
    }

    if (!$found_subcategory) {
        urbannest_fail(
            "The selected subcategory does not exist or is inactive.",
            "subcategory_id = " . $subcategory_id
        );
    }

    /*
    | Foreign keys: every selected amenity must exist (property_amenities_ibfk_2).
    */
    $valid_amenity_ids = [];

    foreach ($amenities as $amenity_row) {
        $valid_amenity_ids[(int) $amenity_row["amenity_id"]] = true;
    }

    $selected_amenities = [];

    if (isset($_POST["amenities"]) && is_array($_POST["amenities"])) {
        foreach ($_POST["amenities"] as $posted_amenity_id) {
            $posted_amenity_id = (int) $posted_amenity_id;

            if ($posted_amenity_id <= 0) {
                continue;
            }

            if (!isset($valid_amenity_ids[$posted_amenity_id])) {
                urbannest_fail(
                    "A selected amenity does not exist.",
                    "amenity_id = " . $posted_amenity_id
                );
            }

            $selected_amenities[$posted_amenity_id] = $posted_amenity_id;
        }
    }

    /*
    | The main photo is required (same rule as the JavaScript on step 6).
    */
    if (
        !isset($_FILES["main_property_photo"]) ||
        $_FILES["main_property_photo"]["error"] !== UPLOAD_ERR_OK
    ) {
        $main_error = isset($_FILES["main_property_photo"])
            ? urbannest_upload_error_text($_FILES["main_property_photo"]["error"])
            : "No main photo field was received.";

        urbannest_fail("The main property photo could not be uploaded.", $main_error);
    }

    $moved_files = [];

    mysqli_begin_transaction($con);

    try {

        /*
        |--------------------------------------------------------------------------
        | Insert Property
        |--------------------------------------------------------------------------
        | 18 columns, 18 placeholders, 18 bind types:
        | user_id i, subcategory_id i, title s, description s, listing_type s,
        | price s (DECIMAL), area_sqft i, bedrooms i, bathrooms i, furnishing s,
        | floor_number i, total_floors i, plot_length d, plot_width d,
        | address s, city s, state s, property_condition s
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
                approval_status,
                status,
                property_condition
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Active', ?)
        ";

        $property_stmt = mysqli_prepare($con, $property_sql);

        if (!$property_stmt) {
            throw new Exception("Could not prepare property query: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param(
            $property_stmt,
            "iissssiiisiiddssss",
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

        if (!mysqli_stmt_execute($property_stmt)) {
            throw new Exception(mysqli_stmt_error($property_stmt));
        }

        $property_id = (int) mysqli_insert_id($con);
        mysqli_stmt_close($property_stmt);

        if ($property_id <= 0) {
            throw new Exception("The property row was not created (no insert id returned).");
        }

        /*
        |--------------------------------------------------------------------------
        | Save Amenities  (table: property_amenities, columns: property_id, amenity_id)
        |--------------------------------------------------------------------------
        */
        if (count($selected_amenities) > 0) {

            $amenity_stmt = mysqli_prepare(
                $con,
                "INSERT INTO property_amenities
                 (property_id, amenity_id)
                 VALUES (?, ?)"
            );

            if (!$amenity_stmt) {
                throw new Exception("Could not prepare amenity query: " . mysqli_error($con));
            }

            foreach ($selected_amenities as $amenity_id) {

                mysqli_stmt_bind_param(
                    $amenity_stmt,
                    "ii",
                    $property_id,
                    $amenity_id
                );

                if (!mysqli_stmt_execute($amenity_stmt)) {
                    throw new Exception(mysqli_stmt_error($amenity_stmt));
                }
            }

            mysqli_stmt_close($amenity_stmt);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Images  (table: property_images: property_id, image_path, is_main)
        |--------------------------------------------------------------------------
        */
        $local_image_root = __DIR__ . "/assets/images/property";
        $admin_image_root = __DIR__ . "/admin/assets/images/property";

        /*
        | If this page is on the user side, use admin/assets/images/property.
        | If this page is already inside admin/properties, use assets/images/property.
        */
        if (is_dir(__DIR__ . "/admin/assets/images")) {
            $upload_root = $admin_image_root;
        } else {
            $upload_root = $local_image_root;
        }

        $upload_directory =
            $upload_root . "/property" . $property_id . "/";

        if (!is_dir($upload_directory)) {
            if (!mkdir($upload_directory, 0777, true)) {
                throw new Exception(
                    "Could not create image folder: " . $upload_directory
                );
            }
        }

        if (!is_writable($upload_directory)) {
            throw new Exception(
                "Image folder is not writable: " . $upload_directory
            );
        }

        $all_files = [];

        /*
        | Main photo goes first, so it becomes is_main = 1.
        */
        $all_files[] = [
            "name" => $_FILES["main_property_photo"]["name"],
            "tmp_name" => $_FILES["main_property_photo"]["tmp_name"],
            "error" => $_FILES["main_property_photo"]["error"],
            "prefix" => "main"
        ];

        /*
        | Additional photos.
        */
        if (
            isset($_FILES["property_photos"]) &&
            isset($_FILES["property_photos"]["name"]) &&
            is_array($_FILES["property_photos"]["name"])
        ) {
            $photo_count = count($_FILES["property_photos"]["name"]);

            for ($i = 0; $i < $photo_count; $i++) {

                // An empty file input is not an error.
                if ($_FILES["property_photos"]["error"][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $all_files[] = [
                    "name" => $_FILES["property_photos"]["name"][$i],
                    "tmp_name" => $_FILES["property_photos"]["tmp_name"][$i],
                    "error" => $_FILES["property_photos"]["error"][$i],
                    "prefix" => "image"
                ];
            }
        }

        $image_stmt = mysqli_prepare(
            $con,
            "INSERT INTO property_images
             (property_id, image_path, is_main)
             VALUES (?, ?, ?)"
        );

        if (!$image_stmt) {
            throw new Exception("Could not prepare image query: " . mysqli_error($con));
        }

        $saved_photo_index = 0;
        $allowed_extensions = ["jpg", "jpeg", "png"];

        foreach ($all_files as $file) {

            if ($file["error"] !== UPLOAD_ERR_OK) {
                throw new Exception(
                    "Image \"" . $file["name"] . "\" failed to upload: " .
                    urbannest_upload_error_text($file["error"])
                );
            }

            if (!is_uploaded_file($file["tmp_name"])) {
                throw new Exception("Invalid uploaded image: " . $file["name"]);
            }

            $image_info = @getimagesize($file["tmp_name"]);

            if ($image_info === false) {
                throw new Exception(
                    "\"" . $file["name"] . "\" is not a valid image."
                );
            }

            $extension = strtolower(
                pathinfo($file["name"], PATHINFO_EXTENSION)
            );

            if (!in_array($extension, $allowed_extensions, true)) {
                throw new Exception(
                    "Only JPG, JPEG and PNG images are allowed (\"" . $file["name"] . "\")."
                );
            }

            $new_filename =
                $file["prefix"] . "_" .
                bin2hex(random_bytes(6)) .
                "." . $extension;

            $destination = $upload_directory . $new_filename;

            if (!move_uploaded_file($file["tmp_name"], $destination)) {
                throw new Exception(
                    "Could not move uploaded image to: " . $destination
                );
            }

            $moved_files[] = $destination;

            $is_main = ($saved_photo_index === 0) ? 1 : 0;

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

            if (!mysqli_stmt_execute($image_stmt)) {
                throw new Exception(mysqli_stmt_error($image_stmt));
            }

            $saved_photo_index++;
        }

        mysqli_stmt_close($image_stmt);

        mysqli_commit($con);

        $redirect_target = basename($_SERVER["SCRIPT_NAME"]);

        echo "<script>
            alert('Property added successfully!');
            window.location.href = " . json_encode($redirect_target) . ";
        </script>";
        exit;

    } catch (Throwable $e) {

        mysqli_rollback($con);

        // Do not leave orphan image files for a row that was rolled back.
        foreach ($moved_files as $moved_file) {
            if (is_file($moved_file)) {
                @unlink($moved_file);
            }
        }

        urbannest_fail(
            "The database or upload step failed, so nothing was saved.",
            get_class($e) . ": " . $e->getMessage()
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Property | UrbanNest</title>

<style>
/* =========================================================
   UrbanNest - Add Property
   CSS is intentionally inside this PHP file.
========================================================= */

*{box-sizing:border-box}

body{
    margin:0;
    font-family:Arial,sans-serif;
    background:#f5f6f8;
    color:#172033;
}

.property-container{
    width:min(1100px,92%);
    margin:35px auto;
    background:#fff;
    border-radius:16px;
    padding:34px 42px;
    box-shadow:0 8px 30px rgba(0,0,0,.08);
}

.page-header{
    text-align:center;
    margin-bottom:32px;
}

.page-header h2{
    margin:0 0 7px;
    font-size:30px;
    color:#14213d;
}

.page-header p{
    margin:0;
    color:#737b89;
    font-size:14px;
}

/* User information gate */
.user-card{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
    margin-bottom:35px;
    padding:22px;
    background:#f8f9fb;
    border:1px solid #e6e8ed;
    border-radius:12px;
}

.user-card h3{
    grid-column:1/-1;
    margin:0;
    color:#14213d;
    font-size:18px;
}

.user-field label{
    display:block;
    margin-bottom:7px;
    font-size:13px;
    font-weight:600;
    color:#555f70;
}

.user-field input{
    width:100%;
    padding:12px 13px;
    border:1px solid #d8dce3;
    border-radius:8px;
    background:#fff;
    color:#172033;
}

.role-choice{
    grid-column:1/-1;
}

.role-options{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
}

.role-option{
    flex:1;
    min-width:180px;
    position:relative;
}

.role-option input{
    position:absolute;
    opacity:0;
}

.role-option label{
    display:block;
    padding:15px 18px;
    border:1px solid #d8dce3;
    border-radius:10px;
    cursor:pointer;
    background:#fff;
    transition:.2s;
}

.role-option input:checked + label{
    border-color:#14213d;
    background:#f0f3f8;
}

.role-option strong{
    display:block;
    color:#14213d;
    margin-bottom:4px;
}

.role-option small{
    color:#737b89;
}

/* Listing information first screen */
.listing-intro{
    max-width:850px;
    margin:0 auto;
}

.listing-intro .user-card{
    margin-bottom:24px;
}

.listing-start-actions{
    display:flex;
    justify-content:flex-end;
    padding-top:4px;
}

.listing-start-btn{
    border:0;
    border-radius:8px;
    padding:12px 22px;
    background:#14213d;
    color:#fff;
    font:inherit;
    font-weight:600;
    cursor:pointer;
}

.listing-start-btn:hover{
    background:#0b1f3a;
}

#property-workflow{
    display:none !important;
}

#property-workflow.workflow-visible{
    display:block !important;
}

/* Stepper */
.property-stepper{
    display:flex;
    align-items:flex-start;
    width:100%;
    margin-bottom:40px;
}

.property-stepper .step{
    display:flex;
    flex-direction:column;
    align-items:center;
    min-width:72px;
}

.step-circle{
    width:38px;
    height:38px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#e5e7eb;
    color:#6b7280;
    font-size:14px;
    font-weight:600;
    border:2px solid #e5e7eb;
}

.property-stepper .step span{
    margin-top:8px;
    font-size:12px;
    color:#737b89;
    white-space:nowrap;
}

.property-stepper .step.active .step-circle{
    background:#14213d;
    border-color:#14213d;
    color:#fff;
}

.property-stepper .step.active span{
    color:#14213d;
    font-weight:600;
}

.property-stepper .step.completed .step-circle{
    background:#d4a72c;
    border-color:#d4a72c;
    color:#fff;
}

.property-stepper .step.completed span{
    color:#14213d;
}

.step-line{
    flex:1;
    height:2px;
    background:#e5e7eb;
    margin-top:19px;
    min-width:15px;
}

/* Steps */
.property-step{display:none}
.property-step.active{display:block}

.step-content{max-width:850px;margin:0 auto}

.step-heading{
    margin-bottom:26px;
}

.step-number{
    display:inline-block;
    margin-bottom:7px;
    color:#d4a72c;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
}

.step-heading h3{
    margin:0 0 7px;
    color:#14213d;
    font-size:24px;
}

.step-heading p{
    margin:0;
    color:#737b89;
    font-size:14px;
    line-height:1.5;
}

.form-group{margin-bottom:20px}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#394355;
    font-size:13px;
    font-weight:600;
}

.form-group input,
.form-group select,
.form-group textarea{
    width:100%;
    border:1px solid #d8dce3;
    border-radius:8px;
    padding:12px 13px;
    background:#fff;
    color:#172033;
    font:inherit;
    outline:none;
}

.form-group textarea{
    min-height:110px;
    resize:vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{
    border-color:#14213d;
}

.property-subheading{
    margin:26px 0 18px;
    padding:16px 18px;
    background:#f8f9fb;
    border-left:4px solid #d4a72c;
    border-radius:8px;
}

.property-subheading h4{
    margin:0 0 5px;
    color:#14213d;
}

.property-subheading p{
    margin:0;
    color:#737b89;
    font-size:13px;
}

.dynamic-field{display:none}

.amenities-container{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-bottom:22px;
}

.amenities-container > p{
    grid-column:1/-1;
    color:#737b89;
}

.amenity-option{
    display:flex;
    align-items:center;
    gap:9px;
    padding:11px 12px;
    border:1px solid #e1e4e9;
    border-radius:8px;
    background:#fafbfc;
}

.amenity-option input{
    accent-color:#14213d;
}

.amenity-option label{
    margin:0;
    font-size:13px;
    color:#3d4656;
    cursor:pointer;
}

.photo-upload-area{
    margin:18px 0 10px;
}

.photo-upload-box{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:150px;
    padding:25px;
    border:2px dashed #cdd2db;
    border-radius:12px;
    background:#fafbfc;
    cursor:pointer;
    text-align:center;
    transition:.2s;
}

.photo-upload-box:hover{
    border-color:#14213d;
    background:#f5f7fa;
}

.upload-icon{
    width:40px;
    height:40px;
    margin-bottom:9px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#14213d;
    color:#fff;
    font-size:25px;
}

.photo-upload-box strong{
    color:#14213d;
    margin-bottom:5px;
}

.photo-upload-box small{
    color:#737b89;
}

input[type="file"]{
    display:none;
}

.photo-preview,
.review-photos{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
    margin-top:15px;
}

.preview-item{
    position:relative;
}

.preview-item img{
    width:100%;
    height:130px;
    object-fit:cover;
    border-radius:9px;
    border:1px solid #e1e4e9;
}

.main-badge{
    position:absolute;
    left:7px;
    top:7px;
    padding:4px 7px;
    border-radius:5px;
    background:#14213d;
    color:#fff;
    font-size:10px;
}

.step-actions{
    display:flex;
    justify-content:space-between;
    gap:12px;
    margin-top:30px;
    padding-top:22px;
    border-top:1px solid #eceef2;
}

.back-btn,
.next-btn,
.save-btn{
    border:0;
    border-radius:8px;
    padding:12px 22px;
    font:inherit;
    font-weight:600;
    cursor:pointer;
}

.back-btn{
    background:#eef0f3;
    color:#394355;
}

.next-btn,
.save-btn{
    background:#14213d;
    color:#fff;
}

.save-btn{background:#d4a72c;color:#14213d}

.review-section{
    margin-bottom:22px;
    padding:20px;
    border:1px solid #e2e5ea;
    border-radius:10px;
}

.review-section h4{
    margin:0 0 15px;
    color:#14213d;
}

.review-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.review-item{
    padding:12px;
    border-radius:8px;
    background:#f8f9fb;
}

.review-item span{
    display:block;
    margin-bottom:4px;
    color:#737b89;
    font-size:11px;
    text-transform:uppercase;
}

.review-item strong{
    color:#172033;
    word-break:break-word;
}

.review-description{
    margin:0;
    color:#4c5565;
    line-height:1.6;
}

.review-tags{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.review-tag{
    padding:7px 10px;
    border-radius:20px;
    background:#eef1f5;
    color:#394355;
    font-size:12px;
}

@media(max-width:800px){
    .property-container{padding:25px 20px}
    .property-stepper{overflow-x:auto;padding-bottom:8px}
    .user-card{grid-template-columns:1fr}
    .user-card h3,.role-choice{grid-column:auto}
    .amenities-container{grid-template-columns:repeat(2,1fr)}
    .photo-preview,.review-photos{grid-template-columns:repeat(2,1fr)}
}

@media(max-width:520px){
    .property-container{width:96%;margin:15px auto}
    .amenities-container{grid-template-columns:1fr}
    .review-grid{grid-template-columns:1fr}
    .photo-preview,.review-photos{grid-template-columns:1fr}
}
</style>
</head>

<body>

<div class="property-container">

    <div class="page-header">
        <h2>Add Property</h2>
        <p>List your property on UrbanNest</p>
    </div>

    <!-- =====================================================
         FIRST SCREEN — LOGGED-IN USER / LISTING ROLE
    ====================================================== -->
    <section class="listing-intro" id="listing-intro" style="display:block;">

    <div class="user-card">

        <h3>Your Listing Information</h3>

        <div class="user-field">
            <label for="display_name">Your Name</label>
            <input
                type="text"
                id="display_name"
                value="<?php echo htmlspecialchars($current_user['full_name']); ?>"
                readonly
            >
        </div>

        <div class="user-field">
            <label for="display_email">Email ID</label>
            <input
                type="email"
                id="display_email"
                value="<?php echo htmlspecialchars($current_user['email']); ?>"
                readonly
            >
        </div>

        <div class="role-choice">
            <label style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#555f70;">
                You are listing this property as
            </label>

            <div class="role-options">

                <div class="role-option">
                    <input
                        type="radio"
                        name="listing_role"
                        id="listing_owner"
                        value="Owner"
                        form="propertyForm"
                        required
                    >
                    <label for="listing_owner">
                        <strong>Owner</strong>
                        <small>I own this property.</small>
                    </label>
                </div>

                <div class="role-option">
                    <input
                        type="radio"
                        name="listing_role"
                        id="listing_agent"
                        value="Agent"
                        form="propertyForm"
                        required
                    >
                    <label for="listing_agent">
                        <strong>Agent</strong>
                        <small>I am listing this property as an agent.</small>
                    </label>
                </div>

            </div>
        </div>

    </div>


        <div class="listing-start-actions">
            <button type="button" class="listing-start-btn" onclick="startPropertyForm()">
                Continue
            </button>
        </div>

    </section>

    <div id="property-workflow" style="display:none;">

    <!-- =====================================================
         STEPPER
    ====================================================== -->
    <div class="property-stepper">

        <div class="step active"><div class="step-circle">1</div><span>Type</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">2</div><span>Details</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">3</div><span>Specifications</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">4</div><span>Location</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">5</div><span>Amenities</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">6</div><span>Photos</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle">7</div><span>Review</span></div>

    </div>

    <form
        method="POST"
        id="propertyForm"
        enctype="multipart/form-data"
    >

        <!-- Always sent, so the save never depends on the submit button's name -->
        <input type="hidden" name="save_property" value="1">

        <!-- =================================================
             STEP 1 — PROPERTY TYPE
        ================================================== -->
        <section class="property-step active" id="step-1">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 1</span>
                    <h3>Property Type</h3>
                    <p>Select the category and subcategory for your property.</p>
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">-- Select Category --</option>

                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo (int) $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="subcategory_id">Subcategory</label>
                    <select
                        name="subcategory_id"
                        id="subcategory_id"
                        required
                        disabled
                    >
                        <option value="">-- Select Category First --</option>
                    </select>
                </div>

                <div class="step-actions">
                    <span></span>
                    <button type="button" class="next-btn" onclick="nextStep()">
                        Continue
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================
             STEP 2 — DETAILS
        ================================================== -->
        <section class="property-step" id="step-2">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 2</span>
                    <h3>Property Details</h3>
                    <p>Enter the basic information and specifications.</p>
                </div>

                <div class="form-group">
                    <label for="title">Property Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        placeholder="Enter property title"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        placeholder="Describe the property..."
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="listing_type">Listing Type</label>
                    <select name="listing_type" id="listing_type" required>
                        <option value="">-- Select Listing Type --</option>
                        <option value="For Sale">For Sale</option>
                        <option value="For Rent">For Rent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        min="1"
                        step="1"
                        placeholder="Enter property price"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="area_sqft">Area (sq.ft.)</label>
                    <input
                        type="number"
                        name="area_sqft"
                        id="area_sqft"
                        min="1"
                        step="1"
                        placeholder="Enter area in sq.ft."
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="property_condition">Property Condition</label>
                    <select name="property_condition" id="property_condition">
                        <option value="">-- Select Property Condition --</option>
                        <option value="New Launch">New Launch</option>
                        <option value="Ready to Move">Ready to Move</option>
                        <option value="Under Construction">Under Construction</option>
                        <option value="Resale">Resale</option>
                        <option value="Ready to Build">Ready to Build</option>
                    </select>
                </div>

                <div class="step-actions">
                    <button type="button" class="back-btn" onclick="previousStep()">
                        Back
                    </button>
                    <button type="button" class="next-btn" onclick="nextStep()">
                        Continue
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================
             STEP 3 — LOCATION
        ================================================== -->
        <section class="property-step" id="step-3">
    <div class="step-content">

        <div class="step-heading">
            <span class="step-number">Step 3</span>
            <h3>Property Specifications</h3>
            <p>Enter the specifications based on your selected property type.</p>
        </div>

<div id="dynamic-details" style="display:none">

                    <div class="property-subheading">
                        <h4 id="dynamic-details-title">Property Specifications</h4>
                        <p id="dynamic-details-description">
                            Enter the specifications for this property.
                        </p>
                    </div>

                    <div class="form-group dynamic-field" id="field-bedrooms">
                        <label for="bedrooms">Bedrooms</label>
                        <input
                            type="number"
                            name="bedrooms"
                            id="bedrooms"
                            min="0"
                            placeholder="Enter number of bedrooms"
                        >
                    </div>

                    <div class="form-group dynamic-field" id="field-bathrooms">
                        <label for="bathrooms">Bathrooms</label>
                        <input
                            type="number"
                            name="bathrooms"
                            id="bathrooms"
                            min="0"
                            placeholder="Enter number of bathrooms"
                        >
                    </div>

                    <div class="form-group dynamic-field" id="field-furnishing">
                        <label for="furnishing">Furnishing</label>
                        <select name="furnishing" id="furnishing">
                            <option value="">-- Select Furnishing --</option>
                            <option value="Unfurnished">Unfurnished</option>
                            <option value="Semi-Furnished">Semi-Furnished</option>
                            <option value="Furnished">Furnished</option>
                        </select>
                    </div>

                    <div class="form-group dynamic-field" id="field-floor-number">
                        <label for="floor_number">Floor Number</label>
                        <input
                            type="number"
                            name="floor_number"
                            id="floor_number"
                            min="0"
                            placeholder="Enter floor number"
                        >
                    </div>

                    <div class="form-group dynamic-field" id="field-total-floors">
                        <label for="total_floors">Total Floors</label>
                        <input
                            type="number"
                            name="total_floors"
                            id="total_floors"
                            min="1"
                            placeholder="Enter total floors"
                        >
                    </div>

                    <div class="form-group dynamic-field" id="field-plot-length">
                        <label for="plot_length">Plot Length</label>
                        <input
                            type="number"
                            name="plot_length"
                            id="plot_length"
                            min="0"
                            step="0.01"
                            placeholder="Enter plot length"
                        >
                    </div>

                    <div class="form-group dynamic-field" id="field-plot-width">
                        <label for="plot_width">Plot Width</label>
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
            <button type="button" class="back-btn" onclick="previousStep()">Back</button>
            <button type="button" class="next-btn" onclick="nextStep()">Continue</button>
        </div>

    </div>
</section>

<section class="property-step" id="step-4">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 4</span>
                    <h3>Property Location</h3>
                    <p>Enter the location where the property is situated.</p>
                </div>

                <div class="form-group">
                    <label for="address">Property Address</label>
                    <textarea
                        name="address"
                        id="address"
                        placeholder="Enter the complete property address"
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input
                        type="text"
                        name="city"
                        id="city"
                        placeholder="Enter city"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input
                        type="text"
                        name="state"
                        id="state"
                        placeholder="Enter state"
                        required
                    >
                </div>

                <div class="step-actions">
                    <button type="button" class="back-btn" onclick="previousStep()">
                        Back
                    </button>
                    <button type="button" class="next-btn" onclick="nextStep()">
                        Continue
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================
             STEP 4 — AMENITIES
        ================================================== -->
        <section class="property-step" id="step-5">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 5</span>
                    <h3>Amenities</h3>
                    <p>Select the amenities available at this property.</p>
                </div>

                <div
                    id="amenities-container"
                    class="amenities-container"
                >
                    <p>Select a property type first to see available amenities.</p>
                </div>

                <div class="form-group">
                    <label for="other_amenities">Other Amenities</label>
                    <textarea
                        name="other_amenities"
                        id="other_amenities"
                        placeholder="Don't see an amenity? Write it here..."
                    ></textarea>
                </div>

                <div class="step-actions">
                    <button type="button" class="back-btn" onclick="previousStep()">
                        Back
                    </button>
                    <button type="button" class="next-btn" onclick="nextStep()">
                        Continue
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================
             STEP 5 — PHOTOS
        ================================================== -->
        <section class="property-step" id="step-6">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 6</span>
                    <h3>Property Photos</h3>
                    <p>Add a main photo and additional property photos.</p>
                </div>

                <div class="photo-upload-area">
                    <label
                        for="main_property_photo"
                        class="photo-upload-box"
                    >
                        <span class="upload-icon">+</span>
                        <strong>Add Main Property Photo</strong>
                        <small>Select one JPG, JPEG or PNG image</small>
                    </label>

                    <input
                        type="file"
                        name="main_property_photo"
                        id="main_property_photo"
                        accept="image/jpeg,image/png,image/jpg"
                        required
                    >
                </div>

                <div
                    id="main-photo-preview"
                    class="photo-preview"
                ></div>

                <div class="photo-upload-area">
                    <label
                        for="property_photos"
                        class="photo-upload-box"
                    >
                        <span class="upload-icon">+</span>
                        <strong>Add Additional Photos</strong>
                        <small>Select multiple JPG, JPEG or PNG images</small>
                    </label>

                    <input
                        type="file"
                        name="property_photos[]"
                        id="property_photos"
                        accept="image/jpeg,image/png,image/jpg"
                        multiple
                    >
                </div>

                <div
                    id="photo-preview"
                    class="photo-preview"
                ></div>

                <div class="step-actions">
                    <button type="button" class="back-btn" onclick="previousStep()">
                        Back
                    </button>
                    <button type="button" class="next-btn" onclick="nextStep()">
                        Continue
                    </button>
                </div>

            </div>
        </section>

        <!-- =================================================
             STEP 6 — REVIEW
        ================================================== -->
        <section class="property-step" id="step-7">
            <div class="step-content">

                <div class="step-heading">
                    <span class="step-number">Step 7</span>
                    <h3>Review Property</h3>
                    <p>Review everything before saving your property.</p>
                </div>

                <div class="review-section">
                    <h4>Listing Information</h4>

                    <div class="review-grid">

                        <div class="review-item">
                            <span>Name</span>
                            <strong><?php echo htmlspecialchars($current_user['full_name']); ?></strong>
                        </div>

                        <div class="review-item">
                            <span>Email ID</span>
                            <strong><?php echo htmlspecialchars($current_user['email']); ?></strong>
                        </div>

                        <div class="review-item">
                            <span>Listing As</span>
                            <strong id="review-role">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Property Type</span>
                            <strong id="review-type">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Property Title</span>
                            <strong id="review-title">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Listing Type</span>
                            <strong id="review-listing">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Price</span>
                            <strong id="review-price">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Area</span>
                            <strong id="review-area">—</strong>
                        </div>

                        <div class="review-item">
                            <span>Condition</span>
                            <strong id="review-condition">—</strong>
                        </div>

                    </div>
                </div>

                <div class="review-section">
                    <h4>Description</h4>
                    <p class="review-description" id="review-description">—</p>
                </div>

                <div class="review-section">
                    <h4>Property Specifications</h4>
                    <div class="review-grid" id="review-specifications">
                        <div class="review-item">
                            <span>Specifications</span>
                            <strong>—</strong>
                        </div>
                    </div>
                </div>

                <div class="review-section">
                    <h4>Location</h4>

                    <div class="review-grid">

                        <div class="review-item">
                            <span>Address</span>
                            <strong id="review-address">—</strong>
                        </div>

                        <div class="review-item">
                            <span>City</span>
                            <strong id="review-city">—</strong>
                        </div>

                        <div class="review-item">
                            <span>State</span>
                            <strong id="review-state">—</strong>
                        </div>

                    </div>
                </div>

                <div class="review-section">
                    <h4>Amenities</h4>

                    <div
                        id="review-amenities"
                        class="review-tags"
                    >
                        —
                    </div>

                    <p
                        id="review-other-amenities"
                        style="margin:15px 0 0;color:#737b89"
                    ></p>
                </div>

                <div class="review-section">
                    <h4>Property Photos</h4>

                    <div
                        id="review-photos"
                        class="review-photos"
                    >
                        —
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
    </div><!-- /property-workflow -->
</div>

<script>
/* =========================================================
   UrbanNest - Add Property JavaScript
   JavaScript is intentionally inside this PHP file.
========================================================= */

const subcategories =
    <?php echo json_encode($subcategories, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

const amenities =
    <?php echo json_encode($amenities, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

let currentStep = 1;
const totalSteps = 7;

const propertyFieldConfig = {
    1: ["bedrooms","bathrooms","furnishing","floor-number","total-floors"],
    2: ["bedrooms","bathrooms","furnishing","total-floors"],
    3: ["bedrooms","bathrooms","furnishing","total-floors"],
    4: ["bedrooms","bathrooms","furnishing","total-floors"],
    5: ["bedrooms","bathrooms","furnishing","total-floors"],

    6: ["furnishing","floor-number","total-floors"],
    7: ["furnishing","floor-number","total-floors"],
    8: ["total-floors"],
    9: ["furnishing","floor-number","total-floors"],

    10: ["bedrooms","bathrooms","furnishing","floor-number","total-floors"],
    11: ["bedrooms","bathrooms","furnishing","total-floors"],
    12: ["bedrooms","bathrooms","furnishing","total-floors"],
    13: ["bedrooms","bathrooms","furnishing","total-floors"],

    14: ["plot-length","plot-width"],
    15: ["plot-length","plot-width"],
    16: ["plot-length","plot-width"],

    17: ["total-floors"],
    18: ["total-floors"]
};

const amenityConfig = {
    1: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Lift","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","EV Charging","Gated Community","Pet Friendly"],
    2: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","EV Charging","Gated Community","Pet Friendly"],
    3: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","Gated Community","Pet Friendly"],
    4: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Lift","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","Gated Community","Pet Friendly"],
    5: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Gated Community","Pet Friendly"],

    6: ["Parking","24/7 Security","CCTV Surveillance","Lift","Power Backup","Air Conditioning","EV Charging","Gated Community"],
    7: ["Parking","24/7 Security","CCTV Surveillance","Power Backup","Air Conditioning","Gated Community"],
    8: ["Parking","24/7 Security","CCTV Surveillance","Power Backup","Gated Community"],
    9: ["Parking","24/7 Security","CCTV Surveillance","Lift","Power Backup","Air Conditioning","EV Charging","Gated Community"],

    10: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Lift","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","EV Charging","Gated Community","Pet Friendly"],
    11: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Gated Community","Pet Friendly"],
    12: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","EV Charging","Gated Community","Pet Friendly"],
    13: ["Parking","Swimming Pool","Private Gym","24/7 Security","CCTV Surveillance","Power Backup","Balcony","Air Conditioning","Smart Home","Clubhouse","Gated Community","Pet Friendly"],

    14: ["24/7 Security","CCTV Surveillance","Gated Community"],
    15: ["24/7 Security","CCTV Surveillance","Gated Community"],
    16: ["24/7 Security","CCTV Surveillance","Gated Community"],

    17: ["Parking","24/7 Security","CCTV Surveillance","Power Backup","EV Charging","Gated Community"],
    18: ["Parking","24/7 Security","CCTV Surveillance","Power Backup","EV Charging","Gated Community"]
};

const categorySelect = document.getElementById("category_id");
const subcategorySelect = document.getElementById("subcategory_id");

categorySelect.addEventListener("change", function () {

    const categoryId = this.value;

    subcategorySelect.innerHTML = "";

    if (!categoryId) {
        subcategorySelect.disabled = true;

        const option = document.createElement("option");
        option.value = "";
        option.textContent = "-- Select Category First --";

        subcategorySelect.appendChild(option);

        hideDynamicFields();
        clearAmenities();
        return;
    }

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "-- Select Subcategory --";
    subcategorySelect.appendChild(defaultOption);

    subcategories.forEach(function (subcategory) {

        if (
            String(subcategory.category_id) ===
            String(categoryId)
        ) {

            const option = document.createElement("option");

            option.value = subcategory.subcategory_id;
            option.textContent =
                String(subcategory.subcategory_name).trim();

            subcategorySelect.appendChild(option);
        }
    });

    subcategorySelect.disabled = false;
});

subcategorySelect.addEventListener("change", function () {

    const selected = subcategories.find(function (subcategory) {
        return String(subcategory.subcategory_id) ===
            String(subcategorySelect.value);
    });

    if (!selected) {
        hideDynamicFields();
        clearAmenities();
        return;
    }

    const id = Number(selected.subcategory_id);
    const name = String(selected.subcategory_name).trim();

    showDynamicFields(id, name);
    showAmenities(id);
});

function showDynamicFields(subcategoryId, subcategoryName) {

    hideDynamicFields();

    const fields = propertyFieldConfig[subcategoryId] || [];

    if (!fields.length) {
        return;
    }

    const container = document.getElementById("dynamic-details");
    container.style.display = "block";

    fields.forEach(function (fieldName) {

        const field = document.getElementById(
            "field-" + fieldName
        );

        if (field) {
            field.style.display = "block";
        }
    });

    document.getElementById(
        "dynamic-details-title"
    ).textContent =
        subcategoryName + " Specifications";

    document.getElementById(
        "dynamic-details-description"
    ).textContent =
        "Enter the specifications for this " +
        subcategoryName.toLowerCase() +
        ".";
}

function hideDynamicFields() {

    const container =
        document.getElementById("dynamic-details");

    container.style.display = "none";

    document.querySelectorAll(".dynamic-field").forEach(
        function (field) {
            field.style.display = "none";
        }
    );

    container.querySelectorAll("input, select").forEach(
        function (input) {
            input.value = "";
        }
    );
}

function showAmenities(subcategoryId) {

    const container =
        document.getElementById("amenities-container");

    container.innerHTML = "";

    const allowed =
        amenityConfig[subcategoryId] || [];

    if (!allowed.length) {
        const p = document.createElement("p");
        p.textContent = "No predefined amenities available.";
        container.appendChild(p);
        return;
    }

    amenities.forEach(function (amenity) {

        const amenityName =
            String(amenity.amenity_name).trim();

        if (!allowed.includes(amenityName)) {
            return;
        }

        const wrapper =
            document.createElement("div");

        wrapper.className = "amenity-option";

        const checkbox =
            document.createElement("input");

        checkbox.type = "checkbox";
        checkbox.name = "amenities[]";
        checkbox.value = amenity.amenity_id;
        checkbox.id = "amenity-" + amenity.amenity_id;

        const label =
            document.createElement("label");

        label.htmlFor = checkbox.id;
        label.textContent = amenityName;

        wrapper.appendChild(checkbox);
        wrapper.appendChild(label);

        container.appendChild(wrapper);
    });
}

function clearAmenities() {

    const container =
        document.getElementById("amenities-container");

    container.innerHTML =
        "<p>Select a property type first to see available amenities.</p>";
}

function validateCurrentStep() {

    const section =
        document.getElementById("step-" + currentStep);

    if (!section) {
        return true;
    }
    /*
    | Step 1 category/subcategory.
    */
    if (currentStep === 1) {

        if (!categorySelect.value) {
            alert("Please select a category.");
            categorySelect.focus();
            return false;
        }

        if (!subcategorySelect.value) {
            alert("Please select a subcategory.");
            subcategorySelect.focus();
            return false;
        }
    }

    /*
    | Step 2 required fields.
    */
    if (currentStep === 2) {

        const requiredIds = [
            "title",
            "description",
            "listing_type",
            "price",
            "area_sqft"
        ];

        for (const id of requiredIds) {

            const element =
                document.getElementById(id);

            if (!element.value.trim()) {
                alert("Please complete all required property details.");
                element.focus();
                return false;
            }
        }
    }

    /*
    | Step 4 location.
    */
    if (currentStep === 4) {

        const requiredIds = [
            "address",
            "city",
            "state"
        ];

        for (const id of requiredIds) {

            const element =
                document.getElementById(id);

            if (!element.value.trim()) {
                alert("Please complete the property location.");
                element.focus();
                return false;
            }
        }
    }

    /*
    | Step 6 photos.
    */
    if (currentStep === 6) {

        const mainPhoto =
            document.getElementById("main_property_photo");

        if (!mainPhoto.files.length) {
            alert("Please select a main property photo.");
            return false;
        }
    }

    return true;
}

function validateAllRequiredFields() {

    const role = document.querySelector(
        'input[name="listing_role"]:checked'
    );

    if (!role) {
        alert("Please select Owner or Agent.");
        return false;
    }

    const requiredIds = [
        "category_id",
        "subcategory_id",
        "title",
        "description",
        "listing_type",
        "price",
        "area_sqft",
        "address",
        "city",
        "state"
    ];

    for (const id of requiredIds) {

        const element = document.getElementById(id);

        if (!element || !String(element.value).trim()) {
            alert("Please complete all required property information.");
            if (element) {
                element.focus();
            }
            return false;
        }
    }

    const mainPhoto = document.getElementById("main_property_photo");

    if (!mainPhoto || !mainPhoto.files.length) {
        alert("Please select a main property photo.");
        return false;
    }

    return true;
}

function startPropertyForm() {

    const role = document.querySelector('input[name="listing_role"]:checked');

    if (!role) {
        alert("Please select Owner or Agent before continuing.");
        return;
    }

    const intro = document.getElementById("listing-intro");
    const workflow = document.getElementById("property-workflow");

    // Hide the listing-information screen completely.
    intro.style.display = "none";

    // Show the 7-step property form.
    workflow.classList.add("workflow-visible");
    workflow.style.setProperty("display", "block", "important");

    currentStep = 1;
    showStep(1);

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

function showListingIntro() {

    const intro = document.getElementById("listing-intro");
    const workflow = document.getElementById("property-workflow");

    workflow.classList.remove("workflow-visible");
    workflow.style.setProperty("display", "none", "important");
    intro.style.display = "block";

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

function showStep(stepNumber) {

    document.querySelectorAll(".property-step").forEach(
        function (step) {
            step.classList.remove("active");
        }
    );

    const current =
        document.getElementById("step-" + stepNumber);

    if (current) {
        current.classList.add("active");
    }

    document.querySelectorAll(
        ".property-stepper .step"
    ).forEach(function (indicator, index) {

        indicator.classList.remove(
            "active",
            "completed"
        );

        if (index + 1 === stepNumber) {
            indicator.classList.add("active");
        }

        if (index + 1 < stepNumber) {
            indicator.classList.add("completed");
        }
    });

    currentStep = stepNumber;

    if (stepNumber === totalSteps) {
        buildPropertyReview();
    }

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

function nextStep() {

    if (!validateCurrentStep()) {
        return;
    }

    if (currentStep < totalSteps) {
        showStep(currentStep + 1);
    }
}

function previousStep() {

    if (currentStep > 1) {
        showStep(currentStep - 1);
    } else {
        showListingIntro();
    }
}

function valueOf(id) {

    const element =
        document.getElementById(id);

    return element
        ? element.value.trim()
        : "";
}

function buildPropertyReview() {

    const role =
        document.querySelector(
            'input[name="listing_role"]:checked'
        );

    document.getElementById("review-role").textContent =
        role ? role.value : "—";

    const selectedSubcategory =
        subcategories.find(function (subcategory) {

            return String(subcategory.subcategory_id) ===
                String(subcategorySelect.value);

        });

    const typeText =
        selectedSubcategory
            ? String(
                selectedSubcategory.subcategory_name
            ).trim()
            : "—";

    document.getElementById("review-type").textContent =
        typeText;

    document.getElementById("review-title").textContent =
        valueOf("title") || "—";

    document.getElementById("review-listing").textContent =
        valueOf("listing_type") || "—";

    document.getElementById("review-price").textContent =
        valueOf("price") || "—";

    document.getElementById("review-area").textContent =
        valueOf("area_sqft")
            ? valueOf("area_sqft") + " sq.ft."
            : "—";

    document.getElementById("review-condition").textContent =
        valueOf("property_condition") || "—";

    document.getElementById("review-description").textContent =
        valueOf("description") || "—";

    document.getElementById("review-address").textContent =
        valueOf("address") || "—";

    document.getElementById("review-city").textContent =
        valueOf("city") || "—";

    document.getElementById("review-state").textContent =
        valueOf("state") || "—";

    buildSpecificationReview();
    buildAmenityReview();
    buildPhotoReview();
}

function buildSpecificationReview() {

    const container =
        document.getElementById(
            "review-specifications"
        );

    container.innerHTML = "";

    const fields = [
        ["Bedrooms", "bedrooms"],
        ["Bathrooms", "bathrooms"],
        ["Furnishing", "furnishing"],
        ["Floor Number", "floor_number"],
        ["Total Floors", "total_floors"],
        ["Plot Length", "plot_length"],
        ["Plot Width", "plot_width"]
    ];

    let found = false;

    fields.forEach(function (pair) {

        const value = valueOf(pair[1]);

        if (!value) {
            return;
        }

        found = true;

        const item =
            document.createElement("div");

        item.className = "review-item";

        item.innerHTML =
            "<span>" +
            pair[0] +
            "</span><strong>" +
            escapeHtml(value) +
            "</strong>";

        container.appendChild(item);
    });

    if (!found) {

        const item =
            document.createElement("div");

        item.className = "review-item";

        item.innerHTML =
            "<span>Specifications</span><strong>Not applicable</strong>";

        container.appendChild(item);
    }
}

function buildAmenityReview() {

    const container =
        document.getElementById(
            "review-amenities"
        );

    container.innerHTML = "";

    const checked =
        document.querySelectorAll(
            '#amenities-container input[name="amenities[]"]:checked'
        );

    if (!checked.length) {

        container.textContent =
            "No predefined amenities selected.";

    } else {

        checked.forEach(function (checkbox) {

            const label =
                document.querySelector(
                    'label[for="' +
                    checkbox.id +
                    '"]'
                );

            const tag =
                document.createElement("span");

            tag.className = "review-tag";

            tag.textContent =
                label
                    ? label.textContent
                    : "Amenity";

            container.appendChild(tag);
        });
    }

    const other =
        valueOf("other_amenities");

    document.getElementById(
        "review-other-amenities"
    ).textContent =
        other
            ? "Other: " + other
            : "";
}

function buildPhotoReview() {

    const container =
        document.getElementById("review-photos");

    container.innerHTML = "";

    const mainInput =
        document.getElementById(
            "main_property_photo"
        );

    const additionalInput =
        document.getElementById(
            "property_photos"
        );

    if (mainInput.files.length) {

        const file =
            mainInput.files[0];

        addReviewPhoto(
            container,
            file,
            true
        );
    }

    if (additionalInput.files.length) {

        Array.from(
            additionalInput.files
        ).forEach(function (file) {

            addReviewPhoto(
                container,
                file,
                false
            );
        });
    }

    if (!container.children.length) {
        container.textContent = "No photos selected.";
    }
}

function addReviewPhoto(
    container,
    file,
    isMain
) {

    const wrapper =
        document.createElement("div");

    wrapper.className =
        "preview-item";

    const image =
        document.createElement("img");

    image.src =
        URL.createObjectURL(file);

    image.alt =
        file.name;

    wrapper.appendChild(image);

    if (isMain) {

        const badge =
            document.createElement("span");

        badge.className =
            "main-badge";

        badge.textContent =
            "Main Photo";

        wrapper.appendChild(badge);
    }

    container.appendChild(wrapper);
}

function previewMainPhoto() {

    const input =
        document.getElementById(
            "main_property_photo"
        );

    const container =
        document.getElementById(
            "main-photo-preview"
        );

    container.innerHTML = "";

    if (!input.files.length) {
        return;
    }

    addReviewPhoto(
        container,
        input.files[0],
        true
    );
}

function previewAdditionalPhotos() {

    const input =
        document.getElementById(
            "property_photos"
        );

    const container =
        document.getElementById(
            "photo-preview"
        );

    container.innerHTML = "";

    Array.from(input.files).forEach(
        function (file) {

            addReviewPhoto(
                container,
                file,
                false
            );
        }
    );
}

function escapeHtml(value) {

    return String(value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

document.getElementById(
    "main_property_photo"
).addEventListener(
    "change",
    previewMainPhoto
);

document.getElementById(
    "property_photos"
).addEventListener(
    "change",
    previewAdditionalPhotos
);

document.getElementById(
    "propertyForm"
).addEventListener(
    "submit",
    function (event) {

        if (!validateAllRequiredFields()) {
            event.preventDefault();
            return;
        }

        const role =
            document.querySelector(
                'input[name="listing_role"]:checked'
            );

        if (!role) {
            event.preventDefault();
            alert("Please select Owner or Agent.");
            return;
        }

        /*
        | Prevent accidental double submission.
        |
        | IMPORTANT: the button must NOT be disabled synchronously here.
        | Browsers build the POST data AFTER this handler returns and leave
        | out a disabled submit button, which used to drop "save_property"
        | from $_POST. Disabling is deferred until the data is collected.
        */
        const button =
            this.querySelector(
                'button[name="save_property"]'
            );

        if (button) {
            setTimeout(function () {
                button.disabled = true;
                button.textContent = "Saving...";
            }, 0);
        }
    }
);

/*
| If the user comes back with the browser Back button, make the
| Save button usable again.
*/
window.addEventListener("pageshow", function () {
    const saveButton = document.querySelector(
        'button[name="save_property"]'
    );

    if (saveButton) {
        saveButton.disabled = false;
        saveButton.textContent = "Save Property";
    }
});
</script>

</body>
</html>