<?php

require_once __DIR__ . "/../db_connection.php";

// Total Users
$user_sql = "SELECT COUNT(*) AS total FROM users";
$user_result = mysqli_query($conn, $user_sql);
$user_data = mysqli_fetch_assoc($user_result);
$total_users = $user_data['total'];

// Total Properties
$property_sql = "SELECT COUNT(*) AS total FROM properties";
$property_result = mysqli_query($conn, $property_sql);
$property_data = mysqli_fetch_assoc($property_result);
$total_properties = $property_data['total'];

// Total Categories
$category_sql = "SELECT COUNT(*) AS total FROM categories";
$category_result = mysqli_query($conn, $category_sql);
$category_data = mysqli_fetch_assoc($category_result);
$total_categories = $category_data['total'];

// Total Sub Categories
$subcategory_sql = "SELECT COUNT(*) AS total FROM sub_categories";
$subcategory_result = mysqli_query($conn, $subcategory_sql);
$subcategory_data = mysqli_fetch_assoc($subcategory_result);
$total_subcategories = $subcategory_data['total'];

// Total Amenities
$amenity_sql = "SELECT COUNT(*) AS total FROM amenities";
$amenity_result = mysqli_query($conn, $amenity_sql);
$amenity_data = mysqli_fetch_assoc($amenity_result);
$total_amenities = $amenity_data['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Admin Dashboard - UrbanNest</title>

    <!-- Common CSS -->
    <link rel="stylesheet" href="/urbannest/admin/assets/css/view.css">
</head>

<body>

    <!-- Sidebar -->

    <div class="sidebar">

        <h2>UrbanNest</h2>

        <p>Admin Panel</p>

        <a href="dashboard.php">Home</a>

        <a href="users/view.php">Users</a>

        <a href="property/view.php">Properties</a>

        <a href="category/view.php">Categories</a>

        <a href="sub-category/view.php">Sub Categories</a>

        <a href="amenities/view.php">Amenities</a>

    </div>


    <!-- Main Content -->

    <div class="main">

        <h1>Admin Dashboard</h1>

        <p class="welcome">
            Welcome to UrbanNest Admin Panel
        </p>


        <!-- Dashboard Cards -->

        <div class="cards">

            <!-- Users -->

            <div class="card">

                <h3>Total Users</h3>

                <h2>
                    <?php echo $total_users; ?>
                </h2>

                <a href="users/view.php">
                    View Users
                </a>

            </div>


            <!-- Properties -->

            <div class="card">

                <h3>Total Properties</h3>

                <h2>
                    <?php echo $total_properties; ?>
                </h2>

                <a href="property/view.php">
                    View Properties
                </a>

            </div>


            <!-- Categories -->

            <div class="card">

                <h3>Total Categories</h3>

                <h2>
                    <?php echo $total_categories; ?>
                </h2>

                <a href="category/view.php">
                    View Categories
                </a>

            </div>


            <!-- Sub Categories -->

            <div class="card">

                <h3>Total Sub Categories</h3>

                <h2>
                    <?php echo $total_subcategories; ?>
                </h2>

                <a href="sub-category/view.php">
                    View Sub Categories
                </a>

            </div>


            <!-- Amenities -->

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