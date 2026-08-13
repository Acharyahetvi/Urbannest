<?php
include "../connect.php";

if (isset($_POST['save'])) {

    $category_name = $_POST['category_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO categories (category_name, description, status)
            VALUES ('$category_name', '$description', '$status')";

    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Category added successfully!";
    } else {
        echo "Category not added: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Category | UrbanNest</title>

    <link rel="stylesheet" href="../assets/css/subcategory.css">
</head>

<body>

    <div class="subcategory-container">

        <h2>Add Category</h2>


        <form action="" method="POST">

            <div class="form-group">

                <label for="category_name">
                    Category Name
                </label>

                <input
                    type="text"
                    id="category_name"
                    name="category_name"
                    placeholder="Enter category name"
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
                    placeholder="Enter category description"
                ></textarea>

            </div>


            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                >
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>

            </div>


            <button
                type="submit"
                name="save"
                class="submit-btn"
            >
                Save Category
            </button>


        </form>

    </div>

</body>
</html>