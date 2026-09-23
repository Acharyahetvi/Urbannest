<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../db_connection.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Category ID.");
}

/* Get Category */
$sql = "SELECT * FROM categories WHERE category_id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Category not found.");
}

$category = mysqli_fetch_assoc($result);

/* Update Category */
if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST['category_name'])
    );

    $description = mysqli_real_escape_string(
        $conn,
        trim($_POST['description'])
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $photo = $category['photo'];

    /* Image Upload */
    if (!empty($_FILES['photo']['name'])) {

        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $targetDir = __DIR__ . "/../assets/images/category/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photo = $fileName;
        } else {
            die("Image upload failed.");
        }
    }

    $updateSql = "UPDATE categories SET
                    category_name = '$name',
                    description = '$description',
                    status = '$status',
                    photo = '$photo'
                  WHERE category_id = $id";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: view.php?success=updated");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Category | UrbanNest</title>

    <link rel="stylesheet" href="/urbannest/admin/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">
    <link rel="stylesheet" href="/urbannest/admin/assets/css/category.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>

    <div class="un-main-wrapper">

        <?php include __DIR__ . "/../includes/header.php"; ?>

        <main class="un-page-content category-page">

            <div class="category-page-header">
                <div>
                    <h2>Edit Category</h2>
                    <p>Update category information</p>
                </div>

                <a href="view.php" class="btn btn-secondary">
                    ← Back to Category

                </a>
            </div>

            <div class="category-form-card">

                <form method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>

                        <input
                            type="text"
                            name="category_name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($category['category_name']); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            required><?php echo htmlspecialchars($category['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>

                        <select name="status" class="form-control" required>
                            <option value="Active"
                                <?php if ($category['status'] == 'Active') echo 'selected'; ?>>
                                Active
                            </option>

                            <option value="Inactive"
                                <?php if ($category['status'] == 'Inactive') echo 'selected'; ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Photo</label>
                        <br>

                        <?php if (!empty($category['photo'])) { ?>
                            <img
                                src="/urbannest/admin/assets/images/category/<?php echo htmlspecialchars($category['photo']); ?>"
                                width="120"
                                alt="Category Photo">
                        <?php } else { ?>
                            <p>No image available</p>
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Change Photo (Optional)</label>

                        <input
                            type="file"
                            name="photo"
                            class="form-control"
                            accept="image/*">
                    </div>

                    <button type="submit" name="update" class="btn btn-primary">
                        Update Category
                    </button>

                    <a href="view.php" class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </main>

        <?php include __DIR__ . "/../includes/footer.php"; ?>

    </div>

    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>