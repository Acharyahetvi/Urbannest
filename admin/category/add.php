<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================================================
   DATABASE CONNECTION
========================================================= */

require_once __DIR__ . "/../../db_connection.php";

if (!isset($con) && isset($conn)) {
    $con = $conn;
}

if (!isset($con)) {
    die("Database connection not found.");
}

$message = "";

/* =========================================================
   ADD CATEGORY
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $category_name = trim($_POST['category_name'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $status = $_POST['status'] ?? "Active";

    $photo = "";

    if ($category_name === "") {

        $message = "Please enter category name.";
    } elseif (!in_array($status, ["Active", "Inactive"], true)) {

        $message = "Invalid status selected.";
    } else {

        /* PHOTO UPLOAD */

        if (
            isset($_FILES['photo']) &&
            $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {

                $message = "Photo upload failed.";
            } else {

                $allowed = ["jpg", "jpeg", "png", "webp"];

                $originalName = $_FILES['photo']['name'];

                $extension = strtolower(
                    pathinfo($originalName, PATHINFO_EXTENSION)
                );

                if (!in_array($extension, $allowed, true)) {

                    $message = "Only JPG, JPEG, PNG and WEBP files are allowed.";
                } else {

                    $uploadDir = __DIR__ . "/../assets/images/category/";

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $photo = uniqid("category_", true) . "." . $extension;

                    if (!move_uploaded_file(
                        $_FILES['photo']['tmp_name'],
                        $uploadDir . $photo
                    )) {

                        $message = "Unable to save uploaded photo.";
                    }
                }
            }
        }

        /* INSERT CATEGORY */

        if ($message === "") {

            $stmt = mysqli_prepare(
                $con,
                "INSERT INTO categories
                (category_name, description, status, photo)
                VALUES (?, ?, ?, ?)"
            );

            if (!$stmt) {

                $message = "Database query preparation failed.";
            } else {

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssss",
                    $category_name,
                    $description,
                    $status,
                    $photo
                );

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_close($stmt);

                    header("Location: view.php");
                    exit;
                } else {

                    $message = "Error adding category.";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Category | UrbanNest</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <!-- Common Admin CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <!-- Category CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/category.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>

<body>

    <!-- SIDEBAR: INCLUDE ONLY ONCE -->
    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- MAIN WRAPPER -->
    <div class="un-main-wrapper">

        <!-- HEADER -->
        <?php include __DIR__ . "/../includes/header.php"; ?>


        <!-- PAGE CONTENT -->
        <main class="un-content category-page">

            <!-- PAGE HEADER -->
            <div class="page-header">

                <div>
                    <h2>Add Category</h2>
                    <p>Add a new property category.</p>
                </div>

                <a href="view.php" class="back-btn">
                    ← Back to Category
                </a>

            </div>


            <!-- CATEGORY FORM CARD -->
            <div class="category-form-card">

                <h3>Category Information</h3>


                <!-- ERROR MESSAGE -->
                <?php if ($message !== "") { ?>

                    <div class="form-message">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php } ?>


                <!-- FORM -->
                <form method="POST" enctype="multipart/form-data">

                    <!-- CATEGORY NAME -->
                    <div class="form-group">

                        <label for="category_name">
                            Category Name
                        </label>

                        <input
                            type="text"
                            id="category_name"
                            name="category_name"
                            placeholder="Enter category name"
                            required
                            value="<?php
                                    echo htmlspecialchars(
                                        $_POST['category_name'] ?? ''
                                    );
                                    ?>">

                    </div>


                    <!-- DESCRIPTION -->
                    <div class="form-group">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Enter category description"><?php
                                                                        echo htmlspecialchars(
                                                                            $_POST['description'] ?? ''
                                                                        );
                                                                        ?></textarea>

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

                            <option value="Active"
                                <?php
                                echo (
                                    ($_POST['status'] ?? 'Active') === 'Active'
                                ) ? 'selected' : '';
                                ?>>
                                Active
                            </option>

                            <option value="Inactive"
                                <?php
                                echo (
                                    ($_POST['status'] ?? '') === 'Inactive'
                                ) ? 'selected' : '';
                                ?>>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <!-- CATEGORY PHOTO -->
                    <div class="form-group">

                        <label for="photo">
                            Category Photo
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.webp">

                    </div>


                    <!-- BUTTONS -->
                    <div class="form-actions">

                        <button
                            type="submit"
                            class="save-btn">
                            Save Category
                        </button>

                        <a
                            href="view.php"
                            class="cancel-btn">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </main>


        <!-- FOOTER -->
        <?php include __DIR__ . "/../includes/footer.php"; ?>

    </div>


    <!-- Bootstrap JS (for mobile sidebar/offcanvas) -->
    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>