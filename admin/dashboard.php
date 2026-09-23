<?php
/* DATABASE CONNECTION */
require_once __DIR__ . "/../db_connection.php";

$con = isset($con) ? $con : (isset($conn) ? $conn : null);

if (!$con) {
    die("Database connection failed.");
}

/* GET TABLE COUNT */
function getCount($con, $table)
{
    $allowedTables = [
        "users",
        "properties",
        "categories",
        "sub_categories",
        "amenities"
    ];

    if (!in_array($table, $allowedTables, true)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

/* DASHBOARD COUNTS */
$totalUsers       = getCount($con, "users");
$totalProperties  = getCount($con, "properties");
$totalCategories  = getCount($con, "categories");
$totalSubCategories = getCount($con, "sub_categories");
$totalAmenities   = getCount($con, "amenities");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - UrbanNest</title>

    <link rel="stylesheet" href="/urbannest/admin/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/admin.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- SIDEBAR -->
    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <!-- MAIN WRAPPER -->
    <div class="un-main-wrapper">

        <!-- HEADER -->
        <?php include __DIR__ . "/includes/header.php"; ?>

        <!-- DASHBOARD CONTENT -->

        <!-- DASHBOARD CONTENT -->
        <main class="un-dashboard-content">

            <!-- DASHBOARD HEADING -->
            <div class="un-dashboard-heading">
                <h2>Dashboard</h2>
                <p>Welcome to UrbanNest Admin Panel</p>
            </div>

            <!-- DASHBOARD CARDS -->
            <div class="un-dashboard-cards">

                <!-- USERS -->
                <div class="un-stat-card">
                    <div class="un-stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h4>Total Users</h4>
                    <h2><?php echo $totalUsers; ?></h2>

                    <a href="/urbannest/admin/users/view.php">
                        View Users →
                    </a>
                </div>

                <!-- PROPERTIES -->
                <div class="un-stat-card">
                    <div class="un-stat-icon">
                        <i class="fa-solid fa-house"></i>
                    </div>

                    <h4>Total Properties</h4>
                    <h2><?php echo $totalProperties; ?></h2>

                    <a href="/urbannest/admin/property/view.php">
                        View Properties →
                    </a>
                </div>

                <!-- CATEGORIES -->
                <div class="un-stat-card">
                    <div class="un-stat-icon">
                        <i class="fa-solid fa-list"></i>
                    </div>

                    <h4>Total Categories</h4>
                    <h2><?php echo $totalCategories; ?></h2>

                    <a href="/urbannest/admin/category/view.php">
                        View Categories →
                    </a>
                </div>

                <!-- SUB CATEGORIES -->
                <div class="un-stat-card">
                    <div class="un-stat-icon">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>

                    <h4>Total Sub Categories</h4>
                    <h2><?php echo $totalSubCategories; ?></h2>

                    <a href="/urbannest/admin/sub-category/view.php">
                        View Sub Categories →
                    </a>
                </div>

                <!-- AMENITIES -->
                <div class="un-stat-card">
                    <div class="un-stat-icon">
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <h4>Total Amenities</h4>
                    <h2><?php echo $totalAmenities; ?></h2>

                    <a href="/urbannest/admin/amenities/view.php">
                        View Amenities →
                    </a>
                </div>

            </div>

            <!-- PROJECT OVERVIEW -->
            <div class="un-dashboard-box">

                <div class="un-box-header">
                    <h3>
                        <i class="fa-solid fa-house"></i>
                        Project Overview
                    </h3>
                </div>

                <div class="un-overview-content">

                    <div class="un-property-circle">
                        <i class="fa-solid fa-house"></i>

                        <strong><?php echo $totalProperties; ?></strong>

                        <span>Total Properties</span>
                    </div>

                    <div class="un-overview-list">

                        <div class="un-overview-item">
                            <span class="un-overview-name">
                                <i class="fa-solid fa-users"></i>
                                Total Users
                            </span>
                            <strong><?php echo $totalUsers; ?></strong>
                        </div>

                        <div class="un-overview-item">
                            <span class="un-overview-name">
                                <i class="fa-solid fa-list"></i>
                                Categories
                            </span>
                            <strong><?php echo $totalCategories; ?></strong>
                        </div>

                        <div class="un-overview-item">
                            <span class="un-overview-name">
                                <i class="fa-solid fa-layer-group"></i>
                                Sub Categories
                            </span>
                            <strong><?php echo $totalSubCategories; ?></strong>
                        </div>

                        <div class="un-overview-item">
                            <span class="un-overview-name">
                                <i class="fa-solid fa-star"></i>
                                Amenities
                            </span>
                            <strong><?php echo $totalAmenities; ?></strong>
                        </div>

                    </div>

                </div>
            </div>

        </main>

        <!-- FOOTER -->
        <?php include __DIR__ . "/includes/footer.php"; ?>

    </div>

    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>