<?php
require_once __DIR__ . "/../../db_connection.php";

/* CHECK PROPERTY ID */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Property ID not found.");
}

$property_id = (int) $_GET['id'];

/* GET PROPERTY DATA */
$sql = "SELECT * FROM properties WHERE property_id = $property_id";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

if (mysqli_num_rows($result) == 0) {
    die("Property not found.");
}

$property = mysqli_fetch_assoc($result);

$error = "";

/* UPDATE PROPERTY */
if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $subcategory_id = (int) $_POST['subcategory_id'];
    $status = trim($_POST['status']);

    $title = mysqli_real_escape_string($con, $title);
    $description = mysqli_real_escape_string($con, $description);
    $address = mysqli_real_escape_string($con, $address);
    $city = mysqli_real_escape_string($con, $city);
    $state = mysqli_real_escape_string($con, $state);
    $status = mysqli_real_escape_string($con, $status);

    /* UPDATE QUERY */
    $update_sql = "UPDATE properties SET
        title = '$title',
        description = '$description',
        price = $price,
        address = '$address',
        city = '$city',
        state = '$state',
        subcategory_id = $subcategory_id,
        status = '$status'
        WHERE property_id = $property_id";

    if (mysqli_query($con, $update_sql)) {

        /* UPLOAD NEW IMAGES */
        if (
            isset($_FILES['property_images']) &&
            !empty($_FILES['property_images']['name'][0])
        ) {

            $upload_folder = __DIR__ .
                "/../assets/images/property/uploads/";

            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }

            foreach ($_FILES['property_images']['name'] as $key => $name) {

                if ($_FILES['property_images']['error'][$key] != 0) {
                    continue;
                }

                $tmp_name = $_FILES['property_images']['tmp_name'][$key];
                $file_size = $_FILES['property_images']['size'][$key];

                /* MAXIMUM 5 MB */
                if ($file_size > 5 * 1024 * 1024) {
                    continue;
                }

                /* CHECK IMAGE TYPE */
                $image_info = getimagesize($tmp_name);

                if ($image_info === false) {
                    continue;
                }

                $extension = strtolower(
                    pathinfo($name, PATHINFO_EXTENSION)
                );

                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];

                if (!in_array($extension, $allowed_extensions)) {
                    continue;
                }

                $new_name = "property_" . $property_id .
                    "_" . uniqid() . "." . $extension;

                $destination = $upload_folder . $new_name;

                if (move_uploaded_file($tmp_name, $destination)) {

                    $image_path =
                        "admin/assets/images/property/uploads/" .
                        $new_name;

                    $image_path = mysqli_real_escape_string(
                        $con,
                        $image_path
                    );

                    $image_sql = "INSERT INTO property_images
                        (property_id, image_path, is_main)
                        VALUES ($property_id, '$image_path', 0)";

                    mysqli_query($con, $image_sql);
                }
            }
        }

        /* SET MAIN IMAGE */
        if (
            isset($_POST['main_image']) &&
            !empty($_POST['main_image'])
        ) {

            $main_image_id = (int) $_POST['main_image'];

            mysqli_query(
                $con,
                "UPDATE property_images
                 SET is_main = 0
                 WHERE property_id = $property_id"
            );

            mysqli_query(
                $con,
                "UPDATE property_images
                 SET is_main = 1
                 WHERE image_id = $main_image_id
                 AND property_id = $property_id"
            );
        }

        echo "<script>
            alert('Property updated successfully!');
            window.location.href='view.php';
        </script>";

        exit;
    } else {
        $error = "Update Error: " . mysqli_error($con);
    }

    /* SHOW UPDATED VALUES IN FORM */
    $property['title'] = $title;
    $property['description'] = $description;
    $property['price'] = $price;
    $property['address'] = $address;
    $property['city'] = $city;
    $property['state'] = $state;
    $property['subcategory_id'] = $subcategory_id;
    $property['status'] = $status;
}

/* DELETE IMAGE */
if (isset($_GET['delete_image'])) {

    $image_id = (int) $_GET['delete_image'];

    $image_query = mysqli_query(
        $con,
        "SELECT * FROM property_images
         WHERE image_id = $image_id
         AND property_id = $property_id"
    );

    if ($image_query && mysqli_num_rows($image_query) > 0) {

        $image = mysqli_fetch_assoc($image_query);
        $image_path = $image['image_path'];

        mysqli_query(
            $con,
            "DELETE FROM property_images
             WHERE image_id = $image_id
             AND property_id = $property_id"
        );

        $physical_path = __DIR__ . "/../../" . $image_path;

        if (file_exists($physical_path)) {
            unlink($physical_path);
        }
    }

    header("Location: edit.php?id=" . $property_id);
    exit;
}

/* GET EXISTING IMAGES */
$image_sql = "SELECT * FROM property_images
              WHERE property_id = $property_id
              ORDER BY is_main DESC, image_id ASC";

$image_result = mysqli_query($con, $image_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Property - UrbanNest</title>

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/property.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="admin-page">

    <body class="admin-page">

        <?php include __DIR__ . "/../includes/header.php"; ?>

        <?php include __DIR__ . "/../includes/sidebar.php"; ?>

        <main class="main-content">

            <div class="page-container">

                    <!-- PAGE HEADER -->
                    <div class="page-header">
                        <div>
                            <div class="eyebrow">PROPERTY MANAGEMENT</div>
                            <h1>Edit Property</h1>
                            <p>Update property information</p>
                        </div>

                        <a href="view.php" class="back-btn">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Properties
                        </a>
                    </div>

                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <!-- EDIT FORM -->
                    <div class="card">
                        <form method="POST"
                            enctype="multipart/form-data"
                            class="edit-property-form">

                            <div class="form-group">
                                <label>Property Title</label>
                                <input type="text" name="title"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($property['title'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    rows="5"
                                    required><?php echo htmlspecialchars($property['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price"
                                    class="form-control"
                                    min="0" step="0.01"
                                    value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address"
                                    class="form-control"
                                    rows="3"
                                    required><?php echo htmlspecialchars($property['address'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($property['city'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($property['state'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Subcategory ID</label>
                                <input type="number" name="subcategory_id"
                                    class="form-control"
                                    min="1"
                                    value="<?php echo htmlspecialchars($property['subcategory_id'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <?php
                                    $statuses = ['Available', 'Sold', 'Pending'];

                                    foreach ($statuses as $status_option) {
                                        $selected = (
                                            ($property['status'] ?? '') == $status_option
                                        ) ? 'selected' : '';

                                        echo "<option value='" .
                                            htmlspecialchars($status_option) .
                                            "' $selected>" .
                                            htmlspecialchars($status_option) .
                                            "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- EXISTING IMAGES -->
                            <div class="image-section">
                                <h3>Property Images</h3>

                                <div class="image-grid">
                                    <?php
                                    if (
                                        $image_result &&
                                        mysqli_num_rows($image_result) > 0
                                    ) {

                                        while ($image = mysqli_fetch_assoc($image_result)) {

                                            $image_url = "/urbannest/" .
                                                $image['image_path'];
                                    ?>

                                            <div class="image-box">
                                                <img
                                                    src="<?php echo htmlspecialchars($image_url); ?>"
                                                    alt="Property Image">

                                                <div class="main-option">
                                                    <label>
                                                        <input type="radio"
                                                            name="main_image"
                                                            value="<?php echo (int)$image['image_id']; ?>"
                                                            <?php
                                                            if ($image['is_main'] == 1) {
                                                                echo 'checked';
                                                            }
                                                            ?>>
                                                        Main Image
                                                    </label>
                                                </div>

                                                <a class="delete-image"
                                                    href="edit.php?id=<?php echo $property_id; ?>&delete_image=<?php echo (int)$image['image_id']; ?>"
                                                    onclick="return confirm('Delete this image?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                    Delete Image
                                                </a>
                                            </div>

                                    <?php
                                        }
                                    } else {
                                        echo "<p class='no-data'>No images available.</p>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- ADD NEW IMAGES -->
                            <div class="upload-section">
                                <h3>Add New Images</h3>

                                <input type="file"
                                    name="property_images[]"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.webp">

                                <p class="upload-note">
                                    JPG, JPEG, PNG and WEBP only.
                                    Maximum 5 MB per image.
                                </p>
                            </div>

                            <!-- BUTTONS -->
                            <div class="form-actions">
                                <button type="submit"
                                    name="update"
                                    class="update-property-btn">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Update Property
                                </button>

                                <a href="view.php" class="back-btn">
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </div>

            </div>

            <?php include __DIR__ . "/../includes/footer.php"; ?>

    </body>

</html>