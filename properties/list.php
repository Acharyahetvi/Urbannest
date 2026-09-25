<?php
// Database connection
require_once __DIR__ . '/../db_connection.php';

// Header and Navbar
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';


// Fetch approved and active properties
$sql = "SELECT 
            p.property_id,
            p.title,
            p.description,
            p.listing_type,
            p.price,
            p.area_sqft,
            p.bedrooms,
            p.bathrooms,
            p.city,
            p.state,
            s.subcategory_name,
            pi.image_path

        FROM properties p

        LEFT JOIN sub_categories s
            ON p.subcategory_id = s.subcategory_id

        LEFT JOIN property_images pi
            ON p.property_id = pi.property_id
            AND pi.is_main = 1

        WHERE p.approval_status = 'Approved'
        AND p.status = 'Active'

        ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<style>
    body {
        background-color: #f5f6fa;
        font-family: Arial, sans-serif;
    }

    .listing-section {
        padding: 50px 0;
    }

    .listing-heading {
        color: #0b1f3a;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .listing-subtitle {
        color: #777;
        margin-bottom: 30px;
    }

    .property-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        transition: 0.3s;
        height: 100%;
    }

    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .property-image {
        width: 100%;
        height: 230px;
        object-fit: cover;
    }

    .property-content {
        padding: 20px;
    }

    .property-type {
        display: inline-block;
        background: #f5e7b2;
        color: #0b1f3a;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 12px;
    }

    .property-title {
        color: #0b1f3a;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .property-price {
        color: #b08d22;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .property-location {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .property-info {
        color: #555;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .btn-details {
        display: block;
        text-align: center;
        background: #0b1f3a;
        color: white;
        padding: 11px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
    }

    .btn-details:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .no-property {
        background: white;
        padding: 40px;
        text-align: center;
        border-radius: 10px;
        color: #666;
    }
</style>


<!-- Property Listing Section -->
<section class="listing-section">

    <div class="container">

        <!-- Page Heading -->
        <div class="text-center">

            <h2 class="listing-heading">
                Explore Properties
            </h2>

            <p class="listing-subtitle">
                Find your perfect home with UrbanNest.
            </p>

        </div>


        <div class="row">

            <?php
            // Check query result
            if ($result && mysqli_num_rows($result) > 0) {

                // Display each property
                while ($row = mysqli_fetch_assoc($result)) {

                    // Property image
                    if (!empty($row['image_path'])) {
                        $image = '../' . $row['image_path'];
                    } else {
                        $image = '../assets/images/no-property.png';
                    }

                    // Property price
                    $price = number_format($row['price']);

                    // Property location
                    $location = $row['city'] . ', ' . $row['state'];
            ?>

                    <!-- Property Card -->
                    <div class="col-lg-4 col-md-6 mb-4">

                        <div class="property-card">

                            <!-- Property Image -->
                            <img
                                src="<?php echo htmlspecialchars($image); ?>"
                                class="property-image"
                                alt="Property Image">


                            <div class="property-content">

                                <!-- Listing Type -->
                                <span class="property-type">
                                    <?php
                                    echo htmlspecialchars($row['listing_type']);
                                    ?>
                                </span>


                                <!-- Property Title -->
                                <h3 class="property-title">
                                    <?php
                                    echo htmlspecialchars($row['title']);
                                    ?>
                                </h3>


                                <!-- Property Price -->
                                <div class="property-price">

                                    ₹<?php echo $price; ?>

                                    <?php
                                    if ($row['listing_type'] == 'For Rent') {
                                        echo '<small>/month</small>';
                                    }
                                    ?>

                                </div>


                                <!-- Property Location -->
                                <p class="property-location">

                                    📍
                                    <?php
                                    echo htmlspecialchars($location);
                                    ?>

                                </p>


                                <!-- Property Type -->
                                <p class="property-info">

                                    <strong>Type:</strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['subcategory_name'] ?? 'Property'
                                    );
                                    ?>

                                </p>


                                <!-- Area -->
                                <p class="property-info">

                                    <strong>Area:</strong>

                                    <?php
                                    echo htmlspecialchars($row['area_sqft']);
                                    ?> sq.ft.

                                </p>


                                <!-- Bedrooms and Bathrooms -->
                                <p class="property-info">

                                    <?php
                                    if ($row['bedrooms'] !== NULL) {
                                        echo htmlspecialchars($row['bedrooms']) . ' Bedrooms';
                                    }

                                    if ($row['bathrooms'] !== NULL) {
                                        echo ' | ' . htmlspecialchars($row['bathrooms']) . ' Bathrooms';
                                    }
                                    ?>

                                </p>


                                <!-- View Details Button -->
                                <a
                                    href="details.php?id=<?php echo $row['property_id']; ?>"
                                    class="btn-details">
                                    View Details
                                </a>

                            </div>

                        </div>

                    </div>

                <?php
                }
            } else {
                ?>

                <!-- No Property Found -->
                <div class="col-12">

                    <div class="no-property">

                        <h4>No Properties Available</h4>

                        <p>
                            Currently, there are no approved properties.
                            Please check back later.
                        </p>

                    </div>

                </div>

            <?php
            }
            ?>

        </div>

    </div>

</section>


<?php
// Footer
require_once __DIR__ . '/../includes/footer.php';
?>