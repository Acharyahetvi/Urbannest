<?php
include "../connect.php";

$message = "";

if (isset($_POST["add_category"])) {

    $category_id = $_POST["category_id"];
    $subcategory_name = $_POST["subcategory_name"];
    $description = $_POST["description"];
    $status = $_POST['status'];


    $sql = "INSERT INTO sub_categories (category_id, subcategory_name, description, status) VALUES ('$category_id', '$subcategory_name', '$description','$status')";
    //    echo $sql;
    //    exit();
    if (mysqli_query($con, $sql)) {
        $message = "Category added successfully";
    } else {
        $message = "Error adding category: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add  Sub Category</title>
      <link rel="stylesheet" href="../assets/css/subcategory.css">
</head>

<body>
    <div class="subcategory-container">

        <h2>Add Subcategory</h2>

        <?php if ($message != "") { ?>
            <div class="success-message">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label for="category">Select Category</label>

                <select name="category_id" id="category" required>
                    <option value="">-- Select Category --</option>

                    <?php
                    $fetch_category = mysqli_query($con, "SELECT * FROM categories");

                    while ($row = mysqli_fetch_array($fetch_category)) {
                        echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Subcategory Name</label>

                <input
                    type="text"
                    name="subcategory_name"
                    id="subcategory_name"
                    placeholder="Enter subcategory name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea
                    name="description"
                    id="description"
                    placeholder="Enter description"
                    required
                ></textarea>
            </div>
            
            <div class="form-group">
            <label for="status">Status</label>

            <select name="status" id="status" required>
                <option value="">-- Select Status --</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            </div>

            <button type="submit" name="add_category" class="submit-btn">
                Add Subcategory
            </button>

        </form>

    </div>

</body>

</html>