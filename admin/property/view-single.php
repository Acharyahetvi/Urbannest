<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../db_connection.php";


// Check Property ID

if (!isset($_GET['id'])) {

    die("Property ID not found.");
}


$id = $_GET['id'];


// Get Single Property

$sql = "SELECT * FROM properties WHERE property_id = '$id'";

$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Query Error: " . mysqli_error($conn));
}


// Check Property

if (mysqli_num_rows($result) == 0) {

    die("Property not found.");
}


$property = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Property Details - UrbanNest</title>

    <link rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

</head>


<body>


    <div class="admin-container">


        <!-- Sidebar -->

        <div class="sidebar">

            <h2>UrbanNest</h2>


            <ul>

                <li>
                    <a href="../view.php">
                        Dashboard
                    </a>
                </li>


                <li>
                    <a href="../user/view.php">
                        Users
                    </a>
                </li>


                <li>
                    <a href="view.php" class="active">
                        Properties
                    </a>
                </li>


                <li>
                    <a href="../category/view.php">
                        Categories
                    </a>
                </li>


                <li>
                    <a href="../sub-category/view.php">
                        Sub-Categories
                    </a>
                </li>


                <li>
                    <a href="../amenity/view.php">
                        Amenities
                    </a>
                </li>


                <li class="logout-menu">

                    <a href="../logout.php"
                        onclick="return confirm('Are you sure you want to logout?');">

                        Logout

                    </a>

                </li>

            </ul>

        </div>



        <!-- Main Content -->

        <div class="main-content">


            <!-- Heading -->

            <div class="page-header">

                <h1>Property Details</h1>

            </div>



            <!-- Property Details -->

            <div class="table-container">

                <table>


                    <tr>

                        <th>Property ID</th>

                        <td>
                            <?php echo $property['property_id']; ?>
                        </td>

                    </tr>



                    <tr>

                        <th>Property Title</th>

                        <td>

                            <?php

                            if (isset($property['title'])) {

                                echo htmlspecialchars($property['title']);
                            } elseif (isset($property['property_title'])) {

                                echo htmlspecialchars($property['property_title']);
                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                    </tr>



                    <tr>

                        <th>Location</th>

                        <td>

                            <?php

                            if (isset($property['location'])) {

                                echo htmlspecialchars($property['location']);
                            } elseif (isset($property['address'])) {

                                echo htmlspecialchars($property['address']);
                            } elseif (isset($property['city'])) {

                                echo htmlspecialchars($property['city']);
                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                    </tr>



                    <tr>

                        <th>Price</th>

                        <td>

                            ₹<?php

                                if (isset($property['price'])) {

                                    echo number_format($property['price']);
                                } else {

                                    echo "0";
                                }

                                ?>

                        </td>

                    </tr>



                    <tr>

                        <th>Category ID</th>

                        <td>

                            <?php

                            if (isset($property['category_id'])) {

                                echo $property['category_id'];
                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                    </tr>



                    <tr>

                        <th>Status</th>

                        <td>

                            <?php

                            if (isset($property['status'])) {

                                echo htmlspecialchars($property['status']);
                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                    </tr>


                </table>


                <!-- Back Button -->

                <br>

                <a href="view.php" class="btn btn-primary">

                    ← Back to Properties

                </a>


            </div>


        </div>


    </div>


</body>

</html>