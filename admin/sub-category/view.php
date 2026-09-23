<?php

require_once __DIR__ . "/../../db_connection.php";


/* =========================================================
   GET SUB CATEGORIES
========================================================= */

$sql = "SELECT
            s.subcategory_id,
            s.subcategory_name,
            s.description,
            s.status,
            s.photo,
            c.category_name
        FROM sub_categories s
        LEFT JOIN categories c
        ON s.category_id = c.category_id
        ORDER BY s.subcategory_id ASC";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sub Categories - UrbanNest</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/admin.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/sub-category.css">

</head>


<body class="admin-page">


    <!-- HEADER -->

    <?php include __DIR__ . "/../includes/header.php"; ?>


    <!-- SIDEBAR -->

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="subcategory-view-container">


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div>

                    <div class="page-eyebrow">
                        SUB CATEGORY MANAGEMENT
                    </div>

                    <h2>
                        Sub Categories
                    </h2>

                    <p>
                        Manage property sub categories
                    </p>

                </div>


                <a href="add.php" class="add-btn">

                    <i class="fa-solid fa-plus"></i>

                    Add Sub Category

                </a>

            </div>



            <!-- TABLE -->

            <div class="table-container">

                <table class="subcategory-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Category</th>

                            <th>Photo</th>

                            <th>Sub Category</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {

                        ?>


                                <tr>


                                    <!-- ID -->

                                    <td>

                                        <?php
                                        echo (int)$row['subcategory_id'];
                                        ?>

                                    </td>



                                    <!-- CATEGORY -->

                                    <td>

                                        <?php

                                        echo !empty($row['category_name'])
                                            ? htmlspecialchars($row['category_name'])
                                            : 'N/A';

                                        ?>

                                    </td>



                                    <!-- PHOTO -->

                                    <td>

                                        <?php if (!empty($row['photo'])) { ?>


                                            <img
                                                src="/urbannest/admin/assets/images/subcategory/<?php echo htmlspecialchars($row['photo']); ?>"
                                                alt="<?php echo htmlspecialchars($row['subcategory_name']); ?>"
                                                class="subcategory-photo">


                                        <?php } else { ?>


                                            <span class="no-image">
                                                No Image
                                            </span>


                                        <?php } ?>

                                    </td>



                                    <!-- SUB CATEGORY -->

                                    <td>

                                        <strong class="subcategory-name">

                                            <?php
                                            echo htmlspecialchars(
                                                $row['subcategory_name']
                                            );
                                            ?>

                                        </strong>

                                    </td>



                                    <!-- DESCRIPTION -->

                                    <td>

                                        <div class="subcategory-description">

                                            <?php

                                            echo !empty($row['description'])
                                                ? htmlspecialchars($row['description'])
                                                : 'No description';

                                            ?>

                                        </div>

                                    </td>



                                    <!-- STATUS -->

                                    <td>

                                        <?php

                                        if ($row['status'] == 'Active') {

                                        ?>

                                            <span class="status-active">
                                                Active
                                            </span>

                                        <?php

                                        } else {

                                        ?>

                                            <span class="status-inactive">
                                                Inactive
                                            </span>

                                        <?php

                                        }

                                        ?>

                                    </td>



                                    <!-- ACTION -->

                                    <td>

                                        <div class="action-buttons">


                                            <a
                                                href="edit.php?id=<?php echo (int)$row['subcategory_id']; ?>"
                                                class="edit-btn">

                                                Edit

                                            </a>


                                            <a
                                                href="delete.php?id=<?php echo (int)$row['subcategory_id']; ?>"
                                                class="delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this subcategory?');">

                                                Delete

                                            </a>


                                        </div>

                                    </td>


                                </tr>


                            <?php

                            }
                        } else {

                            ?>


                            <tr>

                                <td colspan="7" class="no-data">

                                    No sub categories found.

                                </td>

                            </tr>


                        <?php

                        }

                        ?>


                    </tbody>

                </table>

            </div>


        </div>

    </main>



    <!-- FOOTER -->

    <?php include __DIR__ . "/../includes/footer.php"; ?>


</body>

</html>