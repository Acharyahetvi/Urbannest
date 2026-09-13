<?php

require_once __DIR__ . "/../db_connection.php";


// ==========================================
// IF ID IS GIVEN - SHOW SINGLE USER
// ==========================================

if (isset($_GET['id'])) {

    $user_id = (int) $_GET['id'];

    $sql = "SELECT * FROM users WHERE user_id = $user_id";

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

        <meta name="viewport"
            content="width=device-width, initial-scale=1.0">

        <title>View User - UrbanNest</title>


        <!-- COMMON CSS -->

        <link rel="stylesheet"
            href="/urbannest/admin/assets/css/style.css">


        <!-- USERS CSS -->

        <link rel="stylesheet"
            href="/urbannest/admin/assets/css/users.css">


    </head>

    <body>


        <!-- =====================================
     SINGLE USER DETAILS PAGE
===================================== -->

        <div class="user-details-page">


            <!-- USER DETAILS CARD -->

            <div class="user-card">


                <!-- USER ICON -->

                <div class="user-icon">

                    👤

                </div>


                <!-- USER NAME -->

                <h2>

                    <?php echo htmlspecialchars($user['full_name']); ?>

                </h2>


                <!-- USER ROLE -->

                <p class="role">

                    UrbanNest User

                </p>


                <!-- =====================================
             USER INFORMATION
        ===================================== -->

                <div class="info">


                    <!-- USER ID -->

                    <div class="info-row">

                        <span>User ID</span>

                        <strong>

                            <?php echo $user['user_id']; ?>

                        </strong>

                    </div>


                    <!-- USER NAME -->

                    <div class="info-row">

                        <span>Name</span>

                        <strong>

                            <?php echo htmlspecialchars($user['full_name']); ?>

                        </strong>

                    </div>


                    <!-- USER EMAIL -->

                    <div class="info-row">

                        <span>Email</span>

                        <strong>

                            <?php echo htmlspecialchars($user['email']); ?>

                        </strong>

                    </div>


                    <!-- USER PHONE -->

                    <div class="info-row">

                        <span>Phone</span>

                        <strong>

                            <?php echo htmlspecialchars($user['phoneno']); ?>

                        </strong>

                    </div>


                    <!-- USER STATUS -->

                    <div class="info-row">

                        <span>Status</span>

                        <strong class="status">

                            <?php echo htmlspecialchars($user['status']); ?>

                        </strong>

                    </div>


                </div>


                <!-- BACK TO USERS -->

                <a href="view.php"
                    class="back-btn">

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
// IF ID IS NOT GIVEN - SHOW ALL USERS
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

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>All Users - UrbanNest</title>


    <!-- COMMON CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">


    <!-- USERS CSS -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/users.css">


</head>

<body>


    <!-- =====================================
     ALL USERS PAGE
===================================== -->

    <div class="users-page">


        <!-- =====================================
         PAGE HEADER
    ===================================== -->

        <div class="users-header">


            <h2>

                All Users

            </h2>


            <!-- BACK TO DASHBOARD -->

            <a href="../view.php"
                class="dashboard-btn">

                ← Back to Dashboard

            </a>


        </div>


        <!-- =====================================
         USERS TABLE
    ===================================== -->

        <div class="users-table-wrapper">


            <table class="users-table">


                <!-- TABLE HEADER -->

                <thead>

                    <tr>

                        <th>User ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <!-- TABLE BODY -->

                <tbody>


                    <?php

                    while ($user = mysqli_fetch_assoc($result)) {

                    ?>


                        <tr>


                            <!-- USER ID -->

                            <td>

                                <?php echo $user['user_id']; ?>

                            </td>


                            <!-- USER NAME -->

                            <td>

                                <?php
                                echo htmlspecialchars($user['full_name']);
                                ?>

                            </td>


                            <!-- USER EMAIL -->

                            <td>

                                <?php
                                echo htmlspecialchars($user['email']);
                                ?>

                            </td>


                            <!-- USER PHONE -->

                            <td>

                                <?php
                                echo htmlspecialchars($user['phoneno']);
                                ?>

                            </td>


                            <!-- USER STATUS -->

                            <td>

                                <span class="status-badge">

                                    <?php
                                    echo htmlspecialchars($user['status']);
                                    ?>

                                </span>

                            </td>


                            <!-- ACTION -->

                            <td>


                                <!-- VIEW USER -->

                                <a href="view.php?id=<?php echo $user['user_id']; ?>"
                                    class="view-btn">

                                    View

                                </a>


                            </td>


                        </tr>


                    <?php

                    }

                    ?>


                </tbody>


            </table>


        </div>


    </div>
    ```

</body>

</html>