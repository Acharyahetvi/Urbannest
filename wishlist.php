<?php
session_start();
include "db_connection.php";
$user_id = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
$userPhone = '';
$userRole = (int)($_SESSION['user_role'] ?? 0);
$userRoleName = '';

if ($user_id > 0) {
    $userDetailsStmt = mysqli_prepare(
        $conn,
        "SELECT u.full_name, u.email, u.phoneno, u.role_id, r.role_name
         FROM users u
         LEFT JOIN roles r ON u.role_id = r.role_id
         WHERE u.user_id = ?
         LIMIT 1"
    );

    if ($userDetailsStmt) {
        mysqli_stmt_bind_param($userDetailsStmt, "i", $user_id);
        mysqli_stmt_execute($userDetailsStmt);
        $userDetailsResult = mysqli_stmt_get_result($userDetailsStmt);
        $userDetails = mysqli_fetch_assoc($userDetailsResult);

        if ($userDetails) {
            $userName = $userDetails['full_name'];
            $userEmail = $userDetails['email'];
            $userPhone = $userDetails['phoneno'] ?? '';
            $userRole = (int)$userDetails['role_id'];
            $userRoleName = $userDetails['role_name'] ?? '';
        }

        mysqli_stmt_close($userDetailsStmt);
    }
}

$roleLabels = [
    1 => 'Admin',
    2 => 'Buyer',
    3 => 'Agent',
    4 => 'Seller',
    5 => 'Owner'
];
$userRoleLabel = $userRoleName !== ''
    ? $userRoleName
    : ($roleLabels[$userRole] ?? 'UrbanNest Member');
$nameParts = preg_split('/\s+/', trim($userName), -1, PREG_SPLIT_NO_EMPTY);
$userInitials = '';

foreach (array_slice($nameParts ?: [], 0, 2) as $namePart) {
    $userInitials .= strtoupper(substr($namePart, 0, 1));
}

/* Add / Remove from wishlist */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($user_id <= 0) {
        header("Location: auth/login.php");
        exit;
    }

    $action = $_POST['action'] ?? '';
    $property_id = (int)($_POST['property_id'] ?? 0);

    if ($property_id > 0) {

        if ($action === 'add') {

            /* Prevent duplicate wishlist entries */
            $check = mysqli_prepare(
                $conn,
                "SELECT wishlist_id
                 FROM wishlist
                 WHERE user_id = ? AND property_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $check,
                "ii",
                $user_id,
                $property_id
            );

            mysqli_stmt_execute($check);
            $checkResult = mysqli_stmt_get_result($check);

            if (mysqli_num_rows($checkResult) === 0) {

                $insert = mysqli_prepare(
                    $conn,
                    "INSERT INTO wishlist
                    (user_id, property_id, created_at)
                    VALUES (?, ?, NOW())"
                );

                mysqli_stmt_bind_param(
                    $insert,
                    "ii",
                    $user_id,
                    $property_id
                );

                mysqli_stmt_execute($insert);
                mysqli_stmt_close($insert);
            }

            mysqli_stmt_close($check);

        } elseif ($action === 'remove') {

            $delete = mysqli_prepare(
                $conn,
                "DELETE FROM wishlist
                 WHERE user_id = ? AND property_id = ?"
            );

            mysqli_stmt_bind_param(
                $delete,
                "ii",
                $user_id,
                $property_id
            );

            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);
        }
    }

    /* Return to the page that sent the request */
    $redirect = $_POST['redirect'] ?? 'wishlist.php';

    header("Location: " . $redirect);
    exit;
}


/* Old GET remove support */
if (isset($_GET['remove'])) {

    $property_id = (int)$_GET['remove'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM wishlist
         WHERE user_id = ? AND property_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $user_id,
        $property_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: wishlist.php");
    exit;
}


/* Get wishlist properties */
$sql = "
    SELECT
        w.property_id,
        p.title,
        p.price,
        p.city,
        p.state,
        p.listing_type,
        pi.image_path
    FROM wishlist w
    INNER JOIN properties p
        ON w.property_id = p.property_id
    LEFT JOIN property_images pi
        ON p.property_id = pi.property_id
        AND pi.is_main = 1
    WHERE w.user_id = ?
      AND p.approval_status = 'Approved'
      AND p.status = 'Active'
    ORDER BY w.wishlist_id DESC
";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$pageTitle = "My Wishlist - UrbanNest";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    html,
    body {
        overflow-x: clip;
    }

    .wishlist-page {
        padding: 34px 18px 46px;
        background: #f6f3ec;
    }

    .wishlist-wrap {
        width: min(100%, 1180px);
        margin: 0 auto;
    }

    .wishlist-layout {
        display: grid;
        grid-template-columns: minmax(250px, 0.32fr) minmax(0, 0.68fr);
        gap: 22px;
        align-items: start;
    }

    .profile-card,
    .wishlist-panel {
        min-width: 0;
        background: #fff;
        border: 1px solid #e4ddd0;
        border-top: 3px solid #d4af37;
        border-radius: 8px;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.05);
    }

    .profile-card {
        padding: 22px 20px;
        text-align: center;
    }

    .profile-avatar {
        display: grid;
        width: 68px;
        height: 68px;
        margin: 0 auto 12px;
        place-items: center;
        border: 1px solid rgba(212, 175, 55, 0.5);
        border-radius: 50%;
        background: #0b1f3a;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 23px;
        font-weight: 600;
    }

    .profile-card h1 {
        margin: 0 0 5px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 23px;
        font-weight: 600;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .profile-role {
        margin-bottom: 12px;
        color: #a88727;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
    }

    .profile-info-item {
        padding: 10px 0;
        border-bottom: 1px solid #edf0f4;
        color: #26364d;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .profile-info-label {
        display: block;
        margin-bottom: 3px;
        color: #7a8494;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .wishlist-panel {
        padding: 22px;
    }

    .wishlist-panel h2 {
        margin: 0;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 23px;
        font-weight: 600;
    }

    .wishlist-divider {
        width: 44px;
        height: 2px;
        margin: 9px 0 16px;
        background: #d4af37;
    }

    .wishlist-list {
        display: grid;
        gap: 12px;
    }

    .wishlist-card {
        display: flex;
        min-width: 0;
        min-height: 132px;
        overflow: hidden;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(11, 31, 58, 0.05);
    }

    .wishlist-card img {
        display: block;
        width: 158px;
        min-height: 132px;
        flex: 0 0 158px;
        object-fit: cover;
    }

    .wishlist-card-content {
        display: flex;
        min-width: 0;
        flex: 1;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
    }

    .wishlist-property-details {
        min-width: 0;
    }

    .wishlist-card h3 {
        margin: 0 0 5px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 17px;
        font-weight: 600;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .wishlist-location {
        margin-bottom: 8px;
        color: #687386;
        font-size: 12px;
    }

    .wishlist-price {
        color: #9a791f;
        font-size: 16px;
        font-weight: 700;
        white-space: nowrap;
    }

    .wishlist-actions {
        display: flex;
        flex: 0 0 auto;
        flex-direction: column;
        align-items: stretch;
        gap: 7px;
    }

    .wishlist-actions a {
        display: inline-block;
        padding: 8px 11px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        white-space: nowrap;
    }

    .view-btn {
        background: #0b1f3a;
        color: #fff;
    }

    .view-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .remove-btn {
        background: #f3f4f6;
        color: #9b3434;
    }

    .remove-btn:hover {
        background: #ece1e1;
        color: #822727;
    }

    .empty-wishlist {
        padding: 34px 18px;
        border: 1px solid #e9e4d8;
        border-radius: 8px;
        background: #fff;
        color: #687386;
        text-align: center;
    }

    .empty-wishlist i {
        display: block;
        margin-bottom: 10px;
        color: #b08d22;
        font-size: 30px;
    }

    .empty-wishlist h3 {
        margin: 0 0 6px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 19px;
    }

    .empty-wishlist p {
        margin: 0 0 15px;
        font-size: 13px;
    }

    .wishlist-browse {
        display: inline-block;
        padding: 9px 15px;
        border-radius: 5px;
        background: #0b1f3a;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .wishlist-browse:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    @media (max-width: 900px) {
        .wishlist-page {
            padding: 28px 16px 38px;
        }

        .wishlist-layout {
            grid-template-columns: 1fr;
        }

        .wishlist-card img {
            width: 145px;
            flex-basis: 145px;
        }
    }

    @media (max-width: 520px) {
        .wishlist-panel {
            padding: 17px;
        }

        .wishlist-card img {
            width: 100px;
            min-height: 118px;
            flex-basis: 100px;
        }

        .wishlist-card-content {
            align-items: flex-start;
            gap: 8px;
            padding: 11px;
        }

        .wishlist-card h3 {
            font-size: 15px;
        }

        .wishlist-price {
            font-size: 14px;
        }

        .wishlist-actions a {
            padding: 7px 8px;
            font-size: 11px;
        }
    }
</style>

<main class="wishlist-page">
    <div class="wishlist-wrap">
        <div class="wishlist-layout">
            <section class="profile-card" aria-labelledby="profile-name">
                <div class="profile-avatar" aria-hidden="true">
                    <?= htmlspecialchars($userInitials !== '' ? $userInitials : 'U') ?>
                </div>
                <h1 id="profile-name"><?= htmlspecialchars($userName) ?></h1>
                <div class="profile-role"><?= htmlspecialchars($userRoleLabel) ?></div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Email</span>
                    <?= htmlspecialchars($userEmail) ?>
                </div>
                <?php if ($userPhone !== ''): ?>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Phone</span>
                        <?= htmlspecialchars($userPhone) ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="wishlist-panel" aria-labelledby="wishlist-title">
                <h2 id="wishlist-title">My Wishlist</h2>
                <div class="wishlist-divider"></div>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="wishlist-list">
                        <?php while ($property = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $image = !empty($property['image_path'])
                                ? 'admin/' . ltrim($property['image_path'], '/')
                                : 'admin/assets/images/no-property.png';
                            ?>
                            <article class="wishlist-card">
                                <img
                                    src="<?= htmlspecialchars($image) ?>"
                                    alt="<?= htmlspecialchars($property['title']) ?>"
                                >
                                <div class="wishlist-card-content">
                                    <div class="wishlist-property-details">
                                        <h3><?= htmlspecialchars($property['title']) ?></h3>
                                        <div class="wishlist-location">
                                            <?= htmlspecialchars($property['city']) ?>,
                                            <?= htmlspecialchars($property['state']) ?>
                                        </div>
                                        <div class="wishlist-price">
                                            ₹<?= number_format((float)$property['price']) ?>
                                        </div>
                                    </div>
                                    <div class="wishlist-actions">
                                        <a
                                            class="view-btn"
                                            href="properties/details.php?id=<?= (int)$property['property_id'] ?>"
                                        >View Details</a>
                                        <a
                                            class="remove-btn"
                                            href="wishlist.php?remove=<?= (int)$property['property_id'] ?>"
                                            onclick="return confirm('Remove this property from wishlist?');"
                                        >Remove</a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-wishlist">
                        <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        <h3>Your wishlist is empty.</h3>
                        <p>Explore properties and add your favourites.</p>
                        <a class="wishlist-browse" href="buy.php">Browse Properties</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
mysqli_stmt_close($stmt);
?>