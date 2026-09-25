<?php

$pageTitle = 'Property Details — UrbanNest';
$pageDesc = 'View complete property details with UrbanNest.';

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';


// =====================================
// GET PROPERTY ID
// =====================================

$property_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


// =====================================
// GET PROPERTY DETAILS
// =====================================

$property = null;

if ($property_id > 0) {

    $sql = "SELECT *
            FROM properties
            WHERE property_id = $property_id
            AND approval_status = 'Approved'
            AND status = 'Active'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $property = mysqli_fetch_assoc($result);
    }
}


// =====================================
// GET PROPERTY IMAGES
// =====================================

$images = [];

if ($property) {

    $sql_images = "SELECT image_path, is_main
                   FROM property_images
                   WHERE property_id = $property_id
                   ORDER BY is_main DESC, image_id ASC";

    $result_images = mysqli_query($conn, $sql_images);

    if ($result_images) {

        while ($image = mysqli_fetch_assoc($result_images)) {

            $images[] = $image;
        }
    }
}

?>

<style>
    /* =====================================
   PROPERTY DETAILS PAGE
===================================== */

    .details-section {
        padding: 60px 0;
        background: #f8f7f2;
    }


    /* =====================================
   PAGE HEADING
===================================== */

    .details-heading {
        margin-bottom: 35px;
    }

    .details-heading h1 {
        color: #0b1f3a;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .details-location {
        color: #687386;
        font-size: 15px;
    }


    /* =====================================
   PROPERTY IMAGE
===================================== */

    .details-main-image {
        width: 100%;
        height: 450px;
        object-fit: cover;
        display: block;
        border-radius: 12px;
    }


    /* =====================================
   PROPERTY GALLERY
===================================== */

    .details-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .details-gallery img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 10px;
    }


    /* =====================================
   PROPERTY INFORMATION
===================================== */

    .details-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #eee9dc;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
        margin-top: 30px;
    }

    .details-card h3 {
        color: #0b1f3a;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
    }


    /* =====================================
   PROPERTY PRICE
===================================== */

    .details-price {
        color: #b9922e;
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 20px;
    }


    /* =====================================
   PROPERTY DESCRIPTION
===================================== */

    .details-description {
        color: #687386;
        font-size: 15px;
        line-height: 1.8;
    }


    /* =====================================
   PROPERTY FEATURES
===================================== */

    .details-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 25px;
    }

    .details-info-box {
        padding: 18px;
        background: #f8f7f2;
        border-radius: 8px;
    }

    .details-info-box strong {
        display: block;
        color: #0b1f3a;
        font-size: 15px;
        margin-bottom: 8px;
    }

    .details-info-box span {
        color: #687386;
        font-size: 14px;
    }


    /* =====================================
   PROPERTY DESCRIPTION
===================================== */

    .details-text {
        color: #555;
        font-size: 15px;
        line-height: 1.8;
    }


    /* =====================================
   PROPERTY TYPE
===================================== */

    .details-badge {
        display: inline-block;
        background: #0b1f3a;
        color: #fff;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
    }


    /* =====================================
   BACK BUTTON
===================================== */

    .details-btn {
        display: inline-block;
        padding: 12px 25px;
        background: #0b1f3a;
        color: #fff;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
        transition: 0.3s;
        margin-top: 20px;
    }

    .details-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }


    /* =====================================
   NO PROPERTY MESSAGE
===================================== */

    .details-empty {
        padding: 50px;
        text-align: center;
        background: #fff;
        border-radius: 12px;
    }

    .details-empty h3 {
        color: #0b1f3a;
        margin-bottom: 15px;
    }

    .details-empty p {
        color: #687386;
    }
</style>


<!-- =====================================
     PROPERTY DETAILS SECTION
===================================== -->

<section class="details-section">

    <div class="container">

        <?php if ($property) { ?>


            <!-- =====================================
                 PROPERTY HEADING
            ===================================== -->

            <div class="details-heading">

                <h1>
                    <?php echo htmlspecialchars($property['title']); ?>
                </h1>

                <p class="details-location">

                    <i class="fa-solid fa-location-dot"></i>

                    <?php echo htmlspecialchars($property['city']); ?>,

                    <?php echo htmlspecialchars($property['state']); ?>

                </p>

            </div>


            <!-- =====================================
                 PROPERTY IMAGES
            ===================================== -->

            <div class="row">

                <div class="col-12">

                    <?php

                    $mainImage = '';

                    if (!empty($images)) {

                        $mainImage = $images[0]['image_path'];
                    }

                    $mainImage = str_replace(
                        'admin/property/../',
                        'admin/',
                        $mainImage
                    );

                    ?>

                    <?php if (!empty($mainImage)) { ?>

                        <img
                            src="<?php echo htmlspecialchars($mainImage); ?>"
                            class="details-main-image"
                            alt="Property Image">

                    <?php } else { ?>

                        <img
                            src="assets/images/hero.png"
                            class="details-main-image"
                            alt="Property Image">

                    <?php } ?>


                    <!-- PROPERTY GALLERY -->

                    <?php if (count($images) > 1) { ?>

                        <div class="details-gallery">

                            <?php

                            $galleryCount = 0;

                            foreach ($images as $image) {

                                if ($galleryCount >= 4) {
                                    break;
                                }

                                if ($image['image_path'] == $images[0]['image_path']) {
                                    continue;
                                }

                                $galleryImage = str_replace(
                                    'admin/property/../',
                                    'admin/',
                                    $image['image_path']
                                );

                            ?>

                                <img
                                    src="<?php echo htmlspecialchars($galleryImage); ?>"
                                    alt="Property Image">

                            <?php

                                $galleryCount++;
                            }

                            ?>

                        </div>

                    <?php } ?>

                </div>

            </div>


            <!-- =====================================
                 PROPERTY INFORMATION
            ===================================== -->

            <div class="details-card">

                <!-- PROPERTY TYPE -->

                <span class="details-badge">

                    <?php echo htmlspecialchars($property['listing_type']); ?>

                </span>


                <!-- PROPERTY PRICE -->

                <div class="details-price">

                    ₹<?php echo number_format($property['price']); ?>

                </div>


                <!-- PROPERTY DESCRIPTION -->

                <h3>Property Description</h3>

                <p class="details-description">

                    <?php echo nl2br(htmlspecialchars($property['description'] ?? 'No description available.')); ?>

                </p>


                <hr>


                <!-- =====================================
                     PROPERTY FEATURES
                ===================================== -->

                <h3>Property Information</h3>

                <div class="details-info">


                    <!-- AREA -->

                    <div class="details-info-box">

                        <strong>Area</strong>

                        <span>
                            <?php echo htmlspecialchars($property['area_sqft'] ?? 'N/A'); ?>
                            sq.ft.
                        </span>

                    </div>


                    <!-- BEDROOMS -->

                    <div class="details-info-box">

                        <strong>Bedrooms</strong>

                        <span>
                            <?php echo htmlspecialchars($property['bedrooms'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- BATHROOMS -->

                    <div class="details-info-box">

                        <strong>Bathrooms</strong>

                        <span>
                            <?php echo htmlspecialchars($property['bathrooms'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- FURNISHING -->

                    <div class="details-info-box">

                        <strong>Furnishing</strong>

                        <span>
                            <?php echo htmlspecialchars($property['furnishing'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- PARKING -->

                    <div class="details-info-box">

                        <strong>Parking Spaces</strong>

                        <span>
                            <?php echo htmlspecialchars($property['parking_spaces'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- FLOOR NUMBER -->

                    <div class="details-info-box">

                        <strong>Floor Number</strong>

                        <span>
                            <?php echo htmlspecialchars($property['floor_number'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- TOTAL FLOORS -->

                    <div class="details-info-box">

                        <strong>Total Floors</strong>

                        <span>
                            <?php echo htmlspecialchars($property['total_floors'] ?? 'N/A'); ?>
                        </span>

                    </div>


                    <!-- PROPERTY CONDITION -->

                    <div class="details-info-box">

                        <strong>Property Condition</strong>

                        <span>
                            <?php echo htmlspecialchars($property['property_condition'] ?? 'N/A'); ?>
                        </span>

                    </div>

                </div>


                <hr>


                <!-- =====================================
                     PROPERTY ADDRESS
                ===================================== -->

                <h3>Property Address</h3>

                <p class="details-text">

                    <i class="fa-solid fa-location-dot"></i>

                    <?php echo htmlspecialchars($property['address'] ?? 'Address not available'); ?>,

                    <?php echo htmlspecialchars($property['city']); ?>,

                    <?php echo htmlspecialchars($property['state']); ?>

                </p>


                <!-- =====================================
                     BACK BUTTON
                ===================================== -->

                <a href="javascript:history.back()" class="details-btn">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Properties

                </a>

            </div>


        <?php } else { ?>


            <!-- =====================================
                 NO PROPERTY FOUND
            ===================================== -->

            <div class="details-empty">

                <h3>Property Not Found</h3>

                <p>
                    Sorry, this property is not available or has been removed.
                </p>

                <a href="buy.php" class="details-btn">

                    Browse Properties

                </a>

            </div>


        <?php } ?>

    </div>

</section>


<?php

// =====================================
// INCLUDE FOOTER
// =====================================

require_once __DIR__ . '/../includes/footer.php';

?>