<?php 
require_once __DIR__ . "/../../db_connection.php"; 
 
$message = ""; 
 
// Get category ID 
if (!isset($_GET['id'])) { 
    die("Category ID not found."); 
} 
 
$category_id = $_GET['id']; 
 
// Fetch existing category 
$sql = "SELECT * FROM categories WHERE category_id = '$category_id'"; 
$result = mysqli_query($con, $sql); 
 
if (mysqli_num_rows($result) == 0) { 
    die("Category not found."); 
} 
 
$category = mysqli_fetch_assoc($result); 
 
 
// Update category
if (isset($_POST['update'])) { 
 
    $category_name = $_POST['category_name']; 
    $description = $_POST['description']; 
    $status = $_POST['status']; 
 
    // Keep old photo by default
    $photo_name = $category['photo']; 
 
    // If a new photo was selected
    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != "") { 
 
        $photo = $_FILES['photo']['name']; 
        $photo_tmp = $_FILES['photo']['tmp_name']; 
 
        $upload_folder = "../assets/images/category/"; 
 
        // Create unique filename
        $photo_name = time() . "_" . $photo; 
 
        // Move new photo
        move_uploaded_file(
            $photo_tmp,
            $upload_folder . $photo_name
        ); 
    } 
 
    // Update database
    $update_sql = "UPDATE categories SET 
                    category_name = '$category_name', 
                    description = '$description', 
                    status = '$status', 
                    photo = '$photo_name' 
                   WHERE category_id = '$category_id'"; 
 
    if (mysqli_query($con, $update_sql)) { 
 
        $message = "Category updated successfully!"; 
 
        // Fetch updated data
        $result = mysqli_query(
            $con,
            "SELECT * FROM categories WHERE category_id = '$category_id'"
        ); 
 
        $category = mysqli_fetch_assoc($result); 
 
    } else { 
 
        $message = "Error updating category: " . mysqli_error($con); 
    } 
} 
?> 
 
<!DOCTYPE html> 
<html lang="en"> 
 
<head> 
 
    <meta charset="UTF-8"> 
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 
    <title>Edit Category | UrbanNest</title> 
 
    <link rel="stylesheet" href="../assets/css/style.css"> 
 
</head> 
 
<body> 
 
<div class="subcategory-container"> 
 
    <h2>Edit Category</h2> 
 
    <?php if ($message != "") { ?> 
 
        <div class="success-message"> 
            <?php echo $message; ?> 
        </div> 
 
    <?php } ?> 
 
    <form method="POST" enctype="multipart/form-data"> 
 
        <div class="form-group"> 
 
            <label for="category_name">
                Category Name
            </label> 
 
            <input 
                type="text" 
                id="category_name" 
                name="category_name" 
                value="<?php echo htmlspecialchars($category['category_name']); ?>" 
                required 
            > 
 
        </div> 
 
        <div class="form-group"> 
 
            <label for="description">
                Description
            </label> 
 
            <textarea 
                id="description" 
                name="description"
            ><?php echo htmlspecialchars($category['description']); ?></textarea> 
 
        </div> 
 
        <div class="form-group"> 
 
            <label>
                Current Photo
            </label> 
 
            <?php if (!empty($category['photo'])) { ?> 
 
                <br> 
 
                <img 
                    src="../assets/images/category/<?php echo htmlspecialchars($category['photo']); ?>" 
                    alt="Category Photo" 
                    width="150" 
                > 
 
            <?php } else { ?> 
 
                <p>No photo uploaded.</p> 
 
            <?php } ?> 
 
        </div> 
 
        <div class="form-group"> 
 
            <label for="photo">
                Change Photo
            </label> 
 
            <input 
                type="file" 
                id="photo" 
                name="photo" 
                accept="image/*" 
            > 
 
            <small>
                Leave empty if you want to keep the current photo.
            </small> 
 
        </div> 
 
        <div class="form-group"> 
 
            <label for="status">
                Status
            </label> 
 
            <select 
                id="status" 
                name="status" 
                required
            > 
 
                <option 
                    value="Active" 
                    <?php if ($category['status'] == 'Active') echo 'selected'; ?>
                >
                    Active
                </option> 
 
                <option 
                    value="Inactive" 
                    <?php if ($category['status'] == 'Inactive') echo 'selected'; ?>
                >
                    Inactive
                </option> 
 
            </select> 
 
        </div> 
 
        <button 
            type="submit" 
            name="update" 
            class="submit-btn"
        >
            Update Category
        </button> 
 
    </form> 
 
</div> 
 
</body> 
 
</html>