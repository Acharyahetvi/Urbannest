<?php

require_once __DIR__ . "/../db_connection.php";


// ==========================================
// If ID is given, show single user
// ==========================================

if (isset($_GET['id'])) {

    $user_id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 0) {
        die("User not found.");
    }

    $user = mysqli_fetch_assoc($result);

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>View User - UrbanNest</title>

        <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">

    </head>

    <body>

        <div class="container">

            <div class="user-card">

                <div class="user-icon">
                    👤
                </div>

                <h2>
                    <?php echo htmlspecialchars($user['full_name']); ?>
                </h2>

                <p class="role">
                    UrbanNest User
                </p>


                <div class="info">

                    <div class="info-row">

                        <span>User ID</span>

                        <strong>
                            <?php echo $user['user_id']; ?>
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>Name</span>

                        <strong>
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>Email</span>

                        <strong>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>Phone</span>

                        <strong>
                            <?php echo htmlspecialchars($user['phoneno']); ?>
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>Status</span>

                        <strong class="status">
                            <?php echo htmlspecialchars($user['status']); ?>
                        </strong>

                    </div>

                </div>


                <a href="view.php" class="back-btn">
                    ← Back to Users
                </a>

            </div>

        </div>

    </body>

    </html>

<?php

    exit;
}


// ==========================================
// If ID is NOT given, show all users
// ==========================================

$sql = "SELECT * FROM users";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users - UrbanNest</title>

    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css">

</head>

<body>

    <div class="container">

        <h2>All Users</h2>


        <table border="1" cellpadding="10" cellspacing="0" width="100%">

            <tr>

                <th>User ID</th>

                <th>Name</th>

                <th>Email</th>

                <th>Phone</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php while ($user = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $user['user_id']; ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($user['phoneno']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($user['status']); ?>
                    </td>


                    <td>

                        <a href="view.php?id=<?php echo $user['user_id']; ?>">
                            View
                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>


        <br>

        <a href="../view.php">
            ← Back to Dashboard
        </a>

    </div>

</body>

</html>