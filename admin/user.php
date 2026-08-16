<?php

include("../config/connection.php");

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['name'];
    echo "<br>";
    echo $row['email'];
    echo "<hr>";
}

?>