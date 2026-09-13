<?php

require_once __DIR__ . "/../../db_connection.php";

if (!$con) {
    die("Database connection not available");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Categories</title>

    <link rel="stylesheet" href="../assets/css-new/category.css">

</head>

<body>

    <div class="category-page">

        <div class="page-header">

            <div>

                <span class="eyebrow">UrbanNest Admin</span>

                <h2>Categories</h2>

                <p>Manage property categories</p>

            </div>

            <a href="add.php" class="add-btn">
                <i>+</i> Add Category
            </a>

        </div>


        <div class="category-card">

            <div class="table-header">

                <h3>All Categories</h3>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Photo</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        $sql = "SELECT * FROM categories";

                        $result = mysqli_query($con, $sql);

                        if (!$result) {

                            die("Query Failed: " . mysqli_error($con));
                        }


                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {

                        ?>

                                <tr>

                                    <!-- ID -->
                                    <td class="category-id">

                                        #<?php echo htmlspecialchars($row['category_id']); ?>

                                    </td>


                                    <!-- Photo -->
                                    <td class="category-image">

                                        <?php if (!empty($row['photo'])) { ?>

                                            <img src="/urbannest/admin/assets/images/category/<?php echo htmlspecialchars($row['photo']); ?>"
                                                alt="<?php echo htmlspecialchars($row['category_name']); ?>"
                                                width="80"
                                                height="55">
                                        <?php } else { ?>

                                            <span>No Image</span>

                                        <?php } ?>

                                    </td>


                                    <!-- Category Name -->
                                    <td class="category-name">

                                        <?php
                                        echo htmlspecialchars($row['category_name']);
                                        ?>

                                    </td>


                                    <!-- Description -->
                                    <td class="description">

                                        <?php
                                        echo htmlspecialchars($row['description'] ?? '');
                                        ?>

                                    </td>


                                    <!-- Status -->
                                    <td>

                                        <?php if ($row['status'] == 'Active') { ?>

                                            <span class="status active">
                                                Active
                                            </span>

                                        <?php } else { ?>

                                            <span class="status inactive">
                                                Inactive
                                            </span>

                                        <?php } ?>

                                    </td>


                                    <!-- Actions -->
                                    <td>

                                        <div class="actions">

                                            <a
                                                href="edit.php?id=<?php echo $row['category_id']; ?>"
                                                class="edit-btn">
                                                Edit
                                            </a>


                                            <a
                                                href="delete.php?id=<?php echo $row['category_id']; ?>"
                                                class="delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this category?');">
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

                                <td colspan="6" class="no-data">

                                    No categories found.

                                </td>

                            </tr>

                        <?php

                        }

                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>