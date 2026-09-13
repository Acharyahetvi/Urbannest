<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

/* Admin Login Check */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)
     {
    header("Location: login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UrbanNest Admin Dashboard</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">
</head>

<body>

    <div class="un-wrapper">

        <!-- ================= SIDEBAR ================= -->

        <aside class="un-sidebar">

            <!-- Logo -->
            <div class="un-sidebar-brand">

                <!-- <img src="/urbannest/assets/images/img1.png"
                    alt="UrbanNest Logo"
                    class="un-brand-image">-->

                <div class="un-logo-title">
                    UrbanNest
                </div>

                <div class="un-logo-subtitle">
                    ADMIN PANEL
                </div>

            </div>


            <!-- Menu -->
            <nav class="un-sidebar-nav">

                <a href="dashboard.php" class="un-nav-link active">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>

                <a href="#" class="un-nav-link">
                    <i class="fa-solid fa-building"></i>
                    Properties
                </a>

                <a href="/urbannest/admin/category/view.php" class="un-nav-link">
                    <i class="fa-solid fa-layer-group"></i>
                    Categories
                </a>

                <a href=" /urbannest/admin/sub-category/view.php" class="un-nav-link">
                    <i class="fa-solid fa-sitemap"></i>
                    Sub Categories
                </a>

                <a href="/urbannest/admin/users/view.php" class="un-nav-link">
                    <i class="fa-solid fa-users"></i>
                    Users
                </a>

                <a href="/urbannest/admin/amenities/view.php" class="un-nav-link">
                    <i class="fa-solid fa-star"></i>
                    Amenities
                </a>

                <a href="/urbannest/admin/inquiries/view.php" class="un-nav-link">
                    <i class="fa-solid fa-envelope"></i>
                    Inquiries
                </a>

            </nav>


            <!-- Logout -->
            <div class="un-sidebar-footer">

                <a href="logout.php" class="un-nav-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </div>

        </aside>


        <!-- ================= MAIN ================= -->

        <main class="un-main">

            <!-- Topbar -->
            <header class="un-topbar">

                <div>
                    <span class="un-eyebrow">
                        Overview
                    </span>

                    <h1 class="un-page-title">
                        Dashboard
                    </h1>
                </div>

                <div class="un-profile">

                    <div class="un-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <strong>
                            <?php echo htmlspecialchars($admin_name); ?>
                        </strong>

                        <small>
                            Admin
                        </small>
                    </div>

                </div>

            </header>


            <!-- Content -->
            <div class="un-content">

                <!-- Welcome -->
                <div class="un-welcome">

                    <span class="un-eyebrow">
                        Welcome Back
                    </span>

                    <h2>
                        Hello, <?php echo htmlspecialchars($admin_name); ?>!
                    </h2>

                    <p>
                        Manage your UrbanNest website from here.
                    </p>

                </div>


                <!-- ================= STAT CARDS ================= -->

                <div class="row g-4 mb-4">

                    <!-- Properties -->
                    <div class="col-md-3">

                        <div class="un-card un-stat-card">

                            <div class="un-stat-icon navy">
                                <i class="fa-solid fa-building"></i>
                            </div>

                            <div>
                                <div class="un-stat-value">
                                    248
                                </div>

                                <div class="un-stat-label">
                                    Properties
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- Users -->
                    <div class="col-md-3">

                        <div class="un-card un-stat-card">

                            <div class="un-stat-icon gold">
                                <i class="fa-solid fa-users"></i>
                            </div>

                            <div>
                                <div class="un-stat-value">
                                    1204
                                </div>

                                <div class="un-stat-label">
                                    Users
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- Categories -->
                    <div class="col-md-3">

                        <div class="un-card un-stat-card">

                            <div class="un-stat-icon navy">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>

                            <div>
                                <div class="un-stat-value">
                                    12
                                </div>

                                <div class="un-stat-label">
                                    Categories
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- Inquiries -->
                    <div class="col-md-3">

                        <div class="un-card un-stat-card">

                            <div class="un-stat-icon gold">
                                <i class="fa-solid fa-envelope"></i>
                            </div>

                            <div>
                                <div class="un-stat-value">
                                    18
                                </div>

                                <div class="un-stat-label">
                                    Inquiries
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= RECENT PROPERTIES ================= -->

                <div class="un-card un-panel">

                    <div class="un-panel-header">

                        <div>
                            <span class="un-eyebrow">
                                Latest Listings
                            </span>

                            <h2 class="un-panel-title">
                                Recent Properties
                            </h2>
                        </div>

                        <a href="#" class="un-link-btn">
                            View All
                        </a>

                    </div>


                    <div class="table-responsive">

                        <table class="table un-table">

                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Category</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>

                                    <td>
                                        <strong>
                                            Palm Grove Villa
                                        </strong>

                                        <br>

                                        <small>
                                            Alibaug, Maharashtra
                                        </small>
                                    </td>

                                    <td>
                                        Villa
                                    </td>

                                    <td>
                                        Rajesh Mehta
                                    </td>

                                    <td>
                                        <span class="un-status active">
                                            Active
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>
                                            Skyline Residency
                                        </strong>

                                        <br>

                                        <small>
                                            Mumbai
                                        </small>
                                    </td>

                                    <td>
                                        Apartment
                                    </td>

                                    <td>
                                        Ananya Kapoor
                                    </td>

                                    <td>
                                        <span class="un-status pending">
                                            Pending
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>
                                            Emerald Business Tower
                                        </strong>

                                        <br>

                                        <small>
                                            Noida
                                        </small>
                                    </td>

                                    <td>
                                        Commercial
                                    </td>

                                    <td>
                                        Vikram Suri
                                    </td>

                                    <td>
                                        <span class="un-status active">
                                            Active
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>
                                            Lakeview Bungalow
                                        </strong>

                                        <br>

                                        <small>
                                            Mumbai
                                        </small>
                                    </td>

                                    <td>
                                        Bungalow
                                    </td>

                                    <td>
                                        Arjun Deshmukh
                                    </td>

                                    <td>
                                        <span class="un-status pending">
                                            Pending
                                        </span>
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- ================= QUICK ACTIONS ================= -->

                <div class="un-card un-panel mt-4">

                    <div class="un-panel-header">

                        <div>
                            <span class="un-eyebrow">
                                Shortcuts
                            </span>

                            <h2 class="un-panel-title">
                                Quick Actions
                            </h2>
                        </div>

                    </div>


                    <div class="un-quick-actions">

                        <a href="#" class="un-action-btn">
                            <i class="fa-solid fa-plus"></i>
                            Add Property
                        </a>

                        <a href="/urbannest/admin/category/add.php"
                            class="un-action-btn">

                            <i class="fa-solid fa-layer-group"></i>
                            Add Category

                        </a>

                        <a href="#" class="un-action-btn">

                            <i class="fa-solid fa-user-plus"></i>
                            Add User

                        </a>

                    </div>

                </div>


                <!-- Footer -->

                <footer class="un-footer">
                    UrbanNest Admin Panel © 2026
                </footer>

            </div>

        </main>

    </div>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>