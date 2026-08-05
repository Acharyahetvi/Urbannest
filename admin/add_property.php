<?php
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="assest/css/admin.css">
    </head>

<body>

<?php include '../includes/header.php'; ?>

<div class="admin-container">

    <h1>Add New Property</h1>

    <form action="" method="POST" enctype="multipart/form-data">

        <!-- PROPERTY INFORMATION -->
        <div class="form-card">

            <h2>Property Information</h2>

            <div class="form-group">
                <label>Property Title</label>
                <input type="text" name="title">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>

        </div>

        <!-- CATEGORY -->
        <div class="form-card">

            <h2>Category</h2>

            <div class="form-row">

                <div class="form-group">
                    <label>Category</label>

                    <select name="category">
                        <option value="">Select Category</option>
                        <option value="1">Residential</option>
                        <option value="2">Commercial</option>
                    </select>

                </div>

                <div class="form-group">
                    <label>Sub Category</label>

                    <select name="subcategory">
                        <option value="">Select Sub Category</option>
                        <option value="1">Apartment</option>
                        <option value="2">Villa</option>
                        <option value="3">Office</option>
                    </select>

                </div>

            </div>

        </div>

        <!-- PROPERTY DETAILS -->
        <div class="form-card">

            <h2>Property Details</h2>

            <div class="form-row">

                <div class="form-group">
                    <label>Listing Type</label>

                    <select name="listing_type">
                        <option value="Sale">Sale</option>
                        <option value="Rent">Rent</option>
                    </select>

                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>Area (Sq. Ft.)</label>
                    <input type="number" name="area_sqft">
                </div>

                <div class="form-group">
                    <label>Bedrooms</label>
                    <input type="number" name="bedrooms">
                </div>

                <div class="form-group">
                    <label>Bathrooms</label>
                    <input type="number" name="bathrooms">
                </div>

            </div>

            <div class="form-group">

                <label>Furnishing</label>

                <select name="furnishing">
                    <option value="Fully Furnished">Fully Furnished</option>
                    <option value="Semi Furnished">Semi Furnished</option>
                    <option value="Unfurnished">Unfurnished</option>
                </select>

            </div>

        </div>

        <!-- LOCATION -->
        <div class="form-card">

            <h2>Location</h2>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address"></textarea>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city">
                </div>

                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state">
                </div>

            </div>

        </div>

        <!-- AMENITIES -->
        <div class="form-card">

            <h2>Amenities</h2>

            <div class="checkbox-grid">

                <label><input type="checkbox" name="amenities[]" value="1"> Parking</label>
                <label><input type="checkbox" name="amenities[]" value="2"> Lift</label>
                <label><input type="checkbox" name="amenities[]" value="3"> Garden</label>
                <label><input type="checkbox" name="amenities[]" value="4"> Gym</label>
                <label><input type="checkbox" name="amenities[]" value="5"> Swimming Pool</label>
                <label><input type="checkbox" name="amenities[]" value="6"> CCTV</label>

            </div>

        </div>

        <!-- IMAGES -->
        <div class="form-card">

            <h2>Property Images</h2>

            <div class="form-group">
                <label>Main Image</label>
                <input type="file" name="main_image">
            </div>

            <div class="form-group">
                <label>Other Images</label>
                <input type="file" name="property_images[]" multiple>
            </div>

        </div>

        <button type="submit" class="btn-save" name="save_property">
            Save Property
        </button>

    </form>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>