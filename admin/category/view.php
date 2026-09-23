<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../db_connection.php";

$sql = "SELECT category_id, category_name, description, status, photo
        FROM categories
        ORDER BY category_id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Category Management | UrbanNest</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/category.css">
</head>

<body>

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>

    <div class="un-main-wrapper">

        <?php include __DIR__ . "/../includes/header.php"; ?>

        <main class="un-page-content category-page">

            <div class="page-header">
                <div>
                    <h2>Category Management</h2>
                    <p>Manage all property categories.</p>
                </div>

                <a href="add.php" class="add-btn">
                    + Add Category
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

                            <?php if (mysqli_num_rows($result) > 0) { ?>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>
                                        <td>
                                            <?php echo (int)$row['category_id']; ?>
                                        </td>

                                        <td>
                                            <?php if (!empty($row['photo'])) { ?>
                                                <img
                                                    src="/urbannest/admin/assets/images/category/<?php echo htmlspecialchars($row['photo']); ?>"
                                                    class="category-photo"
                                                    alt="Category Photo">
                                            <?php } else { ?>
                                                No Image
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['category_name']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </td>

                                        <td>
                                            <span class="status-badge <?php echo strtolower($row['status']) === 'active' ? 'active' : 'inactive'; ?>">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="action-buttons">
                                                <a
                                                    href="edit.php?id=<?php echo (int)$row['category_id']; ?>"
                                                    class="edit-btn">
                                                    Edit
                                                </a>

                                                <a
                                                    href="delete.php?id=<?php echo (int)$row['category_id']; ?>"
                                                    class="delete-btn"
                                                    onclick="return confirm('Are you sure you want to delete this category?');">
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="6" class="no-data">
                                        No categories found.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </main>

        <?php include __DIR__ . "/../includes/footer.php"; ?>

    </div>

    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>