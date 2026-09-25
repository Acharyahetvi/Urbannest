<?php

$pageTitle = 'Sell a Property — UrbanNest';
$pageDesc = 'Explore properties available for sale with UrbanNest.';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';


// =====================================
// GET PROPERTIES AVAILABLE FOR SALE
// =====================================

$sql = "SELECT properties.*, property_images.image_path
        FROM properties
        LEFT JOIN property_images
        ON properties.property_id = property_images.property_id
        AND property_images.is_main = 1
        WHERE properties.listing_type = 'For Sale'
        AND properties.approval_status = 'Approved'
        AND properties.status = 'Active'
        ORDER BY properties.property_id DESC
        LIMIT 10";

$result = mysqli_query($conn, $sql);

?>

<style>
    /* =====================================
   SALE PAGE
===================================== */

    .buy-section {
        padding: 60px 0;
        background: #f8f7f2;
    }


    /* =====================================
   PAGE HEADING
===================================== */

    .buy-heading {
        text-align: center;
        margin-bottom: 45px;
    }

    .buy-heading h1 {
        color: #0b1f3a;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .buy-heading p {
        color: #687386;
        font-size: 16px;
        margin: 0;
    }


    /* =====================================
   SECTION TITLE
===================================== */

    .buy-title {
        color: #0b1f3a;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 25px;
    }


    /* =====================================
   PROPERTY CARD
===================================== */

    .buy-card {
        height: 100%;
        background: #fff;
        border: 1px solid #eee9dc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
        transition: 0.3s;
    }

    .buy-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(11, 31, 58, 0.15);
    }


    /* =====================================
   PROPERTY IMAGE
===================================== */

    .buy-image {
        width: 100%;
        height: 230px;
        object-fit: cover;
        display: block;
    }


    /* =====================================
   PROPERTY DETAILS
===================================== */

    .buy-card-body {
        padding: 20px;
    }

    .buy-card-body h5 {
        color: #0b1f3a;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .buy-price {
        color: #b9922e;
        font-size: 23px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .buy-location {
        color: #687386;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .buy-info {
        color: #555;
        font-size: 14px;
        margin-bottom: 10px;
    }


    /* =====================================
   VIEW DETAILS BUTTON
===================================== */

    .buy-btn {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 18px;
        background: #0b1f3a;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
        transition: 0.3s;
    }

    .buy-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }


    /* =====================================
   NO PROPERTY MESSAGE
===================================== */

    .buy-empty {
        padding: 40px;
        text-align: center;
        color: #687386;
        background: #fff;
        border-radius: 10px;
    }
</style>


<!-- =====================================
     SALE PAGE SECTION
===================================== -->

<section class="buy-section">

    <div class="container">

        <!-- PAGE HEADING -->

        <div class="buy-heading">

            <h1>Sell a Property</h1>

            <p>
                Explore properties available for sale with UrbanNest.
            </p>

        </div>


        <!-- PROPERTY SECTION TITLE -->

        <h3 class="buy-title">
            Properties Available for Sale
        </h3>


        <!-- PROPERTY CARDS -->

        <div class="row g-4">

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                <?php while ($property = mysqli_fetch_assoc($result)) { ?>

                    <div class="col-md-4">

                        <div class="buy-card">

                            <!-- PROPERTY IMAGE -->

                            <?php

                            $imagePath = str_replace(
                                'admin/property/../',
                                'admin/',
                                $property['image_path'] ?? ''
                            );

                            ?>

                            <?php if (!empty($imagePath)) { ?>

                                <img
                                    src="<?php echo htmlspecialchars($imagePath); ?>"
                                    class="buy-image"
                                    alt="Property Image">

                            <?php } else { ?>

                                <img
                                    src="assets/images/hero.png"
                                    class="buy-image"
                                    alt="Property Image">

                            <?php } ?>


                            <!-- PROPERTY DETAILS -->

                            <div class="buy-card-body">

                                <!-- PROPERTY TITLE -->

                                <h5>
                                    <?php echo htmlspecialchars($property['title']); ?>
                                </h5>


                                <!-- PROPERTY PRICE -->

                                <div class="buy-price">

                                    ₹<?php echo number_format($property['price']); ?>

                                </div>


                                <!-- PROPERTY LOCATION -->

                                <p class="buy-location">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <?php echo htmlspecialchars($property['city']); ?>,

                                    <?php echo htmlspecialchars($property['state']); ?>

                                </p>


                                <hr>


                                <!-- PROPERTY AREA -->

                                <p class="buy-info">

                                    <strong>Area:</strong>

                                    <?php echo htmlspecialchars($property['area_sqft'] ?? 'N/A'); ?>
                                    sq.ft.

                                </p>


                                <!-- BEDROOMS -->

                                <p class="buy-info">

                                    <strong>Bedrooms:</strong>

                                    <?php echo htmlspecialchars($property['bedrooms'] ?? 'N/A'); ?>

                                </p>


                                <!-- BATHROOMS -->

                                <p class="buy-info">

                                    <strong>Bathrooms:</strong>

                                    <?php echo htmlspecialchars($property['bathrooms'] ?? 'N/A'); ?>

                                </p>


                                <!-- VIEW DETAILS BUTTON -->

                                <a
                                    href="properties/details.php?id=<?php echo (int) $property['property_id']; ?>"
                                    class="buy-btn">

                                    View Details

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <!-- NO PROPERTY MESSAGE -->

                <div class="col-12">

                    <div class="buy-empty">

                        <h5>No Properties Available</h5>

                        <p>
                            No properties are currently available for sale.
                        </p>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>


<?php

// =====================================
// INCLUDE FOOTER
// =====================================

require_once __DIR__ . '/includes/footer.php';

?>