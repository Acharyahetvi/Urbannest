<?php
session_start();

require_once __DIR__ . "/../../db_connection.php";

$message = "";

/* Add Property */
if (isset($_POST['add_property'])) {

    $user_id = $_POST['user_id'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];

    $title = $_POST['title'];
    $description = $_POST['description'];
    $listing_type = $_POST['listing_type'];

    $price = $_POST['price'];
    $area_sqft = $_POST['area_sqft'];

    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    $sql = "INSERT INTO properties
            (user_id, category_id, subcategory_id, title,
             description, listing_type, price, area_sqft,
             address, city, state)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiisssddsss",
        $user_id,
        $category_id,
        $subcategory_id,
        $title,
        $description,
        $listing_type,
        $price,
        $area_sqft,
        $address,
        $city,
        $state
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Property added successfully!";
    } else {
        $message = "Error adding property: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

/* Get Users */
$users = mysqli_query(
    $conn,
    "SELECT user_id FROM users"
);

/* Get Categories */
$categories = mysqli_query(
    $conn,
    "SELECT category_id, category_name FROM categories"
);

/* Get Subcategories */
$subcategories = mysqli_query(
    $conn,
    "SELECT subcategory_id, subcategory_name FROM sub_categories"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Property - UrbanNest</title>

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <link rel="stylesheet" href="../assets/css/property.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <div class="property-container">

        <div class="page-header">
            <h2>Add New Property</h2>
            <a href="view.php" class="back-btn">Back</a>
        </div>

        <?php if ($message != "") { ?>
            <p class="message">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>

        <form method="POST" class="property-form">

            <div class="form-group">
                <label>Owner ID</label>

                <select name="user_id" required>
                    <option value="">Select Owner</option>

                    <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                        <option value="<?php echo $user['user_id']; ?>">
                            User ID: <?php echo $user['user_id']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>

                <select name="category_id" required>
                    <option value="">Select Category</option>

                    <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Sub Category</label>

                <select name="subcategory_id" required>
                    <option value="">Select Sub Category</option>

                    <?php while ($sub = mysqli_fetch_assoc($subcategories)) { ?>
                        <option value="<?php echo $sub['subcategory_id']; ?>">
                            <?php echo htmlspecialchars($sub['subcategory_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Property Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>

            <div class="form-group">
                <label>Listing Type</label>

                <select name="listing_type" required>
                    <option value="">Select Type</option>
                    <option value="Sale">Sale</option>
                    <option value="Rent">Rent</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Area (sq. ft.)</label>
                <input type="number" name="area_sqft" min="0" step="0.01" required>
            </div>

            <div class="form-group full-width">
                <label>Address</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required>
            </div>

            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" value="Gujarat" required>
            </div>

            <div class="form-actions">
                <button type="submit" name="add_property" class="submit-btn">
                    Add Property
                </button>
            </div>

        </form>

    </div>

</body>

</html>