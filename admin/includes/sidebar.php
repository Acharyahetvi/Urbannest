<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
    <!-- ============================================================
       SIDEBAR (Desktop / Tablet landscape — fixed, always visible)
        ============================================================= -->
<aside class="un-sidebar d-none d-lg-flex flex-column">

    <!-- Brand / Logo -->
    <div class="un-sidebar-brand">
        <!-- <span class="un-logo-mark">UN</span>-->
        <img src="./assets/images/logo.png" class="un-logo-mark">
        <div class="un-logo-text">
            <span class="un-logo-title">UrbanNest</span>
            <span class="un-logo-subtitle">Admin Panel</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="un-sidebar-nav">
        <ul class="un-nav-list">
            <li class="un-nav-item active">
                <a href="#" class="un-nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
             <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-building"></i>
                            <span>Properties</span>
                        </a>
                    </li>
                    <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-sitemap"></i>
                            <span>Sub Categories</span>
                        </a>
                    </li> 
            <li class="un-nav-item">
                <a href="user.php" class="un-nav-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-swimming-pool"></i>
                            <span>Amenities</span>
                        </a>
                    </li>
                    <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-star"></i>
                            <span>Reviews</span>
                        </a>
                    </li>
                    <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-envelope-open-text"></i>
                            <span>Inquiries</span>
                        </a>
                    </li>
                    <li class="un-nav-item">
                        <a href="#" class="un-nav-link">
                            <i class="fa-solid fa-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li> 
        </ul>
    </nav>

    <!-- Logout (pinned to bottom) -->
    <div class="un-sidebar-footer">
        <a href="logout.php" class="un-nav-link un-logout-link">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<!-- ============================================================
       SIDEBAR (Mobile / Tablet portrait — Bootstrap Offcanvas)
       Toggled by the hamburger button in the top navbar.
     ============================================================= -->
<div class="offcanvas offcanvas-start un-sidebar-offcanvas d-lg-none" tabindex="-1" id="unSidebarOffcanvas" aria-labelledby="unSidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <div class="un-sidebar-brand un-sidebar-brand--offcanvas">
            <span class="un-logo-mark">UN</span>
            <div class="un-logo-text">
                <span class="un-logo-title">UrbanNest</span>
                <span class="un-logo-subtitle">Admin Panel</span>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <nav class="un-sidebar-nav">
            <ul class="un-nav-list">
                <li class="un-nav-item active">
                    <a href="#" class="un-nav-link">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-building"></i>
                                <span>Properties</span>
                            </a>
                        </li>
                        <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-layer-group"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-sitemap"></i>
                                <span>Sub Categories</span>
                            </a>
                        </li> 
                <li class="un-nav-item">
                    <a href="#" class="un-nav-link">
                        <i class="fa-solid fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                 <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-swimming-pool"></i>
                                <span>Amenities</span>
                            </a>
                        </li>
                        <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-star"></i>
                                <span>Reviews</span>
                            </a>
                        </li>
                        <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                <span>Inquiries</span>
                            </a>
                        </li>
                        <li class="un-nav-item">
                            <a href="#" class="un-nav-link">
                                <i class="fa-solid fa-gear"></i>
                                <span>Settings</span>
                            </a>
                        </li> 
            </ul>
        </nav>
        <div class="un-sidebar-footer mt-auto">
            <a href="./logout.php" class="un-nav-link un-logout-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>
</body>
</html>
