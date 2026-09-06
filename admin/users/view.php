<?php

include "../db_connection.php";

$sql = "
    SELECT
        u.user_id,
        u.full_name,
        u.email,
        r.role_name,
        u.status
    FROM users u
    LEFT JOIN roles r
        ON u.role_id = r.role_id
    ORDER BY u.user_id ASC
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Unable to fetch users: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Users | UrbanNest</title>

</head>

<body>

    <h2>Users</h2>

    <p>
        View registered UrbanNest users.
    </p>


    <table border="1">

        <thead>

            <tr>

                <th>User ID</th>

                <th>Name</th>

                <th>Email</th>

                <th>Role</th>

                <th>Status</th>

                <th>Action</th>

            </tr>

        </thead>


        <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($user = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php
                            echo (int) $user["user_id"];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $user["full_name"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $user["email"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $user["role_name"] ?? "Unknown"
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $user["status"]
                            );
                            ?>
                        </td>

                        <td>

                            <a
                                href="profile.php?id=<?php echo (int) $user["user_id"]; ?>"
                            >
                                View Details
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">
                        No users found.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>