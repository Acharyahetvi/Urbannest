<?php
include "../connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Categories</title>

    <link rel="stylesheet" href="../assets/css/view.css">
</head>

<body>

    <div class="category-page">

        <!-- Page Header -->
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


        <!-- Category Table -->
        <div class="category-card">

            <div class="table-header">
                <h3>All Categories</h3>
            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
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

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>

                                <tr>

                                    <td class="category-id">
                                        #<?php echo $row['category_id']; ?>
                                    </td>

                                    <td class="category-name">
                                        <?php echo htmlspecialchars($row['category_name']); ?>
                                    </td>

                                    <td class="description">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </td>

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
                                <td colspan="5" class="no-data">
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