<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$admin_user_id = (int) ($_SESSION['user_id'] ?? 0);

if ($admin_user_id < 1) {
    header("Location: /urbannest/auth/login.php");
    exit;
}

$admin_auth_stmt = mysqli_prepare(
    $con,
    "SELECT role_id
     FROM users
     WHERE user_id = ?
     AND status = 'Active'
     LIMIT 1"
);

if (!$admin_auth_stmt) {
    http_response_code(500);
    exit("Admin authorization check failed.");
}

mysqli_stmt_bind_param($admin_auth_stmt, "i", $admin_user_id);

if (!mysqli_stmt_execute($admin_auth_stmt)) {
    mysqli_stmt_close($admin_auth_stmt);
    http_response_code(500);
    exit("Admin authorization check failed.");
}

$admin_auth_result = mysqli_stmt_get_result($admin_auth_stmt);
$admin_auth_user = mysqli_fetch_assoc($admin_auth_result);
mysqli_stmt_close($admin_auth_stmt);

if (!$admin_auth_user || (int) $admin_auth_user['role_id'] !== 1) {
    header("Location: /urbannest/auth/login.php");
    exit;
}
