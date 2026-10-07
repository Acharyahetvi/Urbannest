<?php

/* DATABASE CONNECTION */
require_once __DIR__ . "/../../db_connection.php";

$con = isset($con) ? $con : (isset($conn) ? $conn : null);

if (!$con) {
    die("Database connection failed.");
}


/* =========================================================
   SINGLE USER DETAILS
========================================================= */

if (isset($_GET['id']) && $_GET['id'] !== '') {

    $user_id = intval($_GET['id']);

    $sql = "SELECT user_id, full_name, email, phoneno, status
            FROM users
            WHERE user_id = $user_id";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Query Error: " . mysqli_error($con));
    }

    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        die("User not found.");
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport"
            content="width=device-width, initial-scale=1.0">

        <title>User Details - UrbanNest</title>


        <link rel="stylesheet"
            href="/urbannest/admin/assets/css/bootstrap.min.css">

        <link rel="stylesheet"
            href="/urbannest/admin/assets/css/style.css">

        <link rel="stylesheet"
            href="/urbannest/admin/assets/css/users.css">

        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </head>


    <body>


        <?php include __DIR__ . "/../includes/sidebar.php"; ?>


        <div class="un-main-wrapper">


            <?php include __DIR__ . "/../includes/header.php"; ?>


            <main class="users-page">


                <!-- PAGE HEADER -->

                <div class="users-header">

                    <div>

                        <span class="eyebrow">
                            USER MANAGEMENT
                        </span>

                        <h2>
                            User Details
                        </h2>

                    </div>


                    <a href="/urbannest/admin/users/view.php"
                        class="dashboard-btn">

                        ← Back to Users

                    </a>

                </div>



                <!-- USER DETAILS -->

                <div class="user-details-page">


                    <div class="user-card">


                        <div class="user-icon">

                            <i class="fa-solid fa-user"></i>

                        </div>


                        <h2>

                            <?php echo htmlspecialchars($user['full_name']); ?>

                        </h2>


                        <p class="role">

                            UrbanNest User

                        </p>



                        <div class="info">


                            <!-- USER ID -->

                            <div class="info-row">

                                <span>
                                    User ID
                                </span>

                                <strong>

                                    <?php echo (int)$user['user_id']; ?>

                                </strong>

                            </div>



                            <!-- FULL NAME -->

                            <div class="info-row">

                                <span>
                                    Full Name
                                </span>

                                <strong>

                                    <?php echo htmlspecialchars($user['full_name']); ?>

                                </strong>

                            </div>



                            <!-- EMAIL -->

                            <div class="info-row">

                                <span>
                                    Email
                                </span>

                                <strong>

                                    <?php echo htmlspecialchars($user['email']); ?>

                                </strong>

                            </div>



                            <!-- PHONE -->

                            <div class="info-row">

                                <span>
                                    Phone
                                </span>

                                <strong>

                                    <?php echo htmlspecialchars($user['phoneno']); ?>

                                </strong>

                            </div>



                            <!-- STATUS -->

                            <div class="info-row">

                                <span>
                                    Status
                                </span>

                                <strong>

                                    <span class="status">

                                        <?php echo htmlspecialchars($user['status']); ?>

                                    </span>

                                </strong>

                            </div>


                        </div>



                        <!-- ACTION BUTTONS -->

                        <div style="display:flex; gap:10px; margin-top:25px;">


                            <!-- EDIT -->

                            <a href="/urbannest/admin/users/edit.php?id=<?php echo (int)$user['user_id']; ?>"
                                class="view-btn">

                                Edit ✎

                            </a>



                            <!-- DELETE -->

                            <a href="/urbannest/admin/users/delete.php?id=<?php echo (int)$user['user_id']; ?>"
                                class="view-btn"
                                onclick="return confirm('Are you sure you want to delete this user?');">

                                Delete 🗑

                            </a>



                            <!-- BACK -->

                            <a href="/urbannest/admin/users/view.php"
                                class="back-btn"
                                style="margin-top:0;">

                                ← Back

                            </a>


                        </div>


                    </div>


                </div>


            </main>


            <?php include __DIR__ . "/../includes/footer.php"; ?>


        </div>


        <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>


    </body>

    </html>


<?php

    exit;
}


/* =========================================================
   ALL USERS
========================================================= */

$sql = "SELECT user_id, full_name, email, phoneno, status
        FROM users
        ORDER BY user_id DESC";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

?>


<!DOCTYPE html>
<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>All Users - UrbanNest</title>


    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/users.css">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>


<body>


    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <div class="un-main-wrapper">


        <?php include __DIR__ . "/../includes/header.php"; ?>


        <main class="users-page">


            <!-- PAGE HEADER -->

            <div class="users-header">


                <div>

                    <span class="eyebrow">

                        USER MANAGEMENT

                    </span>


                    <h2>

                        All Users

                    </h2>

                </div>



                <a href="/urbannest/admin/dashboard.php"
                    class="dashboard-btn">

                    ← Back to Dashboard

                </a>


            </div>



            <!-- USERS TABLE -->

            <div class="users-table-wrapper">


                <table class="users-table">


                    <thead>

                        <tr>

                            <th>
                                User ID
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <?php if (mysqli_num_rows($result) > 0) { ?>


                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>


                                <tr>


                                    <!-- USER ID -->

                                    <td>

                                        <?php echo (int)$row['user_id']; ?>

                                    </td>



                                    <!-- NAME -->

                                    <td>

                                        <?php echo htmlspecialchars($row['full_name']); ?>

                                    </td>



                                    <!-- EMAIL -->

                                    <td>

                                        <?php echo htmlspecialchars($row['email']); ?>

                                    </td>



                                    <!-- PHONE -->

                                    <td>

                                        <?php echo htmlspecialchars($row['phoneno']); ?>

                                    </td>



                                    <!-- STATUS -->

                                    <td>

                                        <span class="status">

                                            <?php echo htmlspecialchars($row['status']); ?>

                                        </span>

                                    </td>



                                    <!-- ACTION -->

                                    <td>


                                        <!-- VIEW -->

                                        <a href="/urbannest/admin/users/view.php?id=<?php echo (int)$row['user_id']; ?>"
                                            class="view-btn">

                                            View

                                        </a>



                                        <!-- EDIT -->

                                        <a href="/urbannest/admin/users/edit.php?id=<?php echo (int)$row['user_id']; ?>"
                                            class="view-btn">

                                            Edit

                                        </a>



                                        <!-- DELETE -->

                                        <a href="/urbannest/admin/users/delete.php?id=<?php echo (int)$row['user_id']; ?>"
                                            class="view-btn"
                                            onclick="return confirm('Are you sure you want to delete this user?');">

                                            Delete

                                        </a>


                                    </td>


                                </tr>


                            <?php } ?>


                        <?php } else { ?>


                            <tr>

                                <td colspan="6"
                                    class="no-data">

                                    No users found.

                                </td>

                            </tr>


                        <?php } ?>


                    </tbody>


                </table>


            </div>


        </main>


        <?php include __DIR__ . "/../includes/footer.php"; ?>


    </div>


    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>


</body>

</html>