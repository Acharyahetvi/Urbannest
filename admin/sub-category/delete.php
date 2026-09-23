<?php

require_once __DIR__ . "/../db_connection.php";


// Check Subcategory ID

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid Subcategory ID.");
}

$subcategory_id = (int) $_GET["id"];


// Check Subcategory Exists

$check_sql = "SELECT subcategory_id
              FROM sub_categories
              WHERE subcategory_id = $subcategory_id";

$check_result = mysqli_query($con, $check_sql);

if (!$check_result || mysqli_num_rows($check_result) == 0) {
    die("Subcategory not found.");
}


// Delete Subcategory

if (isset($_POST["confirm_delete"])) {

    // Delete related properties first

    $sql1 = "DELETE FROM properties
             WHERE subcategory_id = $subcategory_id";

    if (mysqli_query($con, $sql1)) {

        // Delete subcategory

        $sql2 = "DELETE FROM sub_categories
                 WHERE subcategory_id = $subcategory_id";

        if (mysqli_query($con, $sql2)) {

            header("Location: view.php?message=deleted");
            exit();
        } else {

            echo "Error deleting subcategory: " .
                mysqli_error($con);
        }
    } else {

        echo "Error deleting properties: " .
            mysqli_error($con);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Delete Subcategory | UrbanNest</title>

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/sub-category.css">

</head>


<body class="admin-page">

    <?php include __DIR__ . "/../includes/header.php"; ?>

    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <main class="main-content">

        <div class="subcategory-delete-page">

            <div class="delete-card">

                <div class="delete-icon">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <h2>Delete Subcategory?</h2>

                <p>
                    Are you sure you want to delete this subcategory?
                    Its related properties will also be deleted.
                </p>

                <form method="POST">

                    <button
                        type="submit"
                        name="confirm_delete"
                        class="delete-confirm-btn">

                        Yes, Delete

                    </button>

                    <a href="view.php" class="delete-cancel-btn">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </main>


    <?php include __DIR__ . "/../includes/footer.php"; ?>

</body>

</html>