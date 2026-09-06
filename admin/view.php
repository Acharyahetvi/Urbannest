<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../db_connection.php";


// ================= TOTAL USERS =================

$sql = "SELECT COUNT(*) AS total FROM users";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Users Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$total_users = $data['total'];


// ================= TOTAL PROPERTIES =================

$sql = "SELECT COUNT(*) AS total FROM properties";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Properties Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$total_properties = $data['total'];


// ================= TOTAL CATEGORIES =================

$sql = "SELECT COUNT(*) AS total FROM categories";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Categories Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$total_categories = $data['total'];


// ================= TOTAL SUB CATEGORIES =================

$sql = "SELECT COUNT(*) AS total FROM sub_categories";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Sub Categories Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$total_subcategories = $data['total'];


// ================= TOTAL AMENITIES =================

$sql = "SELECT COUNT(*) AS total FROM amenities";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Amenities Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$total_amenities = $data['total'];

?>


<!DOCTYPE html>
<html>

<head>

    <title>UrbanNest Admin Dashboard</title>

    <link rel="stylesheet" href="assets/css/view.css">

</head>


<body>


    <!-- ================= HEADER ================= -->

    <header class="admin-header">

        <!-- Logo -->

        <div class="header-logo">
            UrbanNest
        </div>


        <!-- Search Bar -->

        <div class="search-box">

            <input type="text"
                placeholder="Search properties, users...">

        </div>


        <!-- Profile -->

        <div class="profile-section">

            <div class="profile-icon">
                AU
            </div>


            <!-- Profile Dropdown -->

            <div class="profile-menu">

                <h4>Admin User</h4>

                <a href="profile.php">
                    My Profile
                </a>

                <a href="settings.php">
                    Account Settings
                </a>

                <a href="logout.php">
                    Logout
                </a>

            </div>

        </div>

    </header>



    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar">

        <h2>UrbanNest</h2>

        <p>Admin Panel</p>


        <a href="view.php">
            Home
        </a>


        <a href="users/view.php">
            Users
        </a>


        <a href="property/view.php">
            Properties
        </a>


        <a href="category/view.php">
            Categories
        </a>


        <a href="sub-category/view.php">
            Sub Categories
        </a>


        <a href="amenities/view.php">
            Amenities
        </a>


        <a href="logout.php">
            Logout
        </a>

    </div>



    <!-- ================= MAIN CONTENT ================= -->

    <div class="main">


        <h1>Admin Dashboard</h1>


        <p class="welcome">
            Welcome to UrbanNest Admin Panel
        </p>


        <!-- Dashboard Cards -->

        <div class="cards">


            <!-- USERS -->

            <div class="card">

                <h3>Total Users</h3>

                <h2>
                    <?php echo $total_users; ?>
                </h2>

                <a href="users/view.php">
                    View Users
                </a>

            </div>



            <!-- PROPERTIES -->

            <div class="card">

                <h3>Total Properties</h3>

                <h2>
                    <?php echo $total_properties; ?>
                </h2>

                <a href="property/view.php">
                    View Properties
                </a>

            </div>



            <!-- CATEGORIES -->

            <div class="card">

                <h3>Total Categories</h3>

                <h2>
                    <?php echo $total_categories; ?>
                </h2>

                <a href="category/view.php">
                    View Categories
                </a>

            </div>



            <!-- SUB CATEGORIES -->

            <div class="card">

                <h3>Total Sub Categories</h3>

                <h2>
                    <?php echo $total_subcategories; ?>
                </h2>

                <a href="sub-category/view.php">
                    View Sub Categories
                </a>

            </div>



            <!-- AMENITIES -->

            <div class="card">

                <h3>Total Amenities</h3>

                <h2>
                    <?php echo $total_amenities; ?>
                </h2>

                <a href="amenities/view.php">
                    View Amenities
                </a>

            </div>


        </div>

    </div>


</body>

</html>