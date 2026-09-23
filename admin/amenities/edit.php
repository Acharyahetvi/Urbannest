<?php
require_once __DIR__ . "/../../db_connection.php";

$message = "";
$amenity_id = 0;
$amenity_name = "";

/* CHECK AMENITY ID */
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("Invalid Amenity ID.");
}

$amenity_id = (int) $_GET['id'];

/* FETCH AMENITY */
$stmt = mysqli_prepare(
    $con,
    "SELECT amenity_name FROM amenities WHERE amenity_id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $amenity_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$amenity = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$amenity) {
    die("Amenity not found.");
}

$amenity_name = $amenity['amenity_name'];

/* UPDATE AMENITY */
if (isset($_POST['update'])) {

    $amenity_name = trim($_POST['amenity_name']);

    if ($amenity_name === "") {
        $message = "Please enter amenity name.";
    } else {

        $stmt = mysqli_prepare(
            $con,
            "UPDATE amenities
             SET amenity_name = ?
             WHERE amenity_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $amenity_name,
            $amenity_id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            header("Location: view.php?updated=1");
            exit;
        } else {
            $message = "Unable to update amenity.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Amenity | UrbanNest</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <!-- Common Layout CSS: Header, Sidebar, Footer -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <!-- Dashboard CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <!-- Amenities CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/amenities.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- HEADER -->
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <!-- SIDEBAR -->
    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <div class="amenity-edit-page">

            <!-- PAGE HEADER -->
            <div class="amenity-page-header">

                <div>
                    <span class="amenity-eyebrow">
                        AMENITY MANAGEMENT
                    </span>

                    <h1>Edit Amenity</h1>

                    <p>Update the amenity information below.</p>
                </div>

                <a href="view.php" class="amenity-back-btn">
                    Back to Amenities
                </a>

            </div>


            <!-- ERROR MESSAGE -->
            <?php if ($message !== "") { ?>

                <div class="amenity-error-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>


            <!-- EDIT FORM -->
            <div class="amenity-edit-container">

                <div class="amenity-edit-card">

                    <div class="amenity-form-heading">
                        <h2>Edit Amenity Details</h2>

                        <p>
                            Change the amenity name and save your changes.
                        </p>
                    </div>

                    <form method="POST">

                        <div class="amenity-form-group">

                            <label for="amenity_name">
                                Amenity Name
                            </label>

                            <input
                                type="text"
                                id="amenity_name"
                                name="amenity_name"
                                value="<?php echo htmlspecialchars($amenity_name); ?>"
                                placeholder="Enter amenity name"
                                required>

                        </div>

                        <div class="amenity-form-actions">

                            <button
                                type="submit"
                                name="update"
                                class="amenity-submit-btn">
                                Update Amenity
                            </button>

                            <a href="view.php"
                                class="amenity-cancel-btn">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <?php include __DIR__ . "/../includes/footer.php"; ?>

</body>

</html>