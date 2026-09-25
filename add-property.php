<?php
session_start();

require_once __DIR__ . '/db_connection.php';

// Login user ID
// Apne login system ke session ke according change karna
$user_id = $_SESSION['user_id'] ?? 1;

$message = "";

// Fetch Subcategories
$subcategories = mysqli_query(
    $conn,
    "SELECT subcategory_id, subcategory_name
     FROM sub_categories
     WHERE status = 'Active'
     ORDER BY subcategory_name ASC"
);

// Form Submit
if (isset($_POST['submit'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $listing_type = $_POST['listing_type'];
    $subcategory_id = (int) $_POST['subcategory_id'];

    $price = (float) $_POST['price'];
    $area_sqft = (int) $_POST['area_sqft'];

    $bedrooms = !empty($_POST['bedrooms']) ? (int) $_POST['bedrooms'] : null;
    $bathrooms = !empty($_POST['bathrooms']) ? (int) $_POST['bathrooms'] : null;

    $furnishing = $_POST['furnishing'] ?: null;

    $parking_spaces = !empty($_POST['parking_spaces']) ? (int) $_POST['parking_spaces'] : null;
    $floor_number = !empty($_POST['floor_number']) ? (int) $_POST['floor_number'] : null;
    $total_floors = !empty($_POST['total_floors']) ? (int) $_POST['total_floors'] : null;

    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    // Image Upload
    $image_path = "";

    if (!empty($_FILES['property_image']['name'])) {

        $upload_folder = __DIR__ . '/../uploads/properties/';

        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES['property_image']['name']);

        $image_path = "uploads/properties/" . $image_name;

        $target_file = $upload_folder . $image_name;

        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($image_type, $allowed_types)) {

            if (!move_uploaded_file($_FILES['property_image']['tmp_name'], $target_file)) {
                $message = "Image upload failed.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG and WEBP images are allowed.";
        }
    }

    // Save Property
    if ($message == "") {

        $sql = "INSERT INTO properties
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
            parking_spaces,
            floor_number,
            total_floors,
            address,
            city,
            state
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iisssdiiisiiisss",
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
            $parking_spaces,
            $floor_number,
            $total_floors,
            $address,
            $city,
            $state
        );

        if (mysqli_stmt_execute($stmt)) {

            $property_id = mysqli_insert_id($conn);

            // Save Image
            if ($image_path != "") {

                $image_sql = "INSERT INTO property_images
                (property_id, image_path, is_main)
                VALUES (?, ?, 1)";

                $image_stmt = mysqli_prepare($conn, $image_sql);

                mysqli_stmt_bind_param(
                    $image_stmt,
                    "is",
                    $property_id,
                    $image_path
                );

                mysqli_stmt_execute($image_stmt);
            }

            $message = "Property added successfully! It will be reviewed by the admin.";
        } else {

            $message = "Error: " . mysqli_error($conn);
        }
    }
}

$pageTitle = "Add Property - UrbanNest";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
    /* ==============================
   ADD PROPERTY PAGE
============================== */
    .add-property-section {
        background:
            linear-gradient(rgba(11, 31, 58, 0.55), rgba(11, 31, 58, 0.55)),
            url('/urbannest/assets/images/hero.png') center / cover no-repeat;

        padding: 60px 0;
        min-height: 100vh;
    }

    /* .add-property-section {
        background: #f5f7fa;
        padding: 50px 0;
    } */

    .property-form-box {
        max-width: 950px;
        margin: auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    }

    .property-form-title {
        color: #0b1f3a;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .property-form-subtitle {
        color: #777;
        margin-bottom: 30px;
    }

    .form-section-title {
        color: #0b1f3a;
        font-size: 18px;
        font-weight: 600;
        margin: 25px 0 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-label {
        color: #333;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border: 1px solid #ddd;
        border-radius: 7px;
        padding: 11px 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.15rem rgba(212, 175, 55, 0.15);
    }

    .btn-property-submit {
        background: #0b1f3a;
        color: #fff;
        border: none;
        padding: 13px 30px;
        border-radius: 7px;
        font-weight: 600;
    }

    .btn-property-submit:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    @media (max-width: 768px) {

        .property-form-box {
            padding: 20px;
        }

        .add-property-section {
            padding: 25px 0;
        }
    }
</style>


<!-- ADD PROPERTY SECTION -->
<section class="add-property-section">

    <div class="container">

        <div class="property-form-box">

            <h2 class="property-form-title">
                Add Your Property
            </h2>

            <p class="property-form-subtitle">
                Share your property details to sell or rent with UrbanNest.
            </p>


            <!-- Success / Error Message -->
            <?php if ($message != ""): ?>

                <div class="alert alert-info">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endif; ?>


            <form method="POST" enctype="multipart/form-data">

                <!-- PROPERTY INFORMATION -->
                <h5 class="form-section-title">
                    Property Information
                </h5>

                <div class="row g-3">

                    <!-- Property Title -->
                    <div class="col-md-12">

                        <label class="form-label">Property Title *</label>

                        <input type="text"
                            name="title"
                            class="form-control"
                            placeholder="Enter property title"
                            required>

                    </div>


                    <!-- Listing Type -->
                    <div class="col-md-6">

                        <label class="form-label">Listing Type *</label>

                        <select name="listing_type" class="form-select" required>

                            <option value="">Select Listing Type</option>
                            <option value="For Sale">For Sale</option>
                            <option value="For Rent">For Rent</option>

                        </select>

                    </div>


                    <!-- Subcategory -->
                    <div class="col-md-6">

                        <label class="form-label">Property Type *</label>

                        <select name="subcategory_id" class="form-select" required>

                            <option value="">Select Property Type</option>

                            <?php if ($subcategories): ?>

                                <?php while ($row = mysqli_fetch_assoc($subcategories)): ?>

                                    <option value="<?= $row['subcategory_id'] ?>">
                                        <?= htmlspecialchars($row['subcategory_name']) ?>
                                    </option>

                                <?php endwhile; ?>

                            <?php endif; ?>

                        </select>

                    </div>


                    <!-- Description -->
                    <div class="col-md-12">

                        <label class="form-label">Description *</label>

                        <textarea name="description"
                            class="form-control"
                            rows="5"
                            placeholder="Describe your property..."
                            required></textarea>

                    </div>

                </div>


                <!-- PRICE AND AREA -->
                <h5 class="form-section-title">
                    Price & Area
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">Price (₹) *</label>

                        <input type="number"
                            name="price"
                            class="form-control"
                            min="1"
                            placeholder="Enter price"
                            required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">Area (sq. ft.) *</label>

                        <input type="number"
                            name="area_sqft"
                            class="form-control"
                            min="1"
                            placeholder="Enter area"
                            required>

                    </div>

                </div>


                <!-- PROPERTY DETAILS -->
                <h5 class="form-section-title">
                    Property Details
                </h5>

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">Bedrooms</label>

                        <input type="number"
                            name="bedrooms"
                            class="form-control"
                            min="0"
                            placeholder="Bedrooms">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">Bathrooms</label>

                        <input type="number"
                            name="bathrooms"
                            class="form-control"
                            min="0"
                            placeholder="Bathrooms">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">Furnishing</label>

                        <select name="furnishing" class="form-select">

                            <option value="">Select Furnishing</option>
                            <option value="Furnished">Furnished</option>
                            <option value="Semi-Furnished">Semi-Furnished</option>
                            <option value="Unfurnished">Unfurnished</option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">Parking Spaces</label>

                        <input type="number"
                            name="parking_spaces"
                            class="form-control"
                            min="0"
                            placeholder="Parking">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">Floor Number</label>

                        <input type="number"
                            name="floor_number"
                            class="form-control"
                            min="0"
                            placeholder="Floor number">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">Total Floors</label>

                        <input type="number"
                            name="total_floors"
                            class="form-control"
                            min="0"
                            placeholder="Total floors">

                    </div>

                </div>


                <!-- LOCATION -->
                <h5 class="form-section-title">
                    Property Location
                </h5>

                <div class="row g-3">

                    <div class="col-md-12">

                        <label class="form-label">Full Address *</label>

                        <textarea name="address"
                            class="form-control"
                            rows="3"
                            placeholder="Enter complete address"
                            required></textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">City *</label>

                        <select name="city" class="form-select" required>

                            <option value="">Select City</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                            <option value="Surat">Surat</option>
                            <option value="Vadodara">Vadodara</option>
                            <option value="Rajkot">Rajkot</option>
                            <option value="Gandhinagar">Gandhinagar</option>
                            <option value="Bhavnagar">Bhavnagar</option>
                            <option value="Mumbai">Mumbai</option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">State *</label>

                        <select name="state" class="form-select" required>

                            <option value="">Select State</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Maharashtra">Maharashtra</option>

                        </select>

                    </div>

                </div>


                <!-- PROPERTY IMAGE -->
                <h5 class="form-section-title">
                    Property Image
                </h5>

                <div class="row g-3">

                    <div class="col-md-12">

                        <label class="form-label">Upload Main Image</label>

                        <input type="file"
                            name="property_image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp">

                        <small class="text-muted">
                            Allowed formats: JPG, JPEG, PNG, WEBP
                        </small>

                    </div>

                </div>


                <!-- SUBMIT BUTTON -->
                <div class="mt-4">

                    <button type="submit"
                        name="submit"
                        class="btn btn-property-submit">

                        Submit Property

                    </button>

                </div>

            </form>

        </div>

    </div>

</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>