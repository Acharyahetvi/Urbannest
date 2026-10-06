<?php

session_start();

require_once __DIR__ . '/../db_connection.php';


/* ================================
   CHECK LOGIN
================================ */

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}


/* ================================
   USER DATA
================================ */

$user_id = (int) $_SESSION['user_id'];

$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_role = $_SESSION['user_role'] ?? 2;

$userPhone = '';
$userRoleName = '';
$userDetailsStmt = mysqli_prepare(
    $conn,
    "SELECT u.full_name, u.email, u.phoneno, u.role_id, r.role_name
     FROM users u
     LEFT JOIN roles r ON u.role_id = r.role_id
     WHERE u.user_id = ?
     LIMIT 1"
);

if ($userDetailsStmt) {
    mysqli_stmt_bind_param($userDetailsStmt, 'i', $user_id);
    mysqli_stmt_execute($userDetailsStmt);
    $userDetailsResult = mysqli_stmt_get_result($userDetailsStmt);
    $userDetails = mysqli_fetch_assoc($userDetailsResult);

    if ($userDetails) {
        $user_name = $userDetails['full_name'];
        $user_email = $userDetails['email'];
        $userPhone = $userDetails['phoneno'] ?? '';
        $user_role = $userDetails['role_id'];
        $userRoleName = $userDetails['role_name'] ?? '';
    }

    mysqli_stmt_close($userDetailsStmt);
}

$nameParts = preg_split('/\s+/', trim($user_name), -1, PREG_SPLIT_NO_EMPTY);
$profileUserInitials = '';

foreach (array_slice($nameParts ?: [], 0, 2) as $namePart) {
    $profileUserInitials .= strtoupper(substr($namePart, 0, 1));
}

$roleLabels = [
    1 => 'Admin',
    2 => 'Buyer',
    3 => 'Agent',
    4 => 'Seller',
    5 => 'Owner'
];
$profileUserRoleLabel = $userRoleName !== ''
    ? $userRoleName
    : ($roleLabels[(int)$user_role] ?? 'UrbanNest Member');

$wishlistSql = "
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
$wishlistStmt = mysqli_prepare($conn, $wishlistSql);
$wishlistResult = false;

if ($wishlistStmt) {
    mysqli_stmt_bind_param($wishlistStmt, 'i', $user_id);
    mysqli_stmt_execute($wishlistStmt);
    $wishlistResult = mysqli_stmt_get_result($wishlistStmt);
}


/* ================================
   PAGE
================================ */

$pageTitle = "My Profile - UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>

<style>
    .profile-page {
        padding: 38px 18px 52px;
        background: #f6f3ec;
    }

    .profile-wrap {
        width: min(100%, 1180px);
        margin: 0 auto;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: minmax(270px, 0.33fr) minmax(0, 0.67fr);
        gap: 22px;
        align-items: start;
    }

    .profile-card,
    .wishlist-panel {
        background: #ffffff;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.05);
    }

    .profile-card {
        padding: 25px 22px;
        text-align: center;
        border-top: 3px solid #d4af37;
    }

    .profile-avatar {
        display: grid;
        width: 78px;
        height: 78px;
        margin: 0 auto 14px;
        place-items: center;
        border: 1px solid rgba(212, 175, 55, 0.5);
        border-radius: 50%;
        background: #0b1f3a;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 27px;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .profile-card h1 {
        margin: 0 0 5px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 25px;
        font-weight: 600;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .profile-role {
        margin-bottom: 17px;
        color: #a88727;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
    }

    .profile-info-item {
        min-width: 0;
        padding: 12px 0;
        border-bottom: 1px solid #edf0f4;
        color: #26364d;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .profile-info-label {
        display: block;
        margin-bottom: 4px;
        color: #7a8494;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .wishlist-panel {
        padding: 22px;
        border-top: 3px solid #d4af37;
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
        margin: 10px 0 18px;
        background: #d4af37;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .wishlist-property {
        display: flex;
        min-width: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e4ddd0;
        border-radius: 7px;
        background: #ffffff;
        color: inherit;
        text-decoration: none;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .wishlist-property:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 16px rgba(11, 31, 58, 0.1);
    }

    .wishlist-property img {
        display: block;
        width: 100%;
        height: 145px;
        object-fit: cover;
    }

    .wishlist-property-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 13px 14px 14px;
    }

    .wishlist-property h3 {
        display: -webkit-box;
        min-height: 2.7em;
        margin: 0 0 7px;
        overflow: hidden;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.35;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .wishlist-property-price {
        margin-bottom: 5px;
        color: #9a791f;
        font-size: 16px;
        font-weight: 700;
    }

    .wishlist-property-location {
        margin-bottom: 12px;
        color: #687386;
        font-size: 12px;
    }

    .wishlist-view-link {
        display: block;
        margin-top: auto;
        padding: 8px 10px;
        border-radius: 5px;
        background: #0b1f3a;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.18s ease, color 0.18s ease;
    }

    .wishlist-view-link:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .wishlist-empty {
        padding: 28px 16px;
        color: #687386;
        text-align: center;
    }

    .wishlist-empty i {
        margin-bottom: 10px;
        color: #b08d22;
        font-size: 27px;
    }

    .wishlist-empty h3 {
        margin: 0 0 6px;
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 19px;
    }

    .wishlist-empty p {
        margin: 0 0 15px;
        font-size: 13px;
    }

    .wishlist-browse {
        display: inline-block;
        padding: 9px 15px;
        border-radius: 5px;
        background: #0b1f3a;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .wishlist-browse:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .wishlist-error {
        margin: 0;
        color: #8a4040;
        font-size: 14px;
    }

    @media (max-width: 900px) {
        .profile-page {
            padding: 30px 16px 40px;
        }

        .profile-layout {
            grid-template-columns: 1fr;
        }

        .profile-avatar {
            width: 68px;
            height: 68px;
            font-size: 23px;
        }

        .wishlist-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .wishlist-panel {
            padding: 18px;
        }

        .wishlist-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="profile-page">
    <div class="profile-wrap">
        <div class="profile-layout">
            <section class="profile-card" aria-labelledby="profile-name">
                <div class="profile-avatar" aria-hidden="true">
                    <?= htmlspecialchars($profileUserInitials !== '' ? $profileUserInitials : 'U') ?>
                </div>
                <h1 id="profile-name"><?= htmlspecialchars($user_name) ?></h1>
                <div class="profile-role"><?= htmlspecialchars($profileUserRoleLabel) ?></div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Email</span>
                    <?= htmlspecialchars($user_email) ?>
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

                <?php if ($wishlistResult === false): ?>
                    <p class="wishlist-error">Unable to load your wishlist right now.</p>
                <?php elseif (mysqli_num_rows($wishlistResult) > 0): ?>
                    <div class="wishlist-grid">
                        <?php while ($property = mysqli_fetch_assoc($wishlistResult)): ?>
                            <?php
                            $propertyImage = !empty($property['image_path'])
                                ? '../admin/' . ltrim($property['image_path'], '/')
                                : '../admin/assets/images/no-property.png';
                            $propertyUrl = '../properties/details.php?id=' . (int)$property['property_id'];
                            ?>
                            <a
                                class="wishlist-property"
                                href="<?= htmlspecialchars($propertyUrl) ?>"
                                aria-label="View <?= htmlspecialchars($property['title']) ?>"
                            >
                                <img
                                    src="<?= htmlspecialchars($propertyImage) ?>"
                                    alt="<?= htmlspecialchars($property['title']) ?>"
                                >
                                <div class="wishlist-property-content">
                                    <h3><?= htmlspecialchars($property['title']) ?></h3>
                                    <div class="wishlist-property-price">
                                        ₹<?= number_format((float)$property['price']) ?>
                                    </div>
                                    <div class="wishlist-property-location">
                                        <?= htmlspecialchars($property['city']) ?>,
                                        <?= htmlspecialchars($property['state']) ?>
                                    </div>
                                    <span class="wishlist-view-link">
                                        View Details
                                    </span>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="wishlist-empty">
                        <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        <h3>Your wishlist is empty.</h3>
                        <p>Explore properties and add your favourites here.</p>
                        <a class="wishlist-browse" href="../buy.php">Browse Properties</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>


<?php

require_once __DIR__ . '/../includes/footer.php';

?>
<?php
if ($wishlistStmt) {
    mysqli_stmt_close($wishlistStmt);
}
?>