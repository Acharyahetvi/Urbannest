<?php
require_once __DIR__ . "/../../db_connection.php";

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Sub Categories</title>

    <link rel="stylesheet" href="../assets/css-new/sub-category.css">
</head>

<body>

    <div class="subcategory-view-container">

        <div class="page-header">
            <h2>Sub Categories</h2>

            <a href="add.php" class="add-btn">
                + Add Sub Category
            </a>
        </div>

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
                                    <?php echo $row['subcategory_id']; ?>
                                </td>


                                <!-- Category -->
                                <td>
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </td>


                                <!-- Photo -->
                                <td>

                                    <?php if (!empty($row['photo'])) { ?>

                                        <img
                                            src="../assets/images/subcategory/<?php echo htmlspecialchars($row['photo']); ?>"
                                            alt="<?php echo htmlspecialchars($row['subcategory_name']); ?>"
                                            width="100"
                                            height="70">

                                    <?php } else { ?>

                                        <span>
                                            No Image
                                        </span>

                                    <?php } ?>

                                </td>


                                <!-- Sub Category -->
                                <td>
                                    <?php echo htmlspecialchars($row['subcategory_name']); ?>
                                </td>


                                <!-- Description -->
                                <td>
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>


                                <!-- Status -->
                                <td>

                                    <?php if ($row['status'] == 'Active') { ?>

                                        <span class="status-active">
                                            Active
                                        </span>

                                    <?php } else { ?>

                                        <span class="status-inactive">
                                            Inactive
                                        </span>

                                    <?php } ?>

                                </td>


                                <!-- Action -->
                                <td>

                                    <a href="edit.php?id=<?php echo $row['subcategory_id']; ?>"
                                        class="edit-btn">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?php echo $row['subcategory_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this subcategory?');">
                                        Delete
                                    </a>

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

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>