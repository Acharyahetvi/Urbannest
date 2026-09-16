<?php
require_once "../db_connection.php";

$message = "";
$message_type = "";

$property = null;
$existing_images = [];


/* ==========================================
   FIND PROPERTY
========================================== */

if (isset($_POST['find_property'])) {

    $property_id = (int)$_POST['property_id'];

    if ($property_id > 0) {

        $stmt = $conn->prepare("
            SELECT 
                property_id,
                title,
                city,
                listing_type,
                price,
                area_sqft,
                bedrooms,
                bathrooms,
                furnishing
            FROM properties
            WHERE property_id = ?
        ");

        $stmt->bind_param("i", $property_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $property = $result->fetch_assoc();

        if ($property) {

            $img_stmt = $conn->prepare("
                SELECT image_id, image_path, is_main
                FROM property_images
                WHERE property_id = ?
                ORDER BY is_main DESC, image_id ASC
            ");

            $img_stmt->bind_param("i", $property_id);
            $img_stmt->execute();

            $existing_images =
                $img_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        } else {

            $message = "Property not found.";
            $message_type = "danger";
        }

    } else {

        $message = "Please enter a valid Property ID.";
        $message_type = "danger";
    }
}


/* ==========================================
   UPLOAD PHOTOS
========================================== */

if (isset($_POST['upload_photos'])) {

    $property_id = (int)$_POST['property_id'];

    /* Check property exists */

    $stmt = $conn->prepare("
        SELECT 
            property_id,
            title,
            city,
            listing_type,
            price,
            area_sqft,
            bedrooms,
            bathrooms,
            furnishing
        FROM properties
        WHERE property_id = ?
    ");

    $stmt->bind_param("i", $property_id);
    $stmt->execute();

    $property_result = $stmt->get_result();
    $property = $property_result->fetch_assoc();


    if (!$property) {

        $message = "Property not found.";
        $message_type = "danger";

    } else {

        $upload_dir =
            "../assets/images/property/property" .
            $property_id . "/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $uploaded_count = 0;

        $conn->begin_transaction();

        try {

            /* ==========================================
               MAIN PHOTO
            ========================================== */

            if (
                isset($_FILES['main_property_photo']) &&
                $_FILES['main_property_photo']['error'] === UPLOAD_ERR_OK
            ) {

                $tmp_name =
                    $_FILES['main_property_photo']['tmp_name'];

                $image_info = getimagesize($tmp_name);

                if ($image_info === false) {
                    throw new Exception(
                        "Main photo is not a valid image."
                    );
                }

                $allowed_types = [
                    IMAGETYPE_JPEG,
                    IMAGETYPE_PNG
                ];

                if (!in_array($image_info[2], $allowed_types)) {
                    throw new Exception(
                        "Main photo must be JPG, JPEG or PNG."
                    );
                }

                $extension = strtolower(
                    pathinfo(
                        $_FILES['main_property_photo']['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $filename =
                    "main_" . uniqid() . "." . $extension;

                $destination = $upload_dir . $filename;

                if (!move_uploaded_file(
                    $tmp_name,
                    $destination
                )) {

                    throw new Exception(
                        "Failed to upload main photo."
                    );
                }

                $image_path =
                    "assets/images/property/property" .
                    $property_id . "/" . $filename;


                /* Make previous main photo non-main */

                $conn->query("
                    UPDATE property_images
                    SET is_main = 0
                    WHERE property_id = $property_id
                ");


                $stmt = $conn->prepare("
                    INSERT INTO property_images
                    (property_id, image_path, is_main)
                    VALUES (?, ?, 1)
                ");

                $stmt->bind_param(
                    "is",
                    $property_id,
                    $image_path
                );

                $stmt->execute();

                $uploaded_count++;
            }


            /* ==========================================
               ADDITIONAL PHOTOS
            ========================================== */

            if (
                isset($_FILES['property_photos']) &&
                is_array($_FILES['property_photos']['name'])
            ) {

                $file_count =
                    count($_FILES['property_photos']['name']);

                for ($i = 0; $i < $file_count; $i++) {

                    if (
                        $_FILES['property_photos']['error'][$i]
                        !== UPLOAD_ERR_OK
                    ) {
                        continue;
                    }

                    $tmp_name =
                        $_FILES['property_photos']['tmp_name'][$i];

                    $image_info = getimagesize($tmp_name);

                    if ($image_info === false) {
                        continue;
                    }

                    $allowed_types = [
                        IMAGETYPE_JPEG,
                        IMAGETYPE_PNG
                    ];

                    if (!in_array(
                        $image_info[2],
                        $allowed_types
                    )) {
                        continue;
                    }

                    $extension = strtolower(
                        pathinfo(
                            $_FILES['property_photos']['name'][$i],
                            PATHINFO_EXTENSION
                        )
                    );

                    $filename =
                        "image_" . uniqid() . "." . $extension;

                    $destination =
                        $upload_dir . $filename;

                    if (
                        move_uploaded_file(
                            $tmp_name,
                            $destination
                        )
                    ) {

                        $image_path =
                            "assets/images/property/property" .
                            $property_id . "/" . $filename;

                        $stmt = $conn->prepare("
                            INSERT INTO property_images
                            (property_id, image_path, is_main)
                            VALUES (?, ?, 0)
                        ");

                        $stmt->bind_param(
                            "is",
                            $property_id,
                            $image_path
                        );

                        $stmt->execute();

                        $uploaded_count++;
                    }
                }
            }


            $conn->commit();


            if ($uploaded_count > 0) {

                $message =
                    $uploaded_count .
                    " photo(s) uploaded successfully.";

                $message_type = "success";

            } else {

                $message =
                    "No photos were uploaded.";

                $message_type = "warning";
            }


            /* Get updated images */

            $img_stmt = $conn->prepare("
                SELECT image_id, image_path, is_main
                FROM property_images
                WHERE property_id = ?
                ORDER BY is_main DESC, image_id ASC
            ");

            $img_stmt->bind_param(
                "i",
                $property_id
            );

            $img_stmt->execute();

            $existing_images =
                $img_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {

            $conn->rollback();

            $message = $e->getMessage();
            $message_type = "danger";
        }
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

    <title>Add Property Photos | UrbanNest</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f6f8;
            font-family: Arial, sans-serif;
        }

        .photo-container {
            max-width: 950px;
            margin: 50px auto;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .property-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .property-info h4 {
            margin-bottom: 15px;
            color: #172554;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .main-label {
            font-weight: 600;
        }

        .preview-section {
            margin-top: 15px;
        }

        .preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 12px;
        }

        .photo-preview-card {
            width: 150px;
            position: relative;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .photo-preview-card img {
            width: 100%;
            height: 105px;
            object-fit: cover;
            border-radius: 7px;
        }

        .photo-name {
            font-size: 12px;
            margin-top: 7px;
            word-break: break-word;
            color: #444;
        }

        .remove-photo {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 25px;
            height: 25px;
            border: none;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            font-weight: bold;
            cursor: pointer;
            line-height: 20px;
        }

        .existing-img {
            width: 150px;
            height: 105px;
            object-fit: cover;
            border-radius: 8px;
        }

        .main-badge {
            text-align: center;
            margin-top: 5px;
        }

        .selected-title {
            font-weight: 600;
            margin-top: 10px;
        }

    </style>

</head>

<body>

<div class="photo-container">

    <div class="card p-4">

        <h2 class="mb-4">
            Add Property Photos
        </h2>


        <?php if ($message): ?>

            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <!-- ==========================================
             FIND PROPERTY
        =========================================== -->

        <form method="POST">

            <div class="row align-items-end">

                <div class="col-md-8">

                    <label class="form-label">
                        Property ID
                    </label>

                    <input
                        type="number"
                        name="property_id"
                        class="form-control"
                        placeholder="Enter Property ID"
                        value="<?php
                            echo isset($_POST['property_id'])
                                ? htmlspecialchars(
                                    $_POST['property_id']
                                )
                                : '';
                        ?>"
                        required
                    >

                </div>

                <div class="col-md-4">

                    <button
                        type="submit"
                        name="find_property"
                        class="btn btn-primary w-100"
                    >
                        Find Property
                    </button>

                </div>

            </div>

        </form>


        <?php if ($property): ?>

            <hr class="my-4">


            <!-- ==========================================
                 PROPERTY VERIFICATION
            =========================================== -->

            <div class="property-info mb-4">

                <h4>
                    <?php
                        echo htmlspecialchars(
                            $property['title']
                        );
                    ?>
                </h4>

                <div class="info-item">
                    <strong>Property ID:</strong>
                    <?php
                        echo $property['property_id'];
                    ?>
                </div>

                <div class="info-item">
                    <strong>City:</strong>
                    <?php
                        echo htmlspecialchars(
                            $property['city']
                        );
                    ?>
                </div>

                <div class="info-item">
                    <strong>Listing Type:</strong>
                    <?php
                        echo htmlspecialchars(
                            $property['listing_type']
                        );
                    ?>
                </div>

                <div class="info-item">
                    <strong>Area:</strong>
                    <?php
                        echo number_format(
                            $property['area_sqft']
                        );
                    ?>
                    sq.ft
                </div>

                <?php if ($property['bedrooms'] > 0): ?>

                    <div class="info-item">
                        <strong>Bedrooms:</strong>
                        <?php
                            echo $property['bedrooms'];
                        ?>
                    </div>

                <?php endif; ?>

                <div class="info-item">
                    <strong>Furnishing:</strong>
                    <?php
                        echo htmlspecialchars(
                            $property['furnishing']
                        );
                    ?>
                </div>

            </div>


            <!-- ==========================================
                 UPLOAD FORM
            =========================================== -->

            <form
                method="POST"
                enctype="multipart/form-data"
                id="photoUploadForm"
            >

                <input
                    type="hidden"
                    name="property_id"
                    value="<?php
                        echo $property['property_id'];
                    ?>"
                >


                <!-- MAIN PHOTO -->

                <div class="mb-4">

                    <label class="form-label main-label">
                        Main Property Photo
                    </label>

                    <input
                        type="file"
                        name="main_property_photo"
                        id="main_property_photo"
                        class="form-control"
                        accept=".jpg,.jpeg,.png"
                    >

                    <small class="text-muted">
                        Select one main image.
                    </small>


                    <div
                        id="mainPreview"
                        class="preview-section"
                    ></div>

                </div>


                <!-- ADDITIONAL PHOTOS -->

                <div class="mb-4">

                    <label class="form-label main-label">
                        Additional Property Photos
                    </label>

                    <input
                        type="file"
                        name="property_photos[]"
                        id="property_photos"
                        class="form-control"
                        accept=".jpg,.jpeg,.png"
                        multiple
                    >

                    <small class="text-muted">
                        You can select multiple photos.
                        Selecting more photos later will add
                        them to the existing selection.
                    </small>


                    <div
                        id="additionalPreview"
                        class="preview-section"
                    ></div>

                </div>


                <button
                    type="submit"
                    name="upload_photos"
                    class="btn btn-success"
                >
                    Upload Photos
                </button>

            </form>


            <!-- ==========================================
                 EXISTING PHOTOS
            =========================================== -->

            <?php if (!empty($existing_images)): ?>

                <hr class="my-4">

                <h5 class="mb-3">
                    Already Uploaded Photos
                </h5>

                <div class="preview-grid">

                    <?php foreach (
                        $existing_images as $image
                    ): ?>

                        <div class="photo-preview-card">

                            <img
                                src="../<?php
                                    echo htmlspecialchars(
                                        $image['image_path']
                                    );
                                ?>"
                                class="existing-img"
                            >

                            <?php if ($image['is_main']): ?>

                                <div class="main-badge">

                                    <span class="badge bg-primary">
                                        Main
                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>


<script>

/* ==========================================
   MAIN PHOTO PREVIEW
========================================== */

const mainInput =
    document.getElementById("main_property_photo");

const mainPreview =
    document.getElementById("mainPreview");


mainInput.addEventListener("change", function () {

    mainPreview.innerHTML = "";

    if (!this.files.length) {
        return;
    }

    const file = this.files[0];

    const reader = new FileReader();

    reader.onload = function (e) {

        mainPreview.innerHTML = `
            <div class="selected-title">
                Selected Main Photo
            </div>

            <div class="preview-grid">

                <div class="photo-preview-card">

                    <img src="${e.target.result}">

                    <div class="photo-name">
                        ${escapeHtml(file.name)}
                    </div>

                </div>

            </div>
        `;
    };

    reader.readAsDataURL(file);
});


/* ==========================================
   ADDITIONAL PHOTOS
========================================== */

const additionalInput =
    document.getElementById("property_photos");

const additionalPreview =
    document.getElementById("additionalPreview");


/*
   DataTransfer allows us to keep all selected files.

   Example:

   First selection:
   photo1, photo2

   Second selection:
   photo3, photo4

   Final files:
   photo1, photo2, photo3, photo4
*/

let selectedFiles = new DataTransfer();


additionalInput.addEventListener(
    "change",
    function () {

        for (const file of this.files) {

            /* Avoid exact duplicate file selections */

            let alreadyExists = false;

            for (
                const existingFile of selectedFiles.files
            ) {

                if (
                    existingFile.name === file.name &&
                    existingFile.size === file.size &&
                    existingFile.lastModified ===
                        file.lastModified
                ) {

                    alreadyExists = true;
                    break;
                }
            }

            if (!alreadyExists) {
                selectedFiles.items.add(file);
            }
        }


        /* Put accumulated files back into input */

        additionalInput.files =
            selectedFiles.files;


        renderAdditionalPhotos();
    }
);


/* ==========================================
   DISPLAY ADDITIONAL PHOTOS
========================================== */

function renderAdditionalPhotos() {

    additionalPreview.innerHTML = "";

    if (selectedFiles.files.length === 0) {
        return;
    }

    additionalPreview.innerHTML = `
        <div class="selected-title">
            Selected Additional Photos
            (${selectedFiles.files.length})
        </div>

        <div class="preview-grid" id="additionalGrid"></div>
    `;

    const grid =
        document.getElementById("additionalGrid");


    Array.from(selectedFiles.files).forEach(
        (file, index) => {

            const reader = new FileReader();

            reader.onload = function (e) {

                const card =
                    document.createElement("div");

                card.className =
                    "photo-preview-card";

                card.innerHTML = `
                    <button
                        type="button"
                        class="remove-photo"
                        data-index="${index}"
                    >
                        ×
                    </button>

                    <img src="${e.target.result}">

                    <div class="photo-name">
                        ${escapeHtml(file.name)}
                    </div>
                `;

                grid.appendChild(card);


                card.querySelector(
                    ".remove-photo"
                ).addEventListener(
                    "click",
                    function () {

                        removeAdditionalPhoto(index);

                    }
                );
            };

            reader.readAsDataURL(file);
        }
    );
}


/* ==========================================
   REMOVE ONE ADDITIONAL PHOTO
========================================== */

function removeAdditionalPhoto(index) {

    const newFiles = new DataTransfer();

    Array.from(selectedFiles.files).forEach(
        (file, i) => {

            if (i !== index) {
                newFiles.items.add(file);
            }

        }
    );

    selectedFiles = newFiles;

    additionalInput.files =
        selectedFiles.files;

    renderAdditionalPhotos();
}


/* ==========================================
   HTML ESCAPE
========================================== */

function escapeHtml(text) {

    const div = document.createElement("div");

    div.textContent = text;

    return div.innerHTML;
}


/* ==========================================
   PREVENT EMPTY UPLOAD
========================================== */

document
    .getElementById("photoUploadForm")
    .addEventListener("submit", function (e) {

        const hasMain =
            mainInput.files.length > 0;

        const hasAdditional =
            selectedFiles.files.length > 0;

        if (!hasMain && !hasAdditional) {

            e.preventDefault();

            alert(
                "Please select at least one photo."
            );
        }

    });

</script>

</body>

</html>