<?php

require_once __DIR__ . "/../../db_connection.php";

$con = isset($con) ? $con : $conn;

if (!$con) {
    die("Database connection failed.");
}


/* CHECK USER ID */

if (!isset($_GET['id']) || $_GET['id'] == '') {
    die("User ID not found.");
}

$user_id = intval($_GET['id']);


/* UPDATE USER */

if (isset($_POST['update_user'])) {

    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phoneno = mysqli_real_escape_string($con, $_POST['phoneno']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $sql = "UPDATE users SET
            full_name = '$full_name',
            email = '$email',
            phoneno = '$phoneno',
            status = '$status'
            WHERE user_id = $user_id";

    $result = mysqli_query($con, $sql);

    if ($result) {

        header("Location: view.php?id=$user_id");
        exit;
    } else {

        $error = "Update Error: " . mysqli_error($con);
    }
}


/* GET USER */

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

    <title>Edit User - UrbanNest</title>


    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/users.css">

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/edit-user.css">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>


<body>


    <?php include __DIR__ . "/../includes/sidebar.php"; ?>


    <div class="un-main-wrapper">


        <?php include __DIR__ . "/../includes/header.php"; ?>


        <main class="users-page">


            <!-- HEADER -->

            <div class="users-header">

                <div>

                    <span class="eyebrow">
                        USER MANAGEMENT
                    </span>

                    <h2>
                        Edit User
                    </h2>

                </div>


                <a href="view.php"
                    class="dashboard-btn">

                    ← Back to Users

                </a>

            </div>



            <!-- EDIT FORM -->

            <div class="edit-user-wrapper">

                <div class="edit-user-card">


                    <div class="edit-user-icon">

                        <i class="fa-solid fa-user-pen"></i>

                    </div>


                    <h3>
                        Edit User Information
                    </h3>


                    <?php if (isset($error)) { ?>

                        <div class="edit-error">

                            <?php echo htmlspecialchars($error); ?>

                        </div>

                    <?php } ?>


                    <form method="POST">


                        <!-- USER ID -->

                        <div class="edit-form-group">

                            <label>
                                User ID
                            </label>

                            <input type="text"
                                value="<?php echo $user['user_id']; ?>"
                                readonly>

                        </div>


                        <!-- FULL NAME -->

                        <div class="edit-form-group">

                            <label>
                                Full Name
                            </label>

                            <input type="text"
                                name="full_name"
                                value="<?php echo htmlspecialchars($user['full_name']); ?>"
                                required>

                        </div>


                        <!-- EMAIL -->

                        <div class="edit-form-group">

                            <label>
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                required>

                        </div>


                        <!-- PHONE -->

                        <div class="edit-form-group">

                            <label>
                                Phone
                            </label>

                            <input type="text"
                                name="phoneno"
                                value="<?php echo htmlspecialchars($user['phoneno']); ?>">

                        </div>


                        <!-- STATUS -->

                        <div class="edit-form-group">

                            <label>
                                Status
                            </label>

                            <select name="status">

                                <option value="Active"
                                    <?php
                                    if ($user['status'] == 'Active') {
                                        echo 'selected';
                                    }
                                    ?>>
                                    Active
                                </option>


                                <option value="Inactive"
                                    <?php
                                    if ($user['status'] == 'Inactive') {
                                        echo 'selected';
                                    }
                                    ?>>
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <!-- BUTTONS -->

                        <div class="edit-actions">

                            <button type="submit"
                                name="update_user"
                                class="save-user-btn">

                                <i class="fa-solid fa-check"></i>
                                Save Changes

                            </button>


                            <a href="view.php?id=<?php echo $user['user_id']; ?>"
                                class="cancel-user-btn">

                                Cancel

                            </a>

                        </div>


                    </form>


                </div>

            </div>


        </main>


        <?php include __DIR__ . "/../includes/footer.php"; ?>


    </div>


    <script src="/urbannest/admin/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>