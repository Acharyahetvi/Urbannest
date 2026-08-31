<?php

require_once __DIR__ . "/../db_connection.php";

if (!isset($_GET['id'])) {
    die("User ID not found.");
}

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
                <?php echo $user['full_name']; ?>
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
                        <?php echo $user['full_name']; ?>
                    </strong>
                </div>

                <div class="info-row">
                    <span>Email</span>
                    <strong>
                        <?php echo $user['email']; ?>
                    </strong>
                </div>

                <div class="info-row">
                    <span>Phone</span>
                    <strong>
                        <?php echo $user['phoneno']; ?>
                    </strong>
                </div>

                <div class="info-row">
                    <span>Status</span>
                    <strong class="status">
                        <?php echo $user['status']; ?>
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