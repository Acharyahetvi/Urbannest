<?php
require_once __DIR__ . "/../../db_connection.php";

$message = "";

// Add Amenity
if (isset($_POST['add'])) {

    $amenity_name = trim($_POST['amenity_name']);

    if ($amenity_name == "") {

        $message = "Please enter an amenity name.";
    } else {

        $sql = "INSERT INTO amenities (amenity_name)
                VALUES ('$amenity_name')";

        if (mysqli_query($con, $sql)) {

            header("Location: view.php?added=1");
            exit;
        } else {

            $message = "Error adding amenity.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Amenity - UrbanNest</title>

    <!-- CSS -->
    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/amenities.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- Header -->
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <!-- Sidebar -->
    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <!-- Main Content -->
    <main class="main-content">

        <div class="amenity-add-page">

            <div class="amenity-page-header">
                <div>
                    <span class="amenity-eyebrow">
                        AMENITY MANAGEMENT
                    </span>

                    <h1>Add Amenity</h1>
                    <p>Add a new amenity to UrbanNest.</p>
                </div>

                <a href="view.php" class="amenity-back-btn">
                    Back to Amenities
                </a>
            </div>


            <!-- Error Message -->
            <?php if ($message != "") { ?>

                <div class="amenity-error-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>


            <!-- Add Form -->
            <div class="amenity-edit-container">

                <div class="amenity-edit-card">

                    <div class="amenity-form-heading">
                        <h2>Amenity Details</h2>
                        <p>Enter the new amenity name.</p>
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
                                placeholder="Enter amenity name"
                                required>
                        </div>

                        <div class="amenity-form-actions">

                            <button
                                type="submit"
                                name="add"
                                class="amenity-submit-btn">
                                Add Amenity
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


    <!-- Footer -->
    <?php include __DIR__ . "/../includes/footer.php"; ?>

</body>

</html>