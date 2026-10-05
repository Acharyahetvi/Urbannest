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


/* ================================
   PAGE
================================ */

$pageTitle = "My Profile - UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>

<div>

    <h2>My Profile</h2>

    <p>
        <strong>Name:</strong>
        <?= htmlspecialchars($user_name) ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?= htmlspecialchars($user_email) ?>
    </p>


    <!-- SELL PROPERTY -->

    <a href="/urbannest/add-property.php">
        Sell Property
    </a>


    <!-- WISHLIST -->

    <a href="/urbannest/wishlist.php">
        Wishlist
    </a>


    <!-- LOGOUT -->

    <a href="logout.php">
        Logout
    </a>

</div>


<?php

require_once __DIR__ . '/../includes/footer.php';

?>