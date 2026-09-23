<?php
// Current page and folder
$current_page = basename($_SERVER['PHP_SELF']);
$current_folder = basename(dirname($_SERVER['PHP_SELF']));

// Active menu conditions
$dashboard_active = ($current_page === "dashboard.php");

$users_active = (
    $current_folder === "users" &&
    $current_page === "view.php"
);

$property_active = (
    $current_folder === "property" &&
    $current_page === "view.php"
);

$category_active = (
    $current_folder === "category" &&
    $current_page === "view.php"
);

$subcategory_active = (
    $current_folder === "sub-category" &&
    $current_page === "view.php"
);

$amenity_active = (
    $current_folder === "amenities" &&
    $current_page === "view.php"
);
?>

<!-- SIDEBAR -->
<aside class="un-sidebar">

    <!-- BRAND -->
    <div class="un-sidebar-brand">

        <div class="un-brand-icon">
            <img src="/urbannest/assets/images/img1.png" alt="UrbanNest Logo">
        </div>

        <div class="un-brand-text">
            <span class="un-urban">Urban</span>
            <span class="un-nest">Nest</span>
        </div>

    </div>

    <!-- SIDEBAR TITLE -->
    <div class="un-sidebar-title">
        Admin Panel
    </div>

    <!-- NAVIGATION -->
    <nav class="un-sidebar-nav">

        <ul class="un-nav-list">

            <!-- DASHBOARD -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/dashboard.php"
                    class="un-nav-link <?= $dashboard_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- USERS -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/users/view.php"
                    class="un-nav-link <?= $users_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- PROPERTIES -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/property/view.php"
                    class="un-nav-link <?= $property_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Properties</span>
                </a>
            </li>

            <!-- CATEGORIES -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/category/view.php"
                    class="un-nav-link <?= $category_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list"></i>
                    <span>Categories</span>
                </a>
            </li>

            <!-- SUB CATEGORIES -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/sub-category/view.php"
                    class="un-nav-link <?= $subcategory_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Sub Categories</span>
                </a>
            </li>

            <!-- AMENITIES -->
            <li class="un-nav-item">
                <a
                    href="/urbannest/admin/amenities/view.php"
                    class="un-nav-link <?= $amenity_active ? 'active' : ''; ?>">
                    <i class="fa-solid fa-star"></i>
                    <span>Amenities</span>
                </a>
            </li>

        </ul>

    </nav>

    <!-- LOGOUT -->
    <div class="un-sidebar-bottom">

        <a
            href="/urbannest/admin/logout.php"
            class="un-nav-link un-logout-link">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>

    </div>

</aside>