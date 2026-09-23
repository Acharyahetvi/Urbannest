<?php

require_once __DIR__ . "/../../db_connection.php";


/* =========================================================
   FETCH AMENITIES
========================================================= */

$sql = "SELECT * FROM amenities ORDER BY amenity_id ASC";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Failed to fetch amenities: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Amenities | UrbanNest</title>


    <!-- Bootstrap CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">


    <!-- Common CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">


    <!-- Amenities CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/amenities.css">


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body class="admin-page">


    <!-- Header -->

    <?php include __DIR__ . "/../includes/header.php"; ?>


    <!-- Sidebar -->

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- Main Content -->

    <main class="main-content">

        <div class="amenity-view-page">


            <!-- =========================================
                 PAGE HEADER
            ========================================== -->

            <div class="amenity-page-header">

                <div>

                    <span class="amenity-eyebrow">
                        AMENITY MANAGEMENT
                    </span>

                    <h1>Manage Amenities</h1>

                    <p>
                        View and manage property amenities.
                    </p>

                </div>


                <!-- Add Amenity Button -->

                <a href="add.php" class="amenity-add-btn">

                    <i class="fa-solid fa-plus"></i>

                    Add Amenity

                </a>

            </div>



            <!-- =========================================
                 SUCCESS MESSAGES
            ========================================== -->

            <?php if (
                isset($_GET["message"]) &&
                $_GET["message"] == "added"
            ) { ?>

                <div class="amenity-success-message">

                    Amenity added successfully!

                </div>

            <?php } ?>


            <?php if (
                isset($_GET["message"]) &&
                $_GET["message"] == "deleted"
            ) { ?>

                <div class="amenity-success-message">

                    Amenity deleted successfully!

                </div>

            <?php } ?>



            <!-- =========================================
                 AMENITIES TABLE
            ========================================== -->

            <div class="amenity-table-wrapper">

                <table class="amenity-table">


                    <!-- Table Header -->

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Amenity Name</th>

                            <th>Actions</th>

                        </tr>

                    </thead>



                    <!-- Table Body -->

                    <tbody>


                        <?php if (mysqli_num_rows($result) > 0) { ?>


                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                <tr>


                                    <!-- Amenity ID -->

                                    <td>
                                        <?php
                                        echo (int) $row["amenity_id"];
                                        ?>
                                    </td>



                                    <!-- Amenity Name -->

                                    <td>

                                        <span class="amenity-name">

                                            <?php
                                            echo htmlspecialchars(
                                                $row["amenity_name"]
                                            );
                                            ?>

                                        </span>

                                    </td>



                                    <!-- Actions -->

                                    <td class="amenity-action-cell">

                                        <div class="amenity-actions">


                                            <!-- Edit Button -->

                                            <a
                                                href="edit.php?id=<?php echo (int) $row["amenity_id"]; ?>"
                                                class="amenity-edit-btn">

                                                Edit

                                            </a>



                                            <!-- Delete Button -->

                                            <a
                                                href="delete.php?id=<?php echo (int) $row["amenity_id"]; ?>"
                                                class="amenity-delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this amenity?');">

                                                Delete

                                            </a>


                                        </div>

                                    </td>


                                </tr>

                            <?php } ?>


                        <?php } else { ?>


                            <!-- No Amenities Found -->

                            <tr>

                                <td colspan="3" class="amenity-empty">

                                    No amenities found.

                                </td>

                            </tr>


                        <?php } ?>


                    </tbody>

                </table>

            </div>


        </div>

    </main>



    <!-- Footer -->

    <?php include __DIR__ . "/../includes/footer.php"; ?>


</body>

</html>