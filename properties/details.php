<?php
$pageTitle = 'Property Details - UrbanNest';
$pageDesc  = 'View complete property details, amenities, gallery and contact information on UrbanNest.';

require_once __DIR__ . '/../db_connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$propertyId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$property = null;
$images = [];
$amenities = [];
$listedBy = null;
$otherProperties = [];
$inquiryMessage = '';
$inquirySuccess = false;

function detailsEsc($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function detailsPropertyImage(?string $path): string
{
    if (!empty($path)) {
        return '../admin/' . ltrim($path, '/');
    }
    return '../admin/assets/images/no-property.png';
}

function detailsFormatPrice($price, string $listingType): string
{
    $price = (float)$price;
    if ($listingType === 'For Rent') {
        return '₹' . number_format($price) . ' / month';
    }
    return '₹' . number_format($price);
}

/* ------------------------------------------------- 
   PROPERTY 
------------------------------------------------- */
if ($propertyId > 0) {
    $stmt = mysqli_prepare($conn, " 
        SELECT 
            p.*, 
            s.subcategory_name, 
            c.category_name 
        FROM properties p 
        LEFT JOIN sub_categories s 
            ON p.subcategory_id = s.subcategory_id 
        LEFT JOIN categories c 
            ON s.category_id = c.category_id 
        WHERE p.property_id = ? 
          AND p.approval_status = 'Approved' 
          AND p.status = 'Active' 
        LIMIT 1 
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $propertyId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $property = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

/* -------------------------------------------------
   WISHLIST
------------------------------------------------- */

$wishlistUserId = (int)($_SESSION['user_id'] ?? 0);
$isWishlisted = false;

if ($property && $wishlistUserId > 0) {

    $wishlistCheck = mysqli_prepare(
        $conn,
        "SELECT wishlist_id
         FROM wishlist
         WHERE user_id = ? AND property_id = ?
         LIMIT 1"
    );

    if ($wishlistCheck) {

        mysqli_stmt_bind_param(
            $wishlistCheck,
            'ii',
            $wishlistUserId,
            $propertyId
        );

        mysqli_stmt_execute($wishlistCheck);

        $wishlistResult = mysqli_stmt_get_result($wishlistCheck);

        $isWishlisted = mysqli_num_rows($wishlistResult) > 0;

        mysqli_stmt_close($wishlistCheck);
    }
}

/* ------------------------------------------------- 
   INQUIRY 
------------------------------------------------- */
if ($property && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_inquiry'])) {
    $message = trim($_POST['message'] ?? '');
    $sessionUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if ($sessionUserId <= 0) {
        $inquiryMessage = 'Please log in before sending an inquiry.';
    } elseif ($message === '') {
        $inquiryMessage = 'Please enter a message.';
    } else {
        $status = 'Pending';
        $insert = mysqli_prepare($conn, " 
            INSERT INTO inquiries (user_id, property_id, message, status, created_at) 
            VALUES (?, ?, ?, ?, NOW()) 
        ");

        if ($insert) {
            mysqli_stmt_bind_param($insert, 'iiss', $sessionUserId, $propertyId, $message, $status);
            if (mysqli_stmt_execute($insert)) {
                $inquirySuccess = true;
                $inquiryMessage = 'Your inquiry has been sent successfully.';
            } else {
                $inquiryMessage = 'Unable to send the inquiry right now. Please try again.';
            }
            mysqli_stmt_close($insert);
        } else {
            $inquiryMessage = 'Unable to send the inquiry right now.';
        }
    }
}

/* ------------------------------------------------- 
   GALLERY 
------------------------------------------------- */
if ($property) {
    $stmt = mysqli_prepare($conn, " 
        SELECT image_id, image_path, is_main 
        FROM property_images 
        WHERE property_id = ? 
        ORDER BY is_main DESC, image_id ASC 
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $propertyId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $images[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}

/* ------------------------------------------------- 
   AMENITIES 
------------------------------------------------- */
if ($property) {
    $stmt = mysqli_prepare($conn, " 
        SELECT a.amenity_id, a.amenity_name 
        FROM amenities a 
        INNER JOIN property_amenities pa 
            ON pa.amenity_id = a.amenity_id 
        WHERE pa.property_id = ? 
        ORDER BY a.amenity_id 
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $propertyId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $amenities[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}

/* ------------------------------------------------- 
   LISTED BY 
   Uses the existing users + roles + agent_profiles / 
   owner_profiles structure from the UrbanNest DB. 
------------------------------------------------- */
if ($property) {
    $stmt = mysqli_prepare($conn, " 
        SELECT 
            u.user_id, 
            u.full_name, 
            u.phoneno, 
            u.email, 
            r.role_name, 
            ap.profile_photo AS agent_photo, 
            ap.agency_name, 
            ap.experience, 
            ap.license_number, 
            ap.bio AS agent_bio, 
            op.profile_photo AS owner_photo, 
            op.property_count, 
            op.bio AS owner_bio 
        FROM users u 
        LEFT JOIN roles r 
            ON u.role_id = r.role_id 
        LEFT JOIN agent_profiles ap 
            ON u.user_id = ap.user_id 
        LEFT JOIN owner_profiles op 
            ON u.user_id = op.user_id 
        WHERE u.user_id = ? 
        LIMIT 1 
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $property['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $listedBy = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

/* ------------------------------------------------- 
   OTHER PROPERTIES 
------------------------------------------------- */
if ($property) {
    $stmt = mysqli_prepare($conn, " 
        SELECT 
            p.property_id, 
            p.title, 
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
        WHERE p.property_id <> ? 
          AND p.subcategory_id = ? 
          AND p.approval_status = 'Approved' 
          AND p.status = 'Active' 
        ORDER BY p.created_at DESC 
        LIMIT 4 
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $propertyId, $property['subcategory_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $otherProperties[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}

$hasImages = !empty($images);
$mainImage = $hasImages ? detailsPropertyImage($images[0]['image_path']) : detailsPropertyImage(null);
$roleName = $listedBy['role_name'] ?? 'Property Lister';
$profilePhoto = null;
$profileBio = '';

if ($listedBy) {
    if (strcasecmp($roleName, 'Agent') === 0) {
        if (!empty($listedBy['agent_photo'])) {
            $profilePhoto = '../admin/assets/images/agents/' . $listedBy['agent_photo'];
        }

        $profileBio = $listedBy['agent_bio'] ?? '';

    } else {
        if (!empty($listedBy['owner_photo'])) {
            $profilePhoto = '../admin/assets/images/owners/' . $listedBy['owner_photo'];
        }

        $profileBio = $listedBy['owner_bio'] ?? '';
    }
}

if (!$property): ?>
    <style>
        .un-not-found {
            max-width: 900px;
            margin: 70px auto;
            padding: 60px 25px;
            text-align: center;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(11, 31, 58, .08);
        }

        .un-not-found h1 {
            color: #0b1f3a;
            margin-bottom: 12px;
        }

        .un-not-found p {
            color: #687386;
            margin-bottom: 25px;
        }

        .un-back {
            display: inline-block;
            padding: 12px 22px;
            background: #0b1f3a;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
    <div class="un-not-found">
        <h1>Property Not Found</h1>
        <p>The property may no longer be available, or the link may be invalid.</p>
        <a class="un-back" href="../property-results.php?type=buy">Back to Properties</a>
    </div>
<?php else: ?>

    <style>
        .details-page {
            background: #f6f7fa;
            padding: 42px 0 70px;
        }

        .details-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 18px;
        }

        .details-breadcrumb {
            color: #748094;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .details-breadcrumb a {
            color: #0b1f3a;
            text-decoration: none;
            font-weight: 600;
        }

        .details-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 24px;
        }

        .details-title-row h1 {
            color: #0b1f3a;
            font-size: 32px;
            line-height: 1.2;
            margin: 0 0 9px;
            font-weight: 750;
        }

        .details-location {
            color: #697588;
            font-size: 15px;
        }

        .details-price {
            color: #b08d22;
            font-size: 28px;
            font-weight: 750;
            white-space: nowrap;
        }

        .details-gallery-main {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 22px rgba(11, 31, 58, .08);
        }

        .details-main-img {
            width: 100%;
            height: 540px;
            object-fit: cover;
            object-position: center center;
            display: block;
        }

        .details-thumbs {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 9px;
            padding: 10px;
        }

        .details-thumb {
            border: 2px solid transparent;
            border-radius: 9px;
            overflow: hidden;
            padding: 0;
            background: none;
            cursor: pointer;
        }

        .details-thumb.active {
            border-color: #b08d22;
        }

        .details-thumb img {
            width: 100%;
            height: 78px;
            object-fit: cover;
            display: block;
        }

        .details-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 24px;
            margin-top: 24px;
        }

        .details-card {
            background: #fff;
            border: 1px solid #e8ebf0;
            border-radius: 16px;
            padding: 27px;
            box-shadow: 0 4px 18px rgba(11, 31, 58, .05);
            margin-bottom: 20px;
        }

        .details-card h2 {
            color: #0b1f3a;
            font-size: 21px;
            margin: 0 0 18px;
        }

        .details-description {
            color: #5d6878;
            line-height: 1.75;
            font-size: 15px;
        }

        .details-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .details-stat {
            background: #f7f8fb;
            border-radius: 11px;
            padding: 16px 13px;
            text-align: center;
        }

        .details-stat i {
            color: #b08d22;
            font-size: 18px;
            margin-bottom: 7px;
        }

        .details-stat strong {
            display: block;
            color: #0b1f3a;
            font-size: 15px;
        }

        .details-stat span {
            display: block;
            color: #788397;
            font-size: 12px;
            margin-top: 3px;
        }

        .details-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .details-info-item {
            padding: 13px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .details-info-item:nth-child(odd) {
            padding-right: 18px;
        }

        .details-info-item:nth-child(even) {
            padding-left: 18px;
            border-left: 1px solid #edf0f4;
        }

        .details-info-label {
            display: block;
            color: #7a8494;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .details-info-value {
            color: #1c2940;
            font-weight: 650;
            font-size: 14px;
        }

        .amenity-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .amenity-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f6f8fa;
            border: 1px solid #e4e8ed;
            color: #26344b;
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 13px;
        }

        .amenity-chip i {
            color: #b08d22;
        }

        .listed-card {
            position: sticky;
            top: 20px;
        }

        .listed-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
        }

        .listed-profile-link {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .listed-profile-link:hover {
            opacity: 0.82;
        }

        .listed-profile-link:focus-visible {
            outline: 2px solid #d4af37;
            outline-offset: 4px;
        }

        .listed-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            background: #eef1f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b1f3a;
            font-size: 22px;
            overflow: hidden;
        }

        .listed-heading h3 {
            color: #0b1f3a;
            margin: 0 0 3px;
            font-size: 18px;
        }

        .listed-role {
            color: #b08d22;
            font-size: 12px;
            font-weight: 700;
        }

        .listed-detail {
            color: #5f6b7d;
            font-size: 13px;
            margin: 9px 0;
        }

        .listed-detail i {
            width: 20px;
            color: #0b1f3a;
        }

        .listed-bio {
            color: #687386;
            line-height: 1.55;
            font-size: 13px;
            margin: 15px 0 18px;
        }

        .inquiry-title {
            margin-top: 23px !important;
            padding-top: 20px;
            border-top: 1px solid #edf0f4;
        }

        .inquiry-form textarea {
            width: 100%;
            min-height: 105px;
            resize: vertical;
            border: 1px solid #dce1e8;
            border-radius: 10px;
            padding: 12px;
            outline: none;
            font: inherit;
            font-size: 13px;
        }

        .inquiry-form textarea:focus {
            border-color: #b08d22;
        }

        .inquiry-btn {
            width: 100%;
            border: 0;
            background: #0b1f3a;
            color: #fff;
            padding: 12px;
            border-radius: 9px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }

        .inquiry-note {
            color: #7b8492;
            font-size: 12px;
            line-height: 1.5;
            margin-top: 9px;
        }

        /* =====================================
   BOOK A VISIT BUTTON
===================================== */

        .book-visit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            width: 100%;
            padding: 11px 18px;

            background: #fff;
            color:
                #0b1f3a;
            border: 2px solid #d4af37;
            border-radius: 7px;

            text-decoration: none;
            font-size: 14px;
            font-weight: 700;

            margin-top: 15px;

            transition: all 0.25s ease;
        }

        .book-visit-btn i {
            color: #b08d22;
            font-size: 14px;
        }

        .book-visit-btn:hover {
            background: #0b1f3a;
            color: #fff;
            border-color: #0b1f3a;
        }

        .book-visit-btn:hover i {
            color: #d4af37;
        }

        .wishlist-detail-form {
            margin-top: 10px;
        }

        .wishlist-detail-btn {
            width: 100%;
            padding: 11px 18px;
            background: #fff;
            color: #0b1f3a;
            border: 2px solid #d4af37;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .wishlist-detail-btn i {
            margin-right: 6px;
            color: #b08d22;
        }

        .wishlist-detail-btn:hover {
            background: #0b1f3a;
            color: #fff;
            border-color: #0b1f3a;
        }

        .wishlist-detail-btn:hover i {
            color: #d4af37;
        }

        .inquiry-message {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .inquiry-success {
            background: #eaf7ef;
            color: #247341;
        }

        .inquiry-error {
            background: #fff0f0;
            color: #a33434;
        }

        .other-section {
            margin-top: 42px;
        }

        .other-section h2 {
            color: #0b1f3a;
            margin-bottom: 18px;
            font-size: 25px;
        }

        .other-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .other-card {
            background: #fff;
            border-radius: 13px;
            overflow: hidden;
            border: 1px solid #e8ebf0;
            box-shadow: 0 4px 16px rgba(11, 31, 58, .05);
        }

        .other-card img {
            width: 100%;
            height: 175px;
            object-fit: cover;
            display: block;
        }

        .other-content {
            padding: 16px;
        }

        .other-content h3 {
            color: #0b1f3a;
            font-size: 16px;
            line-height: 1.35;
            margin: 0 0 8px;
        }

        .other-price {
            color: #b08d22;
            font-weight: 750;
            margin-bottom: 7px;
        }

        .other-meta {
            color: #6d7889;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .other-btn {
            display: block;
            text-align: center;
            text-decoration: none;
            background: #0b1f3a;
            color: #fff;
            padding: 9px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 650;
        }

        @media (max-width: 900px) {
            .details-layout {
                grid-template-columns: 1fr;
            }

            .listed-card {
                position: static;
            }

            .other-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .details-page {
                padding-top: 25px;
            }

            .details-title-row {
                display: block;
            }

            .details-title-row h1 {
                font-size: 25px;
            }

            .details-price {
                margin-top: 10px;
                font-size: 23px;
            }

            .details-main-img {
                height: 300px;
            }

            .details-thumbs {
                grid-template-columns: repeat(4, 1fr);
            }

            .details-thumb img {
                height: 65px;
            }

            .details-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .details-info-grid {
                grid-template-columns: 1fr;
            }

            .details-info-item:nth-child(even) {
                padding-left: 0;
                border-left: 0;
            }

            .details-info-item:nth-child(odd) {
                padding-right: 0;
            }

            .other-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="details-page">
        <div class="details-wrap">
            <div class="details-breadcrumb">
                <a href="../property-results.php?type=<?= $property['listing_type'] === 'For Rent' ? 'rent' : 'buy' ?>">Properties</a>
                <span> / <?= detailsEsc($property['subcategory_name'] ?? 'Property') ?></span>
            </div>

            <div class="details-title-row">
                <div>
                    <h1><?= detailsEsc($property['title']) ?></h1>
                    <div class="details-location">
                        <i class="fa-solid fa-location-dot"></i>
                        <?= detailsEsc($property['address']) ?>, <?= detailsEsc($property['city']) ?>, <?= detailsEsc($property['state']) ?>
                    </div>
                </div>
                <div class="details-price">
                    <?= detailsEsc(detailsFormatPrice($property['price'], $property['listing_type'])) ?>
                </div>
            </div>

            <div class="details-gallery-main">
                <img id="mainPropertyImage" class="details-main-img" src="<?= detailsEsc($mainImage) ?>" alt="<?= detailsEsc($property['title']) ?>">

                <?php if ($hasImages): ?>
                    <div class="details-thumbs">
                        <?php foreach ($images as $index => $image): ?>
                            <button type="button" class="details-thumb <?= $index === 0 ? 'active' : '' ?>" data-image="<?= detailsEsc(detailsPropertyImage($image['image_path'])) ?>">
                                <img src="<?= detailsEsc(detailsPropertyImage($image['image_path'])) ?>" alt="Property image <?= $index + 1 ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="details-layout">
                <main>
                    <div class="details-card">
                        <h2>Property Overview</h2>
                        <div class="details-stats">
                            <div class="details-stat"><i class="fa-solid fa-ruler-combined"></i><strong><?= number_format((int)$property['area_sqft']) ?></strong><span>Sq. Ft.</span></div>
                            <div class="details-stat"><i class="fa-solid fa-bed"></i><strong><?= $property['bedrooms'] !== null ? (int)$property['bedrooms'] . ' BHK' : 'N/A' ?></strong><span>Bedrooms</span></div>
                            <div class="details-stat"><i class="fa-solid fa-bath"></i><strong><?= $property['bathrooms'] !== null ? (int)$property['bathrooms'] : 'N/A' ?></strong><span>Bathrooms</span></div>
                            <div class="details-stat"><i class="fa-solid fa-car"></i><strong><?= $property['parking_spaces'] !== null ? (int)$property['parking_spaces'] : 'N/A' ?></strong><span>Parking</span></div>
                        </div>
                    </div>

                    <div class="details-card">
                        <h2>Description</h2>
                        <div class="details-description"><?= nl2br(detailsEsc($property['description'])) ?></div>
                    </div>

                    <div class="details-card">
                        <h2>Property Details</h2>
                        <div class="details-info-grid">
                            <div class="details-info-item"><span class="details-info-label">Listing Type</span><span class="details-info-value"><?= detailsEsc($property['listing_type']) ?></span></div>
                            <div class="details-info-item"><span class="details-info-label">Property Type</span><span class="details-info-value"><?= detailsEsc($property['category_name'] ?? 'Property') ?></span></div>
                            <div class="details-info-item"><span class="details-info-label">Subcategory</span><span class="details-info-value"><?= detailsEsc($property['subcategory_name'] ?? 'N/A') ?></span></div>
                            <div class="details-info-item"><span class="details-info-label">Furnishing</span><span class="details-info-value"><?= detailsEsc($property['furnishing'] ?? 'N/A') ?></span></div>
                            <div class="details-info-item"><span class="details-info-label">Condition</span><span class="details-info-value"><?= detailsEsc($property['property_condition'] ?? 'N/A') ?></span></div>
                            <div class="details-info-item"><span class="details-info-label">Floor</span><span class="details-info-value"><?= $property['floor_number'] !== null ? (int)$property['floor_number'] . ' of ' . (int)$property['total_floors'] : 'N/A' ?></span></div>
                            <?php if ($property['plot_length'] !== null): ?>
                                <div class="details-info-item"><span class="details-info-label">Plot Size</span><span class="details-info-value"><?= detailsEsc($property['plot_length']) ?> × <?= detailsEsc($property['plot_width']) ?> ft</span></div>
                            <?php endif; ?>
                            <div class="details-info-item"><span class="details-info-label">City / State</span><span class="details-info-value"><?= detailsEsc($property['city']) ?>, <?= detailsEsc($property['state']) ?></span></div>
                        </div>
                    </div>

                    <div class="details-card">
                        <h2>Amenities</h2>
                        <?php if ($amenities): ?>
                            <div class="amenity-list">
                                <?php foreach ($amenities as $amenity): ?>
                                    <span class="amenity-chip"><i class="fa-solid fa-check"></i><?= detailsEsc($amenity['amenity_name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="details-description">No amenities have been listed for this property.</p>
                        <?php endif; ?>
                    </div>
                </main>

                <aside>
                    <div class="details-card listed-card">
                        <h2>Listed By</h2>

                        <?php if ($listedBy): ?>
                            <a
                                class="listed-heading listed-profile-link"
                                href="<?= strcasecmp($roleName, 'Agent') === 0 ? '../agent-details.php' : '../owner-details.php' ?>?id=<?= (int)$listedBy['user_id'] ?>"
                            >
                                <div class="listed-avatar">
                                    <?php if (!empty($profilePhoto)): ?>
                                        <img src="<?= detailsEsc($profilePhoto) ?>" alt="<?= detailsEsc($listedBy['full_name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                                        <?php else: ?>
                                        <i class="fa-solid fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3><?= detailsEsc($listedBy['full_name']) ?></h3>
                                    <div class="listed-role"><?= detailsEsc($roleName) ?></div>
                                </div>
                            </a>

                            <?php if (!empty($listedBy['agency_name']) && strcasecmp($roleName, 'Agent') === 0): ?>
                                <div class="listed-detail"><i class="fa-solid fa-building"></i><?= detailsEsc($listedBy['agency_name']) ?></div>
                            <?php endif; ?>

                            <?php if (!empty($listedBy['experience']) && strcasecmp($roleName, 'Agent') === 0): ?>
                                <div class="listed-detail"><i class="fa-solid fa-briefcase"></i><?= (int)$listedBy['experience'] ?> years experience</div>
                            <?php endif; ?>

                            <div class="listed-detail"><i class="fa-solid fa-phone"></i><?= detailsEsc($listedBy['phoneno']) ?></div>
                            <div class="listed-detail"><i class="fa-solid fa-envelope"></i><?= detailsEsc($listedBy['email']) ?></div>

                            <?php if (!empty($profileBio)): ?>
                                <div class="listed-bio"><?= nl2br(detailsEsc($profileBio)) ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="details-description">Lister information is currently unavailable.</p>
                        <?php endif; ?>

                        <h2 class="inquiry-title">Send an Inquiry</h2>

                        <?php if ($inquiryMessage): ?>
                            <div class="inquiry-message <?= $inquirySuccess ? 'inquiry-success' : 'inquiry-error' ?>">
                                <?= detailsEsc($inquiryMessage) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="inquiry-form">
                            <textarea name="message" placeholder="I am interested in this property. Please share more details..." required></textarea>
                            <button type="submit" name="send_inquiry" class="inquiry-btn">
                                <i class="fa-solid fa-paper-plane"></i> Send Inquiry
                            </button>
                            <?php if (empty($_SESSION['user_id'])): ?>
                                <div class="inquiry-note">You need to be logged in to send an inquiry.</div>
                            <?php else: ?>
                                <div class="inquiry-note">Your inquiry will be sent to the property listing account.</div>
                            <?php endif; ?>
                        </form>

                        <!-- BOOK A VISIT BUTTON -->

                        <a
                            href="../appointment.php?id=<?= (int)$property['property_id'] ?>"
                            class="book-visit-btn">

                            <i class="fa-solid fa-calendar-check"></i>
                            Book a Visit

                        </a>

                        <!-- WISHLIST BUTTON -->

                        <form method="POST" action="../wishlist.php" class="wishlist-detail-form">

                            <input
                                type="hidden"
                                name="action"
                                value="<?= $isWishlisted ? 'remove' : 'add' ?>"
                            >

                            <input
                                type="hidden"
                                name="property_id"
                                value="<?= (int)$property['property_id'] ?>"
                            >

                            <input
                                type="hidden"
                                name="redirect"
                                value="properties/details.php?id=<?= (int)$property['property_id'] ?>"
                            >

                            <button type="submit" class="wishlist-detail-btn">

                                <i class="fa-<?= $isWishlisted ? 'solid' : 'regular' ?> fa-heart"></i>

                                <?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>

                            </button>

</form>

                    </div>
                </aside>
            </div>

            <?php if ($otherProperties): ?>
                <section class="other-section">
                    <h2>Other <?= detailsEsc($property['subcategory_name'] ?? 'Properties') ?></h2>
                    <div class="other-grid">
                        <?php foreach ($otherProperties as $other): ?>
                            <article class="other-card">
                                <img src="<?= detailsEsc(detailsPropertyImage($other['image_path'])) ?>" alt="<?= detailsEsc($other['title']) ?>">
                                <div class="other-content">
                                    <h3><?= detailsEsc($other['title']) ?></h3>
                                    <div class="other-price"><?= detailsEsc(detailsFormatPrice($other['price'], $other['listing_type'])) ?></div>
                                    <div class="other-meta">
                                        <?= detailsEsc($other['city']) ?>, <?= detailsEsc($other['state']) ?><br>
                                        <?= number_format((int)$other['area_sqft']) ?> sq.ft.
                                        <?php if ($other['bedrooms'] !== null): ?> · <?= (int)$other['bedrooms'] ?> BHK<?php endif; ?>
                                    </div>
                                    <a class="other-btn" href="details.php?id=<?= (int)$other['property_id'] ?>">View Details</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </section>

    <script>
        document.querySelectorAll('.details-thumb').forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('mainPropertyImage').src = this.dataset.image;
                document.querySelectorAll('.details-thumb').forEach(function(item) {
                    item.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>