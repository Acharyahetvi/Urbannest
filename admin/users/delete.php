<?php

require_once __DIR__ . "/../db_connection.php";


// Check user ID
if (!isset($_GET['id'])) {
    die("User ID not found.");
}

$user_id = $_GET['id'];


// Delete user
$sql = "DELETE FROM users WHERE user_id = '$user_id'";

$result = mysqli_query($conn, $sql);


if ($result) {

    echo "<script>
            alert('User deleted successfully!');
            window.location.href='view.php';
          </script>";
} else {

    echo "Delete Error: " . mysqli_error($conn);
}
