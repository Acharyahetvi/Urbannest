<?php

// ================= DATABASE CONNECTION =================

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

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UrbanNest Admin Dashboard</title>


    <!-- ================= GOOGLE FONT ================= -->

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap"
        rel="stylesheet">


    <!-- ================= CSS ================= -->


    <link rel="stylesheet" href="/urbannest/admin/assets/css/admin.css">


    <!-- ================= FONT AWESOME ================= -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>


<body>


    <!-- ================= HEADER ================= -->

    <header class="admin-header">


        <!-- ================= BRAND ================= -->

        <div class="brand">


            <!-- LOGO -->

            <div class="header-logo">

                <img
                    src="/urbannest/assets/images/img1.png"
                    alt="UrbanNest Logo">

            </div>


            <!-- WEBSITE NAME -->

            <div class="brand-name">

                <span class="urban">Urban</span><span class="nest">Nest</span>

            </div>


        </div>


        <!-- ================= SEARCH BOX ================= -->

        <div class="search-box">

            <input
                type="text"
                placeholder="Search...">

        </div>


        <!-- ================= PROFILE ================= -->

        <div class="profile-section">


            <!-- PROFILE ICON -->

            <div class="profile-icon">

                AU

            </div>


            <!-- PROFILE MENU -->

            <div class="profile-menu">


                <h4>Admin User</h4>


                <!-- MY PROFILE -->

                <a href="/urbannest/admin/profile.php">

                    <i class="fa-solid fa-user"></i>

                    My Profile

                </a>


                <!-- LOGOUT -->

                <a href="/urbannest/admin/logout.php">

                    <i class="fa-solid fa-arrow-left"></i>

                    Logout

                </a>


            </div>


        </div>


    </header>



    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar">


        <p>Admin Panel</p>


        <!-- DASHBOARD -->

        <a href="/urbannest/admin/view.php">

            <i class="fa-solid fa-chart-line"></i>

            Dashboard

        </a>


        <!-- USERS -->

        <a href="/urbannest/admin/users/view.php">

            <i class="fa-solid fa-users"></i>

            Users

        </a>


        <!-- CATEGORIES -->

        <a href="/urbannest/admin/category/view.php">

            <i class="fa-solid fa-list"></i>

            Categories

        </a>


        <!-- SUB CATEGORIES -->

        <a href="/urbannest/admin/sub-category/view.php">

            <i class="fa-solid fa-list-check"></i>

            Sub Categories

        </a>


        <!-- AMENITIES -->

        <a href="/urbannest/admin/amenities/view.php">

            <i class="fa-solid fa-star"></i>

            Amenities

        </a>


        <!-- PROPERTIES -->

        <a href="/urbannest/admin/property/view.php">

            <i class="fa-solid fa-house"></i>

            Properties

        </a>


        <!-- LOGOUT -->

        <a href="/urbannest/admin/logout.php">

            <i class="fa-solid fa-arrow-left"></i>

            Logout

        </a>


    </div>



    <!-- ================= MAIN CONTENT ================= -->

    <main class="main">


        <!-- PAGE TITLE -->

        <h1>Admin Dashboard</h1>


        <p class="welcome">

            Welcome to UrbanNest Admin Panel

        </p>


        <!-- ================= DASHBOARD CARDS ================= -->

        <div class="cards">


            <!-- ================= USERS ================= -->

            <div class="card">

                <div class="card-icon">

                    <i class="fa-solid fa-users"></i>

                </div>


                <h3>Total Users</h3>


                <h2>

                    <?php echo $total_users; ?>

                </h2>


                <a href="/urbannest/admin/users/view.php">

                    View Users

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <!-- ================= PROPERTIES ================= -->

            <div class="card">

                <div class="card-icon">

                    <i class="fa-solid fa-house"></i>

                </div>


                <h3>Total Properties</h3>


                <h2>

                    <?php echo $total_properties; ?>

                </h2>


                <a href="/urbannest/admin/property/view.php">

                    View Properties

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <!-- ================= CATEGORIES ================= -->

            <div class="card">

                <div class="card-icon">

                    <i class="fa-solid fa-list"></i>

                </div>


                <h3>Total Categories</h3>


                <h2>

                    <?php echo $total_categories; ?>

                </h2>


                <a href="/urbannest/admin/category/view.php">

                    View Categories

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <!-- ================= SUB CATEGORIES ================= -->

            <div class="card">

                <div class="card-icon">

                    <i class="fa-solid fa-list-check"></i>

                </div>


                <h3>Total Sub Categories</h3>


                <h2>

                    <?php echo $total_subcategories; ?>

                </h2>


                <a href="/urbannest/admin/sub-category/view.php">

                    View Sub Categories

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <!-- ================= AMENITIES ================= -->

            <div class="card">

                <div class="card-icon">

                    <i class="fa-solid fa-star"></i>

                </div>


                <h3>Total Amenities</h3>


                <h2>

                    <?php echo $total_amenities; ?>

                </h2>


                <a href="/urbannest/admin/amenities/view.php">

                    View Amenities

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


        </div>


    </main>

</body>

</html>