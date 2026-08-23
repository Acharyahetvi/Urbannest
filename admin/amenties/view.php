<?php

require_once __DIR__ . '/../../includes/db.php';

$sql = "SELECT * FROM amenities ORDER BY amentiy_id ASC";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Failed to fetch amenities: " . mysqli_error($con));
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>View Amenities - UrbanNest</title>

</head>

<body>

    <h1>Manage Amenities</h1>

    <p>
        <a href="add.php">Add Amenity</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">

        <thead>

            <tr>

                <th>ID</th>
                <th>Amenity Name</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?= (int) $row['amentiy_id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['amentiy_name']) ?>
                        </td>

                        <td>

                            <a href="edit.php?id=<?= (int) $row['amentiy_id'] ?>">
                                Edit
                            </a>

                            |

                            <a
                                href="delete.php?id=<?= (int) $row['amentiy_id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this amenity?');">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="3">
                        No amenities found.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>