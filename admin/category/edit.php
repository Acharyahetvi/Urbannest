<?php
require_once __DIR__ . "/../../db_connection.php";

$message = "";

/* =========================
   GET CATEGORY ID
========================= */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Category ID not found.");
}

$category_id = (int) $_GET['id'];


/* =========================
   FETCH CATEGORY
========================= */

$sql = "SELECT * FROM categories WHERE category_id = $category_id LIMIT 1";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($con));
}

if (mysqli_num_rows($result) == 0) {
    die("Category not found.");
}

$category = mysqli_fetch_assoc($result);


/* =========================
   UPDATE CATEGORY
========================= */

if (isset($_POST['update'])) {

    $category_name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    if ($category_name == "") {

        $message = "Category name is required.";
    } else {

        /* Keep old photo */
        $photo_name = $category['photo'];

        /* =========================
           NEW PHOTO
        ========================= */

        if (isset($_FILES['photo']) && $_FILES['photo']['name'] != "") {

            $photo = $_FILES['photo']['name'];
            $photo_tmp = $_FILES['photo']['tmp_name'];

            $upload_folder = __DIR__ . "/../assets/images/category/";

            /* Create folder if not exists */
            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }

            /* Create unique filename */
            $photo_name = time() . "_" . basename($photo);

            /* Upload photo */
            if (!move_uploaded_file(
                $photo_tmp,
                $upload_folder . $photo_name
            )) {

                $message = "Photo upload failed.";
            }
        }


        /* =========================
           UPDATE DATABASE
        ========================= */

        if ($message == "") {

            $category_name = mysqli_real_escape_string($con, $category_name);
            $description = mysqli_real_escape_string($con, $description);
            $status = mysqli_real_escape_string($con, $status);
            $photo_name = mysqli_real_escape_string($con, $photo_name);

            $update_sql = "UPDATE categories SET
                            category_name = '$category_name',
                            description = '$description',
                            status = '$status',
                            photo = '$photo_name'
                           WHERE category_id = $category_id";

            if (mysqli_query($con, $update_sql)) {

                $message = "Category updated successfully!";

                /* Fetch updated category */
                $result = mysqli_query(
                    $con,
                    "SELECT * FROM categories
                     WHERE category_id = $category_id
                     LIMIT 1"
                );

                $category = mysqli_fetch_assoc($result);
            } else {

                $message = "Error updating category: "
                    . mysqli_error($con);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Category | UrbanNest</title>

    <link rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

    <div class="subcategory-container">

        <h2>Edit Category</h2>


        <!-- MESSAGE -->

        <?php if ($message != "") { ?>

            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <!-- EDIT FORM -->

        <form method="POST"
            enctype="multipart/form-data">


            <!-- CATEGORY NAME -->

            <div class="form-group">

                <label for="category_name">
                    Category Name
                </label>

                <input
                    type="text"
                    id="category_name"
                    name="category_name"
                    value="<?php echo htmlspecialchars($category['category_name']); ?>"
                    required>

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"><?php echo htmlspecialchars($category['description']); ?></textarea>

            </div>


            <!-- CURRENT PHOTO -->

            <div class="form-group">

                <label>
                    Current Photo
                </label>

                <?php if (!empty($category['photo'])) { ?>

                    <br><br>

                    <img
                        src="../assets/images/category/<?php echo htmlspecialchars($category['photo']); ?>"
                        alt="Category Photo"
                        width="150">

                <?php } else { ?>

                    <p>No photo uploaded.</p>

                <?php } ?>

            </div>


            <!-- CHANGE PHOTO -->

            <div class="form-group">

                <label for="photo">
                    Change Photo
                </label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept="image/*">

                <small>
                    Leave empty if you want to keep the current photo.
                </small>

            </div>


            <!-- STATUS -->

            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required>

                    <option
                        value="Active"
                        <?php
                        if ($category['status'] == 'Active') {
                            echo 'selected';
                        }
                        ?>>
                        Active
                    </option>

                    <option
                        value="Inactive"
                        <?php
                        if ($category['status'] == 'Inactive') {
                            echo 'selected';
                        }
                        ?>>
                        Inactive
                    </option>

                </select>

            </div>


            <!-- UPDATE BUTTON -->

            <button
                type="submit"
                name="update"
                class="submit-btn">
                Update Category
            </button>


            <!-- BACK BUTTON -->

            <a
                href="view.php"
                class="submit-btn">
                Back
            </a>

        </form>

    </div>

</body>

</html>