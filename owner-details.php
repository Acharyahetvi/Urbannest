<?php

session_start();

require_once __DIR__ . '/db_connection.php';

$pageTitle = "Owner Details - UrbanNest";
$pageDesc = "View property owner details and listed properties.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';


// =====================================================
// GET OWNER ID
// =====================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid owner ID.");
}

$owner_id = (int) $_GET['id'];


// =====================================================
// GET OWNER DETAILS
// =====================================================

$sql = "SELECT
            u.user_id,
            u.full_name,
            u.phoneno,
            u.email,
            op.profile_photo,
            op.property_count,
            op.bio
        FROM users u
        LEFT JOIN owner_profiles op
            ON u.user_id = op.user_id
        WHERE u.user_id = ?
        AND u.role_id = 5
        AND u.status = 'Active'";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database query error.");
}

mysqli_stmt_bind_param($stmt, "i", $owner_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Owner not found.");
}

$owner = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// =====================================================
// GET OWNER'S PROPERTIES
// =====================================================

$propertySql = "SELECT
                    p.property_id,
                    p.title,
                    p.listing_type,
                    p.price,
                    p.area_sqft,
                    p.bedrooms,
                    p.bathrooms,
                    p.city,
                    p.state,
                    pi.image_path
                FROM properties p
                LEFT JOIN property_images pi
                    ON p.property_id = pi.property_id
                    AND pi.is_main = 1
                WHERE p.user_id = ?
                AND p.approval_status = 'Approved'
                AND p.status = 'Active'
                ORDER BY p.property_id ASC";

$propertyStmt = mysqli_prepare($conn, $propertySql);

if (!$propertyStmt) {
    die("Property query error.");
}

mysqli_stmt_bind_param($propertyStmt, "i", $owner_id);
mysqli_stmt_execute($propertyStmt);

$propertyResult = mysqli_stmt_get_result($propertyStmt);

?>

<style>

body {
    background-color: #f8f7f2;
}

.owner-details-section {
    padding: 70px 0;
}

.owner-profile-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 35px;
    box-shadow: 0 5px 20px rgba(11, 31, 58, 0.08);
    border: 1px solid #eeeeee;
    margin-bottom: 50px;
}

.owner-profile-image {
    width: 230px;
    height: 230px;
    border-radius: 12px;
    overflow: hidden;
    margin: 0 auto;
    background: #0b1f3a;
}

.owner-profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.owner-profile-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d4af37;
    font-size: 80px;
    font-weight: 600;
}

.owner-info {
    padding: 10px 20px;
}

.owner-info h1 {
    color: #0b1f3a;
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 10px;
}

.owner-role {
    color: #b08d22;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
}

.owner-detail {
    color: #687386;
    font-size: 15px;
    margin-bottom: 12px;
}

.owner-detail i {
    color: #d4af37;
    width: 25px;
}

.owner-bio {
    color: #555;
    line-height: 1.7;
    margin-top: 20px;
    margin-bottom: 25px;
}

.contact-owner-btn {
    display: inline-block;
    padding: 11px 24px;
    background: #0b1f3a;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}

.contact-owner-btn:hover {
    background: #d4af37;
    color: #0b1f3a;
}

.properties-heading {
    text-align: center;
    margin-bottom: 35px;
}

.properties-heading h2 {
    color: #0b1f3a;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 10px;
}

.properties-heading p {
    color: #687386;
    margin: 0;
}

.property-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #eeeeee;
    box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
    height: 100%;
    transition: 0.3s;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(11, 31, 58, 0.15);
}

.property-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

.property-no-image {
    width: 100%;
    height: 220px;
    background: #0b1f3a;
    color: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 45px;
}

.property-content {
    padding: 20px;
}

.property-content h3 {
    color: #0b1f3a;
    font-size: 19px;
    font-weight: 700;
    margin-bottom: 10px;
}

.property-price {
    color: #b08d22;
    font-size: 21px;
    font-weight: 700;
    margin-bottom: 10px;
}

.property-location {
    color: #687386;
    font-size: 14px;
    margin-bottom: 12px;
}

.property-location i {
    color: #d4af37;
    margin-right: 5px;
}

.property-info {
    color: #555;
    font-size: 14px;
    margin-bottom: 15px;
}

.property-info span {
    margin-right: 15px;
}

.property-info i {
    color: #d4af37;
    margin-right: 4px;
}

.view-property-btn {
    display: block;
    text-align: center;
    padding: 10px;
    background: #0b1f3a;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}

.view-property-btn:hover {
    background: #d4af37;
    color: #0b1f3a;
}

.no-properties {
    text-align: center;
    background: #ffffff;
    padding: 45px 20px;
    border-radius: 12px;
    border: 1px solid #eeeeee;
    color: #687386;
}

@media (max-width: 768px) {

    .owner-details-section {
        padding: 45px 15px;
    }

    .owner-profile-card {
        padding: 25px 20px;
    }

    .owner-profile-image {
        width: 190px;
        height: 190px;
        margin-bottom: 25px;
    }

    .owner-info {
        padding: 10px 0;
        text-align: center;
    }

    .owner-info h1 {
        font-size: 30px;
    }

}

</style>


<section class="owner-details-section">

    <div class="container">

        <!-- OWNER PROFILE -->

        <div class="owner-profile-card">

            <div class="row align-items-center">

                <div class="col-lg-4 text-center">

                    <div class="owner-profile-image">

                        <?php
                        if (!empty($owner['profile_photo'])) {
                        ?>

                            <img
                                src="admin/assets/images/owners/<?php echo htmlspecialchars($owner['profile_photo']); ?>"
                                alt="<?php echo htmlspecialchars($owner['full_name']); ?>"
                            >

                        <?php
                        } else {
                        ?>

                            <div class="owner-profile-placeholder">

                                <?php
                                echo strtoupper(
                                    substr($owner['full_name'], 0, 1)
                                );
                                ?>

                            </div>

                        <?php
                        }
                        ?>

                    </div>

                </div>


                <div class="col-lg-8">

                    <div class="owner-info">

                        <h1>
                            <?php echo htmlspecialchars($owner['full_name']); ?>
                        </h1>

                        <div class="owner-role">
                            Property Owner
                        </div>

                        <div class="owner-detail">
                            <i class="fa-solid fa-envelope"></i>
                            <?php echo htmlspecialchars($owner['email']); ?>
                        </div>

                        <div class="owner-detail">
                            <i class="fa-solid fa-phone"></i>
                            <?php echo htmlspecialchars($owner['phoneno']); ?>
                        </div>

                        <?php
                        if (!empty($owner['bio'])) {
                        ?>

                            <div class="owner-bio">
                                <?php
                                echo nl2br(
                                    htmlspecialchars($owner['bio'])
                                );
                                ?>
                            </div>

                        <?php
                        }
                        ?>

                        <a
                            href="contact.php"
                            class="contact-owner-btn"
                        >
                            <i class="fa-solid fa-envelope"></i>
                            Contact Owner
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- OWNER PROPERTIES -->

        <div class="properties-heading">

            <h2>
                Properties Listed by
                <?php echo htmlspecialchars($owner['full_name']); ?>
            </h2>

            <p>
                Explore properties currently available from this owner.
            </p>

        </div>


        <div class="row g-4">

            <?php
            if (mysqli_num_rows($propertyResult) > 0) {

                while ($property = mysqli_fetch_assoc($propertyResult)) {
            ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="property-card">

                            <?php
                            if (!empty($property['image_path'])) {
                            ?>

                                <img
                                    src="admin/<?php echo htmlspecialchars($property['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($property['title']); ?>"
                                    class="property-image"
                                >

                            <?php
                            } else {
                            ?>

                                <div class="property-no-image">
                                    <i class="fa-solid fa-house"></i>
                                </div>

                            <?php
                            }
                            ?>


                            <div class="property-content">

                                <h3>
                                    <?php
                                    echo htmlspecialchars(
                                        $property['title']
                                    );
                                    ?>
                                </h3>

                                <div class="property-price">

                                    ₹<?php
                                    echo number_format(
                                        (float)$property['price']
                                    );
                                    ?>

                                    <?php
                                    if ($property['listing_type'] === 'For Rent') {
                                        echo '/month';
                                    }
                                    ?>

                                </div>


                                <div class="property-location">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $property['city']
                                    );
                                    ?>,
                                    <?php
                                    echo htmlspecialchars(
                                        $property['state']
                                    );
                                    ?>

                                </div>


                                <div class="property-info">

                                    <?php
                                    if (!empty($property['area_sqft'])) {
                                    ?>

                                        <span>
                                            <i class="fa-solid fa-ruler-combined"></i>
                                            <?php echo (int)$property['area_sqft']; ?>
                                            sqft
                                        </span>

                                    <?php
                                    }
                                    ?>


                                    <?php
                                    if (!empty($property['bedrooms'])) {
                                    ?>

                                        <span>
                                            <i class="fa-solid fa-bed"></i>
                                            <?php echo (int)$property['bedrooms']; ?>
                                            Beds
                                        </span>

                                    <?php
                                    }
                                    ?>


                                    <?php
                                    if (!empty($property['bathrooms'])) {
                                    ?>

                                        <span>
                                            <i class="fa-solid fa-bath"></i>
                                            <?php echo (int)$property['bathrooms']; ?>
                                            Baths
                                        </span>

                                    <?php
                                    }
                                    ?>

                                </div>


                                <a
                                    href="properties/details.php?id=<?php echo (int)$property['property_id']; ?>"
                                    class="view-property-btn"
                                >
                                    View Property
                                </a>

                            </div>

                        </div>

                    </div>

            <?php
                }

            } else {
            ?>

                <div class="col-12">

                    <div class="no-properties">

                        <h4>
                            No properties listed yet.
                        </h4>

                        <p>
                            This owner currently has no approved and active properties.
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

mysqli_stmt_close($propertyStmt);

require_once __DIR__ . '/includes/footer.php';

?>