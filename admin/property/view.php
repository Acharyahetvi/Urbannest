<?php 

require_once __DIR__ . "/../db_connection.php"; 

// Get all properties 
$sql = "SELECT * FROM properties ORDER BY property_id DESC"; 

$result = mysqli_query($conn, $sql); 

if (!$result) { 
    die("Query Error: " . mysqli_error($conn)); 
} 

?> 

<!DOCTYPE html> 
<html lang="en"> 

<head> 

    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Properties - UrbanNest Admin</title> 

    <link rel="stylesheet" href="/urbannest/admin/assets/css/style.css"> 

</head> 

<body> 

    <div class="admin-container"> 

        <!-- Sidebar --> 
        <div class="sidebar"> 

            <h2>UrbanNest</h2> 

            <ul> 

                <li> 
                    <a href="../view.php">Dashboard</a> 
                </li> 

                <li> 
                    <a href="../user/view.php">Users</a> 
                </li> 

                <li> 
                    <a href="view.php" class="active">Properties</a> 
                </li> 

                <li> 
                    <a href="../category/view.php">Categories</a> 
                </li> 

                <li> 
                    <a href="../sub-category/view.php">Sub-Categories</a> 
                </li> 

                <li> 
                    <a href="../amenity/view.php">Amenities</a> 
                </li>

                <!-- Logout -->
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

            <!-- Header --> 
            <div class="page-header"> 

                <h1>Properties</h1> 

                <a href="add.php" class="btn btn-primary"> 
                    + Add Property 
                </a> 

            </div> 


            <!-- Table --> 
            <div class="table-container"> 

                <table> 

                    <thead> 

                        <tr> 

                            <th>ID</th> 

                            <th>Title</th> 

                            <th>Location</th> 

                            <th>Price</th> 

                            <th>Category</th> 

                            <th>Status</th> 

                            <th>Action</th> 

                        </tr> 

                    </thead> 


                    <tbody> 

                        <?php 

                        if (mysqli_num_rows($result) > 0) { 

                            while ($property = mysqli_fetch_assoc($result)) { 

                                // Location 
                                if (isset($property['location'])) { 
                                    $location = $property['location']; 
                                } elseif (isset($property['address'])) { 
                                    $location = $property['address']; 
                                } elseif (isset($property['city'])) { 
                                    $location = $property['city']; 
                                } else { 
                                    $location = "-"; 
                                } 


                                // Category 
                                if (isset($property['category_id'])) { 
                                    $category = $property['category_id']; 
                                } elseif (isset($property['subcategory_id'])) { 
                                    $category = $property['subcategory_id']; 
                                } else { 
                                    $category = "-"; 
                                } 


                                // Title 
                                if (isset($property['title'])) { 
                                    $title = $property['title']; 
                                } elseif (isset($property['property_title'])) { 
                                    $title = $property['property_title']; 
                                } else { 
                                    $title = "-"; 
                                } 


                                // Price 
                                if (isset($property['price'])) { 
                                    $price = $property['price']; 
                                } else { 
                                    $price = 0; 
                                } 


                                // Status 
                                if (isset($property['status'])) { 
                                    $status = $property['status']; 
                                } else { 
                                    $status = "-"; 
                                } 

                        ?> 

                                <tr> 

                                    <!-- ID --> 
                                    <td> 
                                        <?php echo $property['property_id']; ?> 
                                    </td> 


                                    <!-- Title --> 
                                    <td> 
                                        <?php echo htmlspecialchars($title); ?> 
                                    </td> 


                                    <!-- Location --> 
                                    <td> 
                                        <?php echo htmlspecialchars($location); ?> 
                                    </td> 


                                    <!-- Price --> 
                                    <td> 
                                        ₹<?php echo number_format($price); ?> 
                                    </td> 


                                    <!-- Category --> 
                                    <td> 
                                        <?php echo htmlspecialchars($category); ?> 
                                    </td> 


                                    <!-- Status --> 
                                    <td> 
                                        <?php echo htmlspecialchars($status); ?> 
                                    </td> 


                                    <!-- Action --> 
                                    <td> 

                                        <a 
                                            href="view-single.php?id=<?php echo $property['property_id']; ?>" 
                                            class="btn btn-view"> 
                                            View 
                                        </a> 


                                        <a 
                                            href="edit.php?id=<?php echo $property['property_id']; ?>" 
                                            class="btn btn-edit"> 
                                            Edit 
                                        </a> 


                                        <a 
                                            href="delete.php?id=<?php echo $property['property_id']; ?>" 
                                            class="btn btn-delete" 
                                            onclick="return confirm('Are you sure you want to delete this property?');"> 
                                            Delete 
                                        </a> 

                                    </td> 

                                </tr> 

                            <?php 

                            } 

                        } else { 

                            ?> 

                            <tr> 

                                <td colspan="7" style="text-align:center;"> 
                                    No Properties Found 
                                </td> 

                            </tr> 

                        <?php 

                        } 

                        ?> 

                    </tbody> 

                </table> 

            </div> 

        </div> 

    </div> 

</body> 

</html>