<?php
session_start();

// =====================================
// DATABASE CONNECTION
// =====================================

require_once __DIR__ . "/../includes/db.php";


// =====================================
// CHECK ADMIN LOGIN
// =====================================

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true ||
    !isset($_SESSION['admin_id'])
) {
    header("Location: /urbannest/admin/login.php");
    exit;
}


// =====================================
// GET ADMIN ID
// =====================================

$admin_id = (int) $_SESSION['admin_id'];


// =====================================
// GET ADMIN DETAILS
// =====================================

$sql = "SELECT user_id, full_name, email, phoneno, role_id, status
        FROM users
        WHERE user_id = ?
        AND role_id = 1";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    die("Database query failed: " . mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "i", $admin_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$admin = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// =====================================
// CHECK ADMIN DETAILS
// =====================================

if (!$admin) {
    die("Admin details not found. Please login again.");
}


// =====================================
// CREATE ADMIN INITIALS
// =====================================

$initials = "";

if (!empty($admin['full_name'])) {

    $name_parts = preg_split(
        '/\s+/',
        trim($admin['full_name'])
    );

    foreach ($name_parts as $part) {

        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }
}

$initials = substr($initials, 0, 2);


// =====================================
// ADMIN DETAILS
// =====================================

$full_name = $admin['full_name'] ?? '';
$email = $admin['email'] ?? '';
$phone = $admin['phoneno'] ?? '';
$status = $admin['status'] ?? '';


// Update session details

$_SESSION['admin_name'] = $full_name;
$_SESSION['admin_email'] = $email;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Profile - UrbanNest</title>


    <!-- CSS FILES -->

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/profile.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>


    <!-- HEADER -->

    <?php
    include __DIR__ . "/includes/header.php";
    ?>


    <!-- SIDEBAR -->

    <?php
    include __DIR__ . "/includes/sidebar.php";
    ?>


    <!-- MAIN WRAPPER -->

    <div class="un-main-wrapper">

        <main class="main-content">

            <div class="profile-page">


                <!-- PAGE HEADING -->

                <h1>My Profile</h1>

                <p class="profile-text">
                    Admin account information
                </p>


                <!-- PROFILE CARD -->

                <div class="profile-card">


                    <!-- ADMIN INITIALS -->

                    <div class="profile-image">
                        <?php
                        echo htmlspecialchars($initials);
                        ?>
                    </div>


                    <!-- ADMIN INFORMATION -->

                    <div class="profile-info">


                        <!-- NAME -->

                        <h2>
                            <?php
                            echo htmlspecialchars($full_name);
                            ?>
                        </h2>


                        <!-- EMAIL -->

                        <p>
                            <b>Email:</b>

                            <span>
                                <?php
                                echo htmlspecialchars($email);
                                ?>
                            </span>
                        </p>


                        <!-- PHONE -->

                        <p>
                            <b>Phone:</b>

                            <span>
                                <?php
                                echo htmlspecialchars($phone);
                                ?>
                            </span>
                        </p>


                        <!-- ROLE -->

                        <p>
                            <b>Role:</b>

                            <span>Administrator</span>
                        </p>


                        <!-- STATUS -->

                        <p>
                            <b>Status:</b>

                            <span>
                                <?php
                                echo htmlspecialchars($status);
                                ?>
                            </span>
                        </p>


                        <!-- BACK TO DASHBOARD -->

                        <a href="/urbannest/admin/dashboard.php"
                            class="profile-back-btn">

                            Back to Dashboard

                        </a>


                    </div>

                </div>

            </div>

        </main>


        <!-- FOOTER -->

        <?php
        include __DIR__ . "/includes/footer.php";
        ?>


    </div>

</body>

</html>