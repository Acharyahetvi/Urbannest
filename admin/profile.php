<?php

session_start();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Profile - UrbanNest</title>


    <!-- ================= GOOGLE FONT ================= -->

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap"
        rel="stylesheet">



    <!-- ================= PROFILE CSS ================= -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/profile.css">


    <!-- ================= FONT AWESOME ================= -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>


<body>


    <!-- ================= HEADER ================= -->

    <header class="header">


        <!-- LOGO + WEBSITE NAME -->

        <div class="logo">


            <!-- LOGO IMAGE -->

            <img src="/urbannest/assets/images/img1.png"
                alt="UrbanNest Logo">


            <!-- WEBSITE NAME -->

            <h2>

                <span class="urban">Urban</span><span class="nest">Nest</span>

            </h2>


        </div>


        <!-- ADMIN PROFILE ICON -->

        <div class="admin">

            AU

        </div>


    </header>



    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar">


        <!-- ADMIN PANEL TITLE -->

        <p>

            Admin Panel

        </p>


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


        <!-- PROPERTIES -->

        <a href="/urbannest/admin/property/view.php">

            <i class="fa-solid fa-house"></i>

            Properties

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

        <a href="/urbannest/admin/amenity/view.php">

            <i class="fa-solid fa-star"></i>

            Amenities

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

        <h1>

            My Profile

        </h1>


        <!-- DESCRIPTION -->

        <p class="profile-text">

            Admin account information

        </p>



        <!-- ================= PROFILE CARD ================= -->

        <div class="profile-card">


            <!-- PROFILE INITIAL -->

            <div class="profile-image">

                AU

            </div>



            <!-- ================= PROFILE INFORMATION ================= -->

            <div class="profile-info">


                <!-- ADMIN NAME -->

                <h2>

                    Admin User

                </h2>


                <!-- ROLE -->

                <p>

                    <b>Role:</b>

                    Administrator

                </p>


                <!-- STATUS -->

                <p>

                    <b>Status:</b>

                    Active

                </p>


                <!-- BACK BUTTON -->

                <a href="/urbannest/admin/view.php">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Dashboard

                </a>


            </div>


        </div>


    </main>


</body>

</html>