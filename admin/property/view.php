<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =====================================================
   DATABASE CONNECTION
===================================================== */

require_once __DIR__ . "/../../db_connection.php";

if (!isset($con) && isset($conn)) {
    $con = $conn;
}

if (!isset($con)) {
    die("Database connection not found.");
}


/* =====================================================
   GET ALL PROPERTIES
===================================================== */

$sql = "
    SELECT
        p.*,
        u.full_name,
        c.category_name,
        sc.subcategory_name,

        (
            SELECT image_path
            FROM property_images
            WHERE property_id = p.property_id
            AND is_main = 1
            LIMIT 1
        ) AS main_image

    FROM properties p

    LEFT JOIN users u
        ON p.user_id = u.user_id

    LEFT JOIN sub_categories sc
        ON p.subcategory_id = sc.subcategory_id

    LEFT JOIN categories c
        ON sc.category_id = c.category_id

    ORDER BY p.property_id DESC
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Properties | UrbanNest Admin</title>
    
     <!-- FONT AWESOME -->
    <link rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <!-- COMMON STYLE CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <!-- ADMIN CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <!-- PROPERTY CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/property.css">
</head>

<body>

    <!-- =====================================================
         COMMON SIDEBAR
    ====================================================== -->

    <?php
    include __DIR__ . "/../includes/sidebar.php";
    ?>

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- =====================================================
         MAIN WRAPPER
    ====================================================== -->

    <div class="un-main-wrapper">

        <!-- COMMON HEADER -->
        <?php
        include __DIR__ . "/../includes/header.php";
        ?>


        <!-- =================================================
             PROPERTY MAIN CONTENT
        ================================================== -->

        <main class="property-page">

            <!-- PAGE HEADER -->
            <div class="property-header">

                <div>

                    <span class="property-eyebrow">
                        PROPERTY MANAGEMENT
                    </span>

                    <h1>
                        Properties
                    </h1>

                    <p>
                        Manage all property listings
                    </p>

                </div>

                <!-- ADD PROPERTY BUTTON -->
                <a
                    href="add.php"
                    class="add-property-btn">

                    + Add Property

                </a>

            </div>


            <!-- =================================================
                 PROPERTY TABLE
            ================================================== -->

            <div class="property-table-wrapper">

                <table class="property-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Property</th>
                            <th>Owner</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Listing</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($result) > 0) { ?>

                            <?php while ($property = mysqli_fetch_assoc($result)) { ?>

                                <tr>

                                    <!-- ID -->
                                    <td>
                                        #<?php
                                            echo (int) $property["property_id"];
                                            ?>
                                    </td>


                                    <!-- PROPERTY -->
                                    <td>

                                        <div class="property-info">

                                            <?php if (!empty($property["main_image"])) { ?>

                                                <img
                                                    src="/urbannest/<?php
                                                                    echo htmlspecialchars(
                                                                        $property["main_image"]
                                                                    );
                                                                    ?>"
                                                    alt="Property Image"
                                                    class="property-thumbnail">

                                            <?php } else { ?>

                                                <div class="no-property-image">
                                                    🏠
                                                </div>

                                            <?php } ?>


                                            <div>

                                                <strong>
                                                    <?php
                                                    echo htmlspecialchars(
                                                        $property["title"] ?? ""
                                                    );
                                                    ?>
                                                </strong>

                                                <small>
                                                    <?php
                                                    echo htmlspecialchars(
                                                        $property["subcategory_name"]
                                                            ?? "No Subcategory"
                                                    );
                                                    ?>
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- OWNER -->
                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $property["full_name"] ?? "Unknown"
                                        );
                                        ?>
                                    </td>


                                    <!-- LOCATION -->
                                    <td>

                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $property["city"] ?? "-"
                                            );
                                            ?>
                                        </strong>

                                        <br>

                                        <small>
                                            <?php
                                            echo htmlspecialchars(
                                                $property["state"] ?? "-"
                                            );
                                            ?>
                                        </small>

                                    </td>


                                    <!-- PRICE -->
                                    <td>
                                        ₹<?php
                                            echo number_format(
                                                (float) ($property["price"] ?? 0)
                                            );
                                            ?>
                                    </td>


                                    <!-- TYPE -->
                                    <td>

                                        <span class="property-category">
                                            <?php
                                            echo htmlspecialchars(
                                                $property["category_name"] ?? "-"
                                            );
                                            ?>
                                        </span>

                                        <br>

                                        <small>
                                            <?php
                                            echo htmlspecialchars(
                                                $property["subcategory_name"] ?? "-"
                                            );
                                            ?>
                                        </small>

                                    </td>


                                    <!-- LISTING TYPE -->
                                    <td>

                                        <span class="listing-badge">
                                            <?php
                                            echo htmlspecialchars(
                                                $property["listing_type"] ?? "-"
                                            );
                                            ?>
                                        </span>

                                    </td>


                                    <!-- ACTION BUTTONS -->
                                    <td>

                                        <div class="property-actions">

                                            <!-- VIEW -->
                                            <a
                                                href="view-single.php?id=<?php
                                                                            echo (int) $property["property_id"];
                                                                            ?>"
                                                class="property-view-btn">
                                                View
                                            </a>


                                            <!-- EDIT -->
                                            <a
                                                href="edit.php?id=<?php
                                                                    echo (int) $property["property_id"];
                                                                    ?>"
                                                class="property-edit-btn">
                                                Edit
                                            </a>


                                            <!-- DELETE -->
                                            <a
                                                href="delete.php?id=<?php
                                                                    echo (int) $property["property_id"];
                                                                    ?>"
                                                class="property-delete-btn"
                                                onclick="return confirm(
                                                    'Are you sure you want to delete this property?'
                                                );">
                                                Delete
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <!-- NO DATA -->
                            <tr>

                                <td
                                    colspan="8"
                                    class="property-no-data">

                                    🏠
                                    <br>
                                    No Properties Found

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </main>


        <!-- =================================================
             COMMON FOOTER
        ================================================== -->

        <?php
        include __DIR__ . "/../includes/footer.php";
        ?>

    </div>


    <!-- BOOTSTRAP JS -->
    <script
        src="/urbannest/admin/assets/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>