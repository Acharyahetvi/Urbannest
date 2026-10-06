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
            (
                SELECT COUNT(*)
                FROM properties p
                WHERE p.user_id = u.user_id
            ) AS property_count,
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
    html {
        overflow-x: hidden;
    }

    body {
        background-color: #f6f3ec;
    }

    .owner-details-page {
        max-width: 1224px;
        margin: 0 auto;
        padding: 42px 22px 52px;
    }

    .owner-profile-card {
        display: flex;
        align-items: center;
        gap: 32px;
        padding: 26px;
        background: #ffffff;
        border: 1px solid #e4ddd0;
        border-top: 3px solid #d4af37;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(11, 31, 58, 0.05);
    }

    .owner-profile-image {
        width: 270px;
        height: 300px;
        flex: 0 0 270px;
        overflow: hidden;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        background: #0b1f3a;
    }

    .owner-profile-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 18%;
    }

    .owner-profile-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 70px;
        font-weight: 600;
    }

    .owner-profile-info {
        flex: 1;
        min-width: 0;
    }

    .owner-profile-info h1,
    .owner-section-heading h2,
    .owner-about h2 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-weight: 600;
    }

    .owner-profile-info h1 {
        margin: 0 0 6px;
        font-size: 32px;
        line-height: 1.2;
    }

    .owner-role {
        margin-bottom: 18px;
        color: #b08d22;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .owner-profile-rule {
        width: 48px;
        height: 2px;
        margin-bottom: 18px;
        background: #d4af37;
    }

    .owner-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px 22px;
    }

    .owner-info-item {
        min-width: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .owner-info-item strong {
        display: block;
        margin-bottom: 2px;
        color: #0b1f3a;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .contact-owner-btn,
    .view-property-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 18px;
        border: 0;
        border-radius: 5px;
        background: #0b1f3a;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .contact-owner-btn {
        margin-top: 20px;
    }

    .contact-owner-btn:hover,
    .view-property-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .owner-about {
        max-width: 1180px;
        margin: 38px auto 0;
        padding: 0 4px;
    }

    .owner-about h2 {
        margin: 0 0 10px;
        font-size: 24px;
    }

    .owner-about-rule,
    .owner-heading-rule {
        width: 48px;
        height: 2px;
        margin: 0 auto 14px;
        background: #d4af37;
    }

    .owner-about-rule {
        margin-left: 0;
    }

    .owner-about p {
        max-width: 900px;
        margin: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .owner-properties-section {
        margin-top: 42px;
    }

    .owner-section-heading {
        margin-bottom: 26px;
        text-align: center;
    }

    .owner-section-heading h2 {
        margin: 0 0 10px;
        font-size: 30px;
        line-height: 1.25;
    }

    .owner-section-heading p {
        margin: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.55;
    }

    .owner-properties-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: stretch;
        gap: 22px 20px;
    }

    .owner-property-card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e4ddd0;
        border-top: 3px solid #d4af37;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(11, 31, 58, 0.05);
    }

    .owner-property-image {
        height: 205px;
        flex: 0 0 205px;
        overflow: hidden;
        background: #0b1f3a;
    }

    .owner-property-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .owner-property-no-image {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 15px;
    }

    .owner-property-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 17px 18px 18px;
    }

    .owner-property-content h3 {
        margin: 0 0 8px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 20px;
        font-weight: 600;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .owner-property-price {
        margin: 0 0 8px;
        color: #9a791f;
        font-size: 18px;
        font-weight: 600;
    }

    .owner-property-type,
    .owner-property-location {
        margin: 0 0 8px;
        color: #5f6b7a;
        font-size: 13px;
        line-height: 1.5;
    }

    .owner-property-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 14px;
        margin: 2px 0 16px;
        color: #5f6b7a;
        font-size: 13px;
    }

    .owner-property-meta span {
        white-space: nowrap;
    }

    .owner-property-meta i,
    .owner-property-location i {
        margin-right: 4px;
        color: #d4af37;
    }

    .owner-property-content .view-property-btn {
        width: 100%;
        margin-top: auto;
    }

    .owner-no-properties {
        margin: 0;
        padding: 30px 20px;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        background: #ffffff;
        color: #5f6b7a;
        text-align: center;
    }

    @media (max-width: 900px) {
        .owner-profile-card {
            gap: 24px;
        }

        .owner-profile-image {
            width: 235px;
            flex-basis: 235px;
        }

        .owner-properties-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 639px) {
        .owner-details-page {
            padding: 28px 18px 36px;
        }

        .owner-profile-card {
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
            padding: 18px;
        }

        .owner-profile-image {
            width: 100%;
            height: 260px;
            flex-basis: auto;
        }

        .owner-profile-info h1 {
            font-size: 28px;
        }

        .owner-info-grid {
            grid-template-columns: 1fr;
            gap: 11px;
        }

        .owner-about {
            margin-top: 30px;
        }

        .owner-properties-section {
            margin-top: 34px;
        }

        .owner-section-heading {
            margin-bottom: 20px;
        }

        .owner-section-heading h2 {
            font-size: 26px;
        }

        .owner-properties-grid {
            grid-template-columns: 1fr;
            max-width: 440px;
            margin: 0 auto;
        }
    }
</style>

<main class="owner-details-page">
    <section class="owner-profile-card" aria-label="Owner profile">
        <div class="owner-profile-image">
            <?php if (!empty($owner['profile_photo'])) { ?>
                <img
                    src="admin/assets/images/owners/<?php echo htmlspecialchars($owner['profile_photo']); ?>"
                    alt="<?php echo htmlspecialchars($owner['full_name']); ?>"
                >
            <?php } else { ?>
                <div class="owner-profile-placeholder">
                    <?php echo strtoupper(substr($owner['full_name'], 0, 1)); ?>
                </div>
            <?php } ?>
        </div>

        <div class="owner-profile-info">
            <h1><?php echo htmlspecialchars($owner['full_name']); ?></h1>
            <div class="owner-role">Property Owner</div>
            <div class="owner-profile-rule"></div>

            <div class="owner-info-grid">
                <div class="owner-info-item">
                    <strong>Email</strong>
                    <?php echo htmlspecialchars($owner['email']); ?>
                </div>
                <div class="owner-info-item">
                    <strong>Phone</strong>
                    <?php echo htmlspecialchars($owner['phoneno']); ?>
                </div>
                <div class="owner-info-item">
                    <strong>Properties Listed</strong>
                    <?php echo (int)$owner['property_count']; ?>
                </div>
            </div>

            <a href="contact.php" class="contact-owner-btn">Contact Owner</a>
        </div>
    </section>

    <?php if (!empty($owner['bio'])) { ?>
        <section class="owner-about">
            <h2>About <?php echo htmlspecialchars($owner['full_name']); ?></h2>
            <div class="owner-about-rule"></div>
            <p><?php echo nl2br(htmlspecialchars($owner['bio'])); ?></p>
        </section>
    <?php } ?>

    <section class="owner-properties-section">
        <div class="owner-section-heading">
            <h2>Properties Listed by <?php echo htmlspecialchars($owner['full_name']); ?></h2>
            <div class="owner-heading-rule"></div>
            <p>Explore properties currently available from this owner.</p>
        </div>

        <?php if (mysqli_num_rows($propertyResult) > 0) { ?>
            <div class="owner-properties-grid">
                <?php while ($property = mysqli_fetch_assoc($propertyResult)) { ?>
                    <article class="owner-property-card">
                        <div class="owner-property-image">
                            <?php if (!empty($property['image_path'])) { ?>
                                <img
                                    src="admin/<?php echo htmlspecialchars($property['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($property['title']); ?>"
                                >
                            <?php } else { ?>
                                <div class="owner-property-no-image">No Image</div>
                            <?php } ?>
                        </div>

                        <div class="owner-property-content">
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p class="owner-property-price">
                                ₹<?php echo number_format((float)$property['price']); ?>
                                <?php if ($property['listing_type'] === 'For Rent') { ?>/month<?php } ?>
                            </p>
                            <p class="owner-property-type">
                                <?php echo htmlspecialchars($property['listing_type']); ?>
                            </p>
                            <p class="owner-property-location">
                                <i class="fa-solid fa-location-dot"></i>
                                <?php echo htmlspecialchars($property['city']); ?>,
                                <?php echo htmlspecialchars($property['state']); ?>
                            </p>
                            <div class="owner-property-meta">
                                <?php if (!empty($property['area_sqft'])) { ?>
                                    <span><i class="fa-solid fa-ruler-combined"></i><?php echo (int)$property['area_sqft']; ?> sqft</span>
                                <?php } ?>
                                <?php if (!empty($property['bedrooms'])) { ?>
                                    <span><i class="fa-solid fa-bed"></i><?php echo (int)$property['bedrooms']; ?> Beds</span>
                                <?php } ?>
                                <?php if (!empty($property['bathrooms'])) { ?>
                                    <span><i class="fa-solid fa-bath"></i><?php echo (int)$property['bathrooms']; ?> Baths</span>
                                <?php } ?>
                            </div>
                            <a
                                href="properties/details.php?id=<?php echo (int)$property['property_id']; ?>"
                                class="view-property-btn"
                            >View Property</a>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="owner-no-properties">
                <p>No properties listed yet.</p>
                <p>This owner currently has no approved and active properties.</p>
            </div>
        <?php } ?>
    </section>
</main>


<?php

mysqli_stmt_close($propertyStmt);

require_once __DIR__ . '/includes/footer.php';

?>