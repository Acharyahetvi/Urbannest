<?php

$pageTitle = 'Property Category — UrbanNest';
$pageDesc  = 'Explore properties by category on UrbanNest.';

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';


// =====================================
// GET CATEGORY ID
// =====================================

$categoryId = isset($_GET['category_id'])
    ? (int) $_GET['category_id']
    : 0;

$category = null;
$properties = [];


// =====================================
// FETCH CATEGORY
// =====================================

if ($categoryId > 0) {

    $categoryStmt = mysqli_prepare(
        $conn,
        "SELECT category_id, category_name
         FROM categories
         WHERE category_id = ?
         AND status = 'Active'
         LIMIT 1"
    );

    if ($categoryStmt) {

        mysqli_stmt_bind_param(
            $categoryStmt,
            'i',
            $categoryId
        );

        mysqli_stmt_execute($categoryStmt);

        $categoryResult = mysqli_stmt_get_result($categoryStmt);

        if ($categoryResult) {
            $category = mysqli_fetch_assoc($categoryResult);
        }

        mysqli_stmt_close($categoryStmt);
    }
}


// =====================================
// FETCH PROPERTIES OF CATEGORY
// =====================================

if ($category) {

    $propertyStmt = mysqli_prepare(
        $conn,
        "SELECT
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
            p.approval_status,
            p.status,
            s.subcategory_name,
            pi.image_path

        FROM properties p

        INNER JOIN sub_categories s
            ON p.subcategory_id = s.subcategory_id

        LEFT JOIN property_images pi
            ON p.property_id = pi.property_id
            AND pi.is_main = 1

        WHERE s.category_id = ?
        AND p.approval_status = 'Approved'
        AND p.status = 'Active'
        AND s.status = 'Active'

        ORDER BY p.created_at DESC"
    );

    if ($propertyStmt) {

        mysqli_stmt_bind_param(
            $propertyStmt,
            'i',
            $categoryId
        );

        mysqli_stmt_execute($propertyStmt);

        $propertyResult = mysqli_stmt_get_result($propertyStmt);

        if ($propertyResult) {

            while ($row = mysqli_fetch_assoc($propertyResult)) {
                $properties[] = $row;
            }
        }

        mysqli_stmt_close($propertyStmt);
    }
}


// =====================================
// PROPERTY IMAGE HELPER
// =====================================

function categoryPropertyImage(?string $path): string
{
    if (!empty($path)) {
        return '../admin/' . ltrim($path, '/');
    }

    return '../admin/assets/images/no-property.png';
}

?>

<style>

    /* =====================================
       CATEGORY PAGE
    ===================================== */

    .category-page {
        background: #f8f7f2;
        padding: 60px 0 70px;
        min-height: 600px;
    }


    /* =====================================
       PAGE HEADING
    ===================================== */

    .category-heading {
        text-align: center;
        margin-bottom: 45px;
    }

    .category-heading h1 {
        color: #0b1f3a;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .category-heading p {
        color: #687386;
        font-size: 16px;
        margin: 0;
    }


    /* =====================================
       PROPERTY CARD
    ===================================== */

    .category-card {
        height: 100%;
        background: #fff;
        border: 1px solid #eee9dc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
        transition: 0.3s;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(11, 31, 58, 0.15);
    }


    /* =====================================
       PROPERTY IMAGE
    ===================================== */

    .category-image {
        width: 100%;
        height: 185px;
        object-fit: cover;
        display: block;
    }


    /* =====================================
       CARD BODY
    ===================================== */

    .category-card-body {
        padding: 16px;
    }

    .category-subcategory {
        display: inline-block;
        background: #f5e7b2;
        color: #0b1f3a;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .category-card-body h5 {
        color: #0b1f3a;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .category-price {
        color: #b9922e;
        font-size: 21px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .category-location {
        color: #687386;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .category-info {
        color: #555;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .category-card-body hr {
        margin: 8px 0;
    }

    .category-room-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .category-room-info .category-info {
        margin-bottom: 0;
    }


    /* =====================================
       LISTING TYPE
    ===================================== */

    .category-listing-type {
        display: inline-block;
        color: #0b1f3a;
        background: #f1f1f1;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }


    /* =====================================
       VIEW DETAILS BUTTON
    ===================================== */

    .category-btn {
        display: block;
        width: 100%;
        padding: 9px 12px;
        margin-top: 12px;
        background: #0b1f3a;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
        transition: 0.3s;
    }

    .category-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    /* =====================================
       EMPTY / ERROR MESSAGE
    ===================================== */

    .category-empty {
        background: #fff;
        padding: 50px 30px;
        text-align: center;
        border-radius: 12px;
        border: 1px solid #eee9dc;
    }

    .category-empty h4 {
        color: #0b1f3a;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .category-empty p {
        color: #687386;
        margin: 0;
    }


    @media (max-width: 767px) {

        .category-heading h1 {
            font-size: 30px;
        }

        .category-page {
            padding: 45px 0 55px;
        }

    }

</style>


<!-- =====================================
     CATEGORY PROPERTY PAGE
===================================== -->

<section class="category-page">

    <div class="container">

        <?php if (!$category): ?>

            <!-- CATEGORY NOT FOUND -->

            <div class="category-empty">

                <h4>Category Not Found</h4>

                <p>
                    The property category you are looking for does not exist.
                </p>

            </div>


        <?php else: ?>

            <!-- CATEGORY HEADING -->

            <div class="category-heading">

                <h1>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                    Properties
                </h1>

                <p>
                    Explore <?php echo htmlspecialchars(strtolower($category['category_name'])); ?>
                    properties available on UrbanNest.
                </p>

            </div>


            <!-- PROPERTY CARDS -->

            <div class="row g-4">

                <?php if (count($properties) > 0): ?>

                    <?php foreach ($properties as $property): ?>

                        <div class="col-lg-4 col-md-6">

                            <div class="category-card">

                                <!-- PROPERTY IMAGE -->

                                <img
                                    src="<?php echo htmlspecialchars(
                                        categoryPropertyImage($property['image_path'] ?? null)
                                    ); ?>"
                                    class="category-image"
                                    alt="<?php echo htmlspecialchars($property['title']); ?>"
                                >


                                <!-- CARD BODY -->

                                <div class="category-card-body">

                                    <!-- SUBCATEGORY -->

                                    <?php if (!empty($property['subcategory_name'])): ?>

                                        <span class="category-subcategory">

                                            <?php echo htmlspecialchars(
                                                $property['subcategory_name']
                                            ); ?>

                                        </span>

                                    <?php endif; ?>


                                    <!-- TITLE -->

                                    <h5>
                                        <?php echo htmlspecialchars($property['title']); ?>
                                    </h5>


                                    <!-- LISTING TYPE -->

                                    <span class="category-listing-type">

                                        <?php echo htmlspecialchars(
                                            $property['listing_type']
                                        ); ?>

                                    </span>


                                    <!-- PRICE -->

                                    <div class="category-price">

                                        ₹<?php echo number_format(
                                            (float) $property['price']
                                        ); ?>

                                        <?php if ($property['listing_type'] === 'For Rent'): ?>
                                            <small>/ month</small>
                                        <?php endif; ?>

                                    </div>


                                    <!-- LOCATION -->

                                    <p class="category-location">

                                        <i class="fa-solid fa-location-dot"></i>

                                        <?php echo htmlspecialchars($property['city']); ?>,
                                        <?php echo htmlspecialchars($property['state']); ?>

                                    </p>


                                    <hr>


                                    <!-- AREA -->

                                    <p class="category-info">

                                        <strong>Area:</strong>

                                        <?php echo htmlspecialchars(
                                            $property['area_sqft'] ?? 'N/A'
                                        ); ?>

                                        sq.ft.

                                    </p>


                                    <div class="category-room-info">

                                    <!-- BEDROOMS -->

                                    <p class="category-info">

                                        <strong>Bedrooms:</strong>

                                        <?php echo htmlspecialchars(
                                            $property['bedrooms'] ?? 'N/A'
                                        ); ?>

                                    </p>


                                    <!-- BATHROOMS -->

                                    <p class="category-info">

                                        <strong>Bathrooms:</strong>

                                        <?php echo htmlspecialchars(
                                            $property['bathrooms'] ?? 'N/A'
                                        ); ?>

                                    </p>

                                    </div>


                                    <!-- VIEW DETAILS -->

                                    <a
                                        href="details.php?id=<?php echo (int) $property['property_id']; ?>"
                                        class="category-btn"
                                    >

                                        View Details

                                        <i class="fa-solid fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <!-- NO PROPERTIES -->

                    <div class="col-12">

                        <div class="category-empty">

                            <h4>
                                No Properties Available
                            </h4>

                            <p>
                                There are currently no active properties in this category.
                            </p>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<?php

require_once __DIR__ . '/../includes/footer.php';

?>