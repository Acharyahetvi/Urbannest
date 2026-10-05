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

.agent-details-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
}

.agent-profile-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.agent-profile-image {
    width: 220px;
    height: 260px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f1f1f1;
}

.agent-profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.agent-profile-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
    font-weight: 600;
    color: #777;
}

.agent-profile-info {
    flex: 1;
}

.agent-profile-info h1 {
    margin: 0 0 8px;
    font-size: 32px;
}

.agent-role {
    color: #777;
    margin-bottom: 20px;
}

.agent-info-row {
    margin: 10px 0;
}

.agent-info-row strong {
    display: inline-block;
    min-width: 120px;
}

.agent-bio {
    margin-top: 20px;
    line-height: 1.7;
    color: #555;
}

.agent-actions {
    margin-top: 25px;
}

.agent-contact-btn {
    display: inline-block;
    padding: 11px 20px;
    border-radius: 6px;
    text-decoration: none;
    background: #222;
    color: #fff;
}

.agent-contact-btn:hover {
    color: #fff;
    opacity: 0.9;
}

.agent-properties-section {
    margin-top: 45px;
}

.agent-properties-section h2 {
    margin-bottom: 25px;
}

.agent-properties-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.agent-property-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.agent-property-image {
    width: 100%;
    height: 200px;
    background: #eee;
    overflow: hidden;
}

.agent-property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.agent-property-content {
    padding: 18px;
}

.agent-property-content h3 {
    margin: 0 0 8px;
    font-size: 19px;
}

.agent-property-content p {
    margin: 6px 0;
    color: #666;
}

.agent-property-price {
    font-weight: 600;
    margin-top: 12px !important;
}

.view-property-btn {
    display: inline-block;
    margin-top: 12px;
    padding: 9px 16px;
    border-radius: 6px;
    text-decoration: none;
    background: #222;
    color: #fff;
}

.view-property-btn:hover {
    color: #fff;
    opacity: 0.9;
}

.no-properties {
    color: #777;
}

@media (max-width: 800px) {

    .agent-profile-card {
        flex-direction: column;
    }

    .agent-profile-image {
        width: 100%;
        height: 300px;
    }

    .agent-properties-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 550px) {

    .agent-properties-grid {
        grid-template-columns: 1fr;
    }

    .agent-profile-info h1 {
        font-size: 26px;
    }
}

</style>


<div class="agent-details-container">

    <!-- =========================
         AGENT PROFILE
    ========================== -->

    <div class="agent-profile-card">

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

            <h1>
                <?php echo htmlspecialchars($agent['full_name']); ?>
            </h1>

            <div class="agent-role">
                Real Estate Agent
            </div>


            <?php if (!empty($agent['agency_name'])) { ?>
                <div class="agent-info-row">
                    <strong>Agency:</strong>
                    <?php echo htmlspecialchars($agent['agency_name']); ?>
                </div>
            <?php } ?>


            <?php if (!empty($agent['experience'])) { ?>
                <div class="agent-info-row">
                    <strong>Experience:</strong>
                    <?php echo (int)$agent['experience']; ?> years
                </div>
            <?php } ?>


            <?php if (!empty($agent['license_number'])) { ?>
                <div class="agent-info-row">
                    <strong>License:</strong>
                    <?php echo htmlspecialchars($agent['license_number']); ?>
                </div>
            <?php } ?>


            <div class="agent-info-row">
                <strong>Email:</strong>
                <?php echo htmlspecialchars($agent['email']); ?>
            </div>


            <?php if (!empty($agent['bio'])) { ?>

                <div class="agent-bio">
                    <strong>About:</strong><br>
                    <?php echo nl2br(htmlspecialchars($agent['bio'])); ?>
                </div>

            <?php } ?>


            <div class="agent-actions">

                <a
                    href="contact.php?id=<?php echo (int)$agent['user_id']; ?>"
                    class="agent-contact-btn"
                >
                    <i class="fa-solid fa-envelope"></i>
                    Contact Agent
                </a>

            </div>

        </div>

    </div>


    <!-- =========================
         AGENT PROPERTIES
    ========================== -->

    <div class="agent-properties-section">

        <h2>
            Properties Listed by <?php echo htmlspecialchars($agent['full_name']); ?>
        </h2>


        <?php if (mysqli_num_rows($propertyResult) > 0) { ?>

            <div class="agent-properties-grid">

                <?php while ($property = mysqli_fetch_assoc($propertyResult)) { ?>

                    <div class="agent-property-card">

                        <div class="agent-property-image">

                            <?php if (!empty($property['image_path'])) { ?>

                                <img
                                    src="admin/<?php echo htmlspecialchars($property['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($property['title']); ?>"
                                >

                            <?php } else { ?>

                                <div style="height:100%; display:flex; align-items:center; justify-content:center;">
                                    No Image
                                </div>

                            <?php } ?>

                        </div>


                        <div class="agent-property-content">

                            <h3>
                                <?php echo htmlspecialchars($property['title']); ?>
                            </h3>


                            <p>
                                <?php echo htmlspecialchars($property['city']); ?>,
                                <?php echo htmlspecialchars($property['state']); ?>
                            </p>


                            <p>
                                <?php echo htmlspecialchars($property['listing_type']); ?>
                            </p>


                            <p class="agent-property-price">
                                ₹<?php echo number_format((float)$property['price']); ?>
                            </p>


                            <a
                                href="properties/details.php?id=<?php echo (int)$property['property_id']; ?>"
                                class="view-property-btn"
                            >
                                View Property
                            </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <p class="no-properties">
                This agent currently has no listed properties.
            </p>

        <?php } ?>

    </div>

</div>


<?php

mysqli_stmt_close($propertyStmt);

require_once __DIR__ . '/includes/footer.php';

?>