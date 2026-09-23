<?php

require_once __DIR__ . "/../db_connection.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("User ID not found.");
}

$user_id = (int) $_GET['id'];

/* =========================
   Get basic user information
   ========================= */

$sql = "
    SELECT 
        u.*,
        r.role_name
    FROM users u
    LEFT JOIN roles r 
        ON u.role_id = r.role_id
    WHERE u.user_id = ?
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Query Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* =========================
   Get role-specific profile
   ========================= */

$agent = null;
$owner = null;

if ($user['role_name'] === 'Agent') {

    $sql = "
        SELECT *
        FROM agent_profiles
        WHERE user_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $agent = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
    }
} elseif ($user['role_name'] === 'Owner') {

    $sql = "
        SELECT *
        FROM owner_profiles
        WHERE user_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $owner = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
    }
}

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

            <i class="fa-solid fa-user"></i>

            <h2>
                <?php echo $user['full_name']; ?>
            </h2>

            <p class="role">
                <?php echo htmlspecialchars($user['role_name']); ?>
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

                <?php if ($agent): ?>

                    <h3>Agent Information</h3>

                    <div class="info-row">
                        <span>Agency</span>
                        <strong>
                            <?php echo htmlspecialchars($agent['agency_name']); ?>
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Experience</span>
                        <strong>
                            <?php echo htmlspecialchars($agent['experience']); ?>
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>License Number</span>
                        <strong>
                            <?php echo htmlspecialchars($agent['license_number']); ?>
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Bio</span>
                        <strong>
                            <?php echo htmlspecialchars($agent['bio']); ?>
                        </strong>
                    </div>

                <?php elseif ($owner): ?>

                    <h3>Owner Information</h3>

                    <div class="info-row">
                        <span>Property Count</span>
                        <strong>
                            <?php echo htmlspecialchars($owner['property_count']); ?>
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Bio</span>
                        <strong>
                            <?php echo htmlspecialchars($owner['bio']); ?>
                        </strong>
                    </div>

                <?php endif; ?>

            </div>

            <a href="view.php" class="back-btn">
                ← Back to Users
            </a>

        </div>

    </div>

</body>

</html>