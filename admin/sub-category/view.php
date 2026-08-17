<?php
include "../connect.php";

$sql = "SELECT 
            s.subcategory_id,
            s.subcategory_name,
            s.description,
            s.status,
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

    <link rel="stylesheet" href="../assets/css/view.css">
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

                        <td>
                            <?php echo $row['subcategory_id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['category_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['subcategory_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['description']); ?>
                        </td>

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

                        <td>

                            <a href="edit.php?id=<?php echo $row['subcategory_id']; ?>"
                               class="edit-btn">
                                Edit
                            </a>

                        </td>

                    </tr>

                <?php
                    }

                } else {
                ?>

                    <tr>
                        <td colspan="6" class="no-data">
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