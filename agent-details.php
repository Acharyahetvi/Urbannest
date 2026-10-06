<?php
session_start();

require_once __DIR__ . '/db_connection.php';

$agent_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($agent_id <= 0) {
    header("Location: agents.php");
    exit;
}

/* =========================
   GET AGENT DETAILS
========================= */

$sql = "SELECT 
            u.user_id,
            u.full_name,
            u.email,
            ap.profile_photo,
            ap.agency_name,
            ap.experience,
            ap.license_number,
            ap.bio
        FROM users u
        LEFT JOIN agent_profiles ap
            ON u.user_id = ap.user_id
        WHERE u.user_id = ?
        AND u.role_id = 3
        AND u.status = 'Active'
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$agent = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$agent) {
    header("Location: agents.php");
    exit;
}


/* =========================
   GET AGENT PROPERTIES
========================= */

$propertySql = "SELECT 
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
mysqli_stmt_bind_param($propertyStmt, "i", $agent_id);
mysqli_stmt_execute($propertyStmt);

$propertyResult = mysqli_stmt_get_result($propertyStmt);


/* =========================
   PAGE INFO
========================= */

$pageTitle = htmlspecialchars($agent['full_name']) . " - UrbanNest";
$pageDesc = "View agent profile and listed properties.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
    html {
        overflow-x: hidden;
    }

    body {
        background-color: #f6f3ec;
    }

    .agent-details-page {
        max-width: 1224px;
        margin: 0 auto;
        padding: 42px 22px 52px;
    }

    .agent-profile-card {
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

    .agent-profile-image {
        width: 270px;
        height: 300px;
        flex: 0 0 270px;
        overflow: hidden;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        background: #0b1f3a;
    }

    .agent-profile-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 18%;
    }

    .agent-profile-placeholder {
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

    .agent-profile-info {
        flex: 1;
        min-width: 0;
    }

    .agent-profile-info h1,
    .agent-section-heading h2,
    .agent-about h2 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-weight: 600;
    }

    .agent-profile-info h1 {
        margin: 0 0 6px;
        font-size: 32px;
        line-height: 1.2;
    }

    .agent-role {
        margin-bottom: 18px;
        color: #b08d22;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .agent-profile-rule {
        width: 48px;
        height: 2px;
        margin-bottom: 18px;
        background: #d4af37;
    }

    .agent-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px 22px;
    }

    .agent-info-item {
        min-width: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .agent-info-item strong {
        display: block;
        margin-bottom: 2px;
        color: #0b1f3a;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .agent-actions {
        margin-top: 20px;
    }

    .agent-contact-btn,
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

    .agent-contact-btn:hover,
    .view-property-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .agent-about {
        max-width: 1180px;
        margin: 38px auto 0;
        padding: 0 4px;
    }

    .agent-about h2 {
        margin: 0 0 10px;
        font-size: 24px;
    }

    .agent-about-rule,
    .agent-heading-rule {
        width: 48px;
        height: 2px;
        margin: 0 auto 14px;
        background: #d4af37;
    }

    .agent-about-rule {
        margin-left: 0;
    }

    .agent-about p {
        max-width: 900px;
        margin: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .agent-properties-section {
        margin-top: 42px;
    }

    .agent-section-heading {
        margin-bottom: 26px;
        text-align: center;
    }

    .agent-section-heading h2 {
        margin: 0 0 10px;
        font-size: 30px;
        line-height: 1.25;
    }

    .agent-section-heading p {
        margin: 0;
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.55;
    }

    .agent-properties-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: stretch;
        gap: 22px 20px;
    }

    .agent-property-card {
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

    .agent-property-image {
        height: 205px;
        flex: 0 0 205px;
        overflow: hidden;
        background: #0b1f3a;
    }

    .agent-property-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .agent-property-no-image {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 15px;
    }

    .agent-property-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 17px 18px 18px;
    }

    .agent-property-content h3 {
        margin: 0 0 8px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 20px;
        font-weight: 600;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .agent-property-price {
        margin: 0 0 8px;
        color: #9a791f;
        font-size: 18px;
        font-weight: 600;
    }

    .agent-property-type,
    .agent-property-location {
        margin: 0 0 8px;
        color: #5f6b7a;
        font-size: 13px;
        line-height: 1.5;
    }

    .agent-property-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 14px;
        margin: 2px 0 16px;
        color: #5f6b7a;
        font-size: 13px;
    }

    .agent-property-meta span {
        white-space: nowrap;
    }

    .agent-property-meta i,
    .agent-property-location i {
        margin-right: 4px;
        color: #d4af37;
    }

    .agent-property-content .view-property-btn {
        width: 100%;
        margin-top: auto;
    }

    .agent-no-properties {
        margin: 0;
        padding: 30px 20px;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        background: #ffffff;
        color: #5f6b7a;
        text-align: center;
    }

    @media (max-width: 900px) {
        .agent-profile-card {
            gap: 24px;
        }

        .agent-profile-image {
            width: 235px;
            flex-basis: 235px;
        }

        .agent-properties-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 639px) {
        .agent-details-page {
            padding: 28px 18px 36px;
        }

        .agent-profile-card {
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
            padding: 18px;
        }

        .agent-profile-image {
            width: 100%;
            height: 260px;
            flex-basis: auto;
        }

        .agent-profile-info h1 {
            font-size: 28px;
        }

        .agent-info-grid {
            grid-template-columns: 1fr;
            gap: 11px;
        }

        .agent-about {
            margin-top: 30px;
        }

        .agent-properties-section {
            margin-top: 34px;
        }

        .agent-section-heading {
            margin-bottom: 20px;
        }

        .agent-section-heading h2 {
            font-size: 26px;
        }

        .agent-properties-grid {
            grid-template-columns: 1fr;
            max-width: 440px;
            margin: 0 auto;
        }
    }
</style>

<main class="agent-details-page">
    <section class="agent-profile-card" aria-label="Agent profile">
        <div class="agent-profile-image">
            <?php if (!empty($agent['profile_photo'])) { ?>
                <img
                    src="admin/assets/images/agents/<?php echo htmlspecialchars($agent['profile_photo']); ?>"
                    alt="<?php echo htmlspecialchars($agent['full_name']); ?>"
                >
            <?php } else { ?>
                <div class="agent-profile-placeholder">
                    <?php echo strtoupper(substr($agent['full_name'], 0, 1)); ?>
                </div>
            <?php } ?>
        </div>

        <div class="agent-profile-info">
            <h1><?php echo htmlspecialchars($agent['full_name']); ?></h1>
            <div class="agent-role">Real Estate Agent</div>
            <div class="agent-profile-rule"></div>

            <div class="agent-info-grid">
                <?php if (!empty($agent['agency_name'])) { ?>
                    <div class="agent-info-item">
                        <strong>Agency</strong>
                        <?php echo htmlspecialchars($agent['agency_name']); ?>
                    </div>
                <?php } ?>
                <?php if (!empty($agent['experience'])) { ?>
                    <div class="agent-info-item">
                        <strong>Experience</strong>
                        <?php echo (int)$agent['experience']; ?> Years
                    </div>
                <?php } ?>
                <?php if (!empty($agent['license_number'])) { ?>
                    <div class="agent-info-item">
                        <strong>License</strong>
                        <?php echo htmlspecialchars($agent['license_number']); ?>
                    </div>
                <?php } ?>
                <div class="agent-info-item">
                    <strong>Email</strong>
                    <?php echo htmlspecialchars($agent['email']); ?>
                </div>
            </div>

            <div class="agent-actions">
                <a
                    href="contact.php?id=<?php echo (int)$agent['user_id']; ?>"
                    class="agent-contact-btn"
                >Contact Agent</a>
            </div>
        </div>
    </section>

    <?php if (!empty($agent['bio'])) { ?>
        <section class="agent-about">
            <h2>About <?php echo htmlspecialchars($agent['full_name']); ?></h2>
            <div class="agent-about-rule"></div>
            <p><?php echo nl2br(htmlspecialchars($agent['bio'])); ?></p>
        </section>
    <?php } ?>

    <section class="agent-properties-section">
        <div class="agent-section-heading">
            <h2>Properties Listed by <?php echo htmlspecialchars($agent['full_name']); ?></h2>
            <div class="agent-heading-rule"></div>
            <p>Explore properties currently available from this agent.</p>
        </div>

        <?php if (mysqli_num_rows($propertyResult) > 0) { ?>
            <div class="agent-properties-grid">
                <?php while ($property = mysqli_fetch_assoc($propertyResult)) { ?>
                    <article class="agent-property-card">
                        <div class="agent-property-image">
                            <?php if (!empty($property['image_path'])) { ?>
                                <img
                                    src="admin/<?php echo htmlspecialchars($property['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($property['title']); ?>"
                                >
                            <?php } else { ?>
                                <div class="agent-property-no-image">No Image</div>
                            <?php } ?>
                        </div>

                        <div class="agent-property-content">
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p class="agent-property-price">
                                ₹<?php echo number_format((float)$property['price']); ?>
                                <?php if ($property['listing_type'] === 'For Rent') { ?>/month<?php } ?>
                            </p>
                            <p class="agent-property-type">
                                <?php echo htmlspecialchars($property['listing_type']); ?>
                            </p>
                            <p class="agent-property-location">
                                <i class="fa-solid fa-location-dot"></i>
                                <?php echo htmlspecialchars($property['city']); ?>,
                                <?php echo htmlspecialchars($property['state']); ?>
                            </p>
                            <div class="agent-property-meta">
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
            <p class="agent-no-properties">This agent currently has no listed properties.</p>
        <?php } ?>
    </section>
</main>


<?php

mysqli_stmt_close($propertyStmt);

require_once __DIR__ . '/includes/footer.php';

?>