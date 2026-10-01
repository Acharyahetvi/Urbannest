<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =====================================================
   DATABASE CONNECTION
===================================================== */

require_once __DIR__ . "/../../db_connection.php";

if (!isset($con) && isset($conn)) {
    $con = $conn;
}

if (!isset($con)) {
    die("Database connection not found.");
}

/* =====================================================
   CHECK PROPERTY ID
===================================================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Valid Property ID not found.");
}

$id = (int) $_GET['id'];

/* =====================================================
   GET PROPERTY DETAILS
===================================================== */

$sql = "
    SELECT
        p.*,
        u.full_name,
        c.category_name,
        sc.subcategory_name

    FROM properties p

    LEFT JOIN users u
        ON p.user_id = u.user_id

    LEFT JOIN sub_categories sc
        ON p.subcategory_id = sc.subcategory_id

    LEFT JOIN categories c
        ON sc.category_id = c.category_id

    WHERE p.property_id = ?
";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    die("Query Error: " . mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Property not found.");
}

$property = mysqli_fetch_assoc($result);

/* =====================================================
   GET PROPERTY IMAGES
===================================================== */

$imageSql = "
    SELECT image_path, is_main
    FROM property_images
    WHERE property_id = ?
";

$imageStmt = mysqli_prepare($con, $imageSql);

if (!$imageStmt) {
    die("Image Query Error: " . mysqli_error($con));
}

mysqli_stmt_bind_param($imageStmt, "i", $id);
mysqli_stmt_execute($imageStmt);

$imageResult = mysqli_stmt_get_result($imageStmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Property Details | UrbanNest Admin</title>

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <!-- COMMON STYLE CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <!-- ADMIN CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <!-- PROPERTY CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/property.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <!-- COMMON SIDEBAR -->
    <?php
    include __DIR__ . "/../includes/sidebar.php";
    ?>

    <!-- MAIN WRAPPER -->
    <div class="un-main-wrapper">

        <!-- COMMON HEADER -->
        <?php
        include __DIR__ . "/../includes/header.php";
        ?>

        <!-- MAIN CONTENT -->
        <main class="property-page">

            <!-- PAGE HEADER -->
            <div class="property-header">

                <div>

                    <span class="property-eyebrow">
                        PROPERTY MANAGEMENT
                    </span>

                    <h1>Property Details</h1>

                    <p>
                        Complete information about this property
                    </p>

                </div>

                <a href="view.php" class="add-property-btn">
                    ← Back to Properties
                </a>

            </div>

            <!-- =========================================
                 PROPERTY PHOTOS
            ========================================== -->

            <div class="property-table-wrapper property-single-card">

                <h3 class="property-details-heading">
                    Property Photos
                </h3>

                <div class="property-single-images">

                    <?php if (mysqli_num_rows($imageResult) > 0) { ?>

                        <?php while ($image = mysqli_fetch_assoc($imageResult)) { ?>

                            <div class="property-image-box">

                                <img
                                    src="/urbannest/admin/<?php
                                                    echo htmlspecialchars(
                                                        ltrim($image['image_path'], '/')
                                                    );
                                                    ?>"
                                    alt="Property Photo"
                                    class="property-single-image">

                                <?php if (!empty($image['is_main']) && $image['is_main'] == 1) { ?>

                                    <span class="main-image-badge">
                                        Main Image
                                    </span>

                                <?php } ?>

                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <p class="property-no-photos">
                            No photos found for this property.
                        </p>

                    <?php } ?>

                </div>

            </div>

            <!-- =========================================
                 PROPERTY INFORMATION
            ========================================== -->

            <div class="property-table-wrapper property-single-card">

                <h3 class="property-details-heading">
                    Property Information
                </h3>

                <table class="property-table property-details-table">

                    <tbody>

                        <tr>
                            <th>Property ID</th>
                            <td>
                                #<?php
                                    echo (int) $property['property_id'];
                                    ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Property Title</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['title'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>
                                <?php
                                echo nl2br(
                                    htmlspecialchars(
                                        $property['description'] ?? "-"
                                    )
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Owner</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['full_name'] ?? "Unknown"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Category</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['category_name'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Subcategory</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['subcategory_name'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Listing Type</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['listing_type'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Price</th>
                            <td>
                                ₹<?php
                                    echo number_format(
                                        (float) ($property['price'] ?? 0)
                                    );
                                    ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Area</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['area_sqft'] ?? "-")
                                );
                                ?> sq.ft.
                            </td>
                        </tr>

                        <tr>
                            <th>Bedrooms</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['bedrooms'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Bathrooms</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['bathrooms'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Furnishing</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['furnishing'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Parking Spaces</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['parking_spaces'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Floor Number</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['floor_number'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Total Floors</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['total_floors'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Plot Length</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['plot_length'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Plot Width</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($property['plot_width'] ?? "-")
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Address</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['address'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>City</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['city'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>State</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['state'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Property Condition</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['property_condition'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Approval Status</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['approval_status'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['status'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $property['created_at'] ?? "-"
                                );
                                ?>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </main>

        <!-- COMMON FOOTER -->
        <?php
        include __DIR__ . "/../includes/footer.php";
        ?>

    </div>

    <!-- BOOTSTRAP JS -->
    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>