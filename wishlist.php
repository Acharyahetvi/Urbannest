<?php
session_start();
include "db_connection.php";
/* TEMPORARY: for wishlist testing */
$user_id = 2;

/* Add / Remove from wishlist */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $property_id = (int)($_POST['property_id'] ?? 0);

    if ($property_id > 0) {

        if ($action === 'add') {

            /* Prevent duplicate wishlist entries */
            $check = mysqli_prepare(
                $conn,
                "SELECT wishlist_id
                 FROM wishlist
                 WHERE user_id = ? AND property_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $check,
                "ii",
                $user_id,
                $property_id
            );

            mysqli_stmt_execute($check);
            $checkResult = mysqli_stmt_get_result($check);

            if (mysqli_num_rows($checkResult) === 0) {

                $insert = mysqli_prepare(
                    $conn,
                    "INSERT INTO wishlist
                    (user_id, property_id, created_at)
                    VALUES (?, ?, NOW())"
                );

                mysqli_stmt_bind_param(
                    $insert,
                    "ii",
                    $user_id,
                    $property_id
                );

                mysqli_stmt_execute($insert);
                mysqli_stmt_close($insert);
            }

            mysqli_stmt_close($check);

        } elseif ($action === 'remove') {

            $delete = mysqli_prepare(
                $conn,
                "DELETE FROM wishlist
                 WHERE user_id = ? AND property_id = ?"
            );

            mysqli_stmt_bind_param(
                $delete,
                "ii",
                $user_id,
                $property_id
            );

            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);
        }
    }

    /* Return to the page that sent the request */
    $redirect = $_POST['redirect'] ?? 'wishlist.php';

    header("Location: " . $redirect);
    exit;
}


/* Old GET remove support */
if (isset($_GET['remove'])) {

    $property_id = (int)$_GET['remove'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM wishlist
         WHERE user_id = ? AND property_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $user_id,
        $property_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: wishlist.php");
    exit;
}


/* Get wishlist properties */
$sql = "
    SELECT
        w.property_id,
        p.title,
        p.price,
        p.city,
        p.state,
        p.listing_type,
        pi.image_path
    FROM wishlist w
    INNER JOIN properties p
        ON w.property_id = p.property_id
    LEFT JOIN property_images pi
        ON p.property_id = pi.property_id
        AND pi.is_main = 1
    WHERE w.user_id = ?
      AND p.approval_status = 'Approved'
      AND p.status = 'Active'
    ORDER BY w.wishlist_id DESC
";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Wishlist - UrbanNest</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
        }

        .wishlist-container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
        }

        .wishlist-container h1 {
            margin-bottom: 30px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .wishlist-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .wishlist-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }

        .wishlist-content {
            padding: 20px;
        }

        .wishlist-content h3 {
            margin: 0 0 10px;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .location {
            color: #666;
            margin-bottom: 8px;
        }

        .listing-type {
            color: #555;
            margin-bottom: 18px;
        }

        .wishlist-actions {
            display: flex;
            gap: 10px;
        }

        .wishlist-actions a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .view-btn {
            background: #222;
            color: white;
        }

        .remove-btn {
            background: #eee;
            color: #c00;
        }

        .empty-wishlist {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 12px;
        }

    </style>

</head>

<body>

<div class="wishlist-container">

    <h1>My Wishlist</h1>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <div class="wishlist-grid">

            <?php while ($property = mysqli_fetch_assoc($result)) { ?>

                <?php
                    if (!empty($property['image_path'])) {
                        $image = "admin/" . ltrim($property['image_path'], "/");
                    } else {
                        $image = "admin/assets/images/no-property.png";
                    }
                ?>

                <div class="wishlist-card">

                    <img
                        src="<?= htmlspecialchars($image) ?>"
                        alt="<?= htmlspecialchars($property['title']) ?>"
                    >

                    <div class="wishlist-content">

                        <h3>
                            <?= htmlspecialchars($property['title']) ?>
                        </h3>

                        <div class="price">
                            ₹<?= number_format($property['price']) ?>
                        </div>

                        <div class="location">
                            <?= htmlspecialchars($property['city']) ?>,
                            <?= htmlspecialchars($property['state']) ?>
                        </div>

                        <div class="listing-type">
                            <?= htmlspecialchars($property['listing_type']) ?>
                        </div>

                        <div class="wishlist-actions">

                            <a
                                href="properties/details.php?id=<?= (int)$property['property_id'] ?>"
                                class="view-btn"
                            >
                                View Property
                            </a>

                            <a
                                href="wishlist.php?remove=<?= (int)$property['property_id'] ?>"
                                class="remove-btn"
                                onclick="return confirm('Remove this property from wishlist?');"
                            >
                                Remove
                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty-wishlist">

            <h2>Your Wishlist is Empty</h2>

            <p>You haven't saved any properties yet.</p>

        </div>

    <?php } ?>

</div>

</body>

</html>

<?php
mysqli_stmt_close($stmt);
?>