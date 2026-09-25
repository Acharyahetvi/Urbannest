<?php

// Include required files
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/data.php";
require_once __DIR__ . "/../includes/navbar.php";


// Get property ID from URL
$id = $_GET["id"] ?? "";


// Store property details
$property = null;


// Check property ID
if ($id == "UN001") {
    $property = $UN_PROPERTIES[0];
} else if ($id == "UN002") {
    $property = $UN_PROPERTIES[1];
} else if ($id == "UN003") {
    $property = $UN_PROPERTIES[2];
} else if ($id == "UN004") {
    $property = $UN_PROPERTIES[3];
} else if ($id == "UN005") {
    $property = $UN_PROPERTIES[4];
} else if ($id == "UN006") {
    $property = $UN_PROPERTIES[5];
} else if ($id == "UN007") {
    $property = $UN_PROPERTIES[6];
} else if ($id == "UN008") {
    $property = $UN_PROPERTIES[7];
} else if ($id == "UN009") {
    $property = $UN_PROPERTIES[8];
} else if ($id == "UN010") {
    $property = $UN_PROPERTIES[9];
} else if ($id == "UN011") {
    $property = $UN_PROPERTIES[10];
} else if ($id == "UN012") {
    $property = $UN_PROPERTIES[11];
}


// Check if property exists
if ($property == null) {

    echo "<div style='text-align:center; padding:60px;'>";

    echo "<h2>Property Not Found</h2>";

    echo "<p>The property you are looking for does not exist.</p>";

    echo "<a href='list.php'>Back to Properties</a>";

    echo "</div>";

    require_once __DIR__ . "/../includes/footer.php";

    exit;
}

?>


<!-- PROPERTY DETAILS -->

<div style="
    width:900px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.10);
">


    <!-- PROPERTY IMAGE -->

    <img
        src="<?php echo htmlspecialchars($property['image']); ?>"
        alt="<?php echo htmlspecialchars($property['title']); ?>"
        style="
            width:100%;
            height:350px;
            object-fit:cover;
            border-radius:10px;
        ">


    <!-- PROPERTY TITLE -->

    <h1 style="margin-top:25px;">

        <?php echo htmlspecialchars($property["title"]); ?>

    </h1>


    <!-- PROPERTY LOCATION -->

    <p style="color:#666; font-size:17px;">

        📍

        <?php echo htmlspecialchars($property["city"]); ?>,

        <?php echo htmlspecialchars($property["state"]); ?>

    </p>


    <!-- PROPERTY PRICE -->

    <h2 style="color:#d4af37;">

        ₹ <?php echo number_format($property["price"]); ?>

        <?php

        if ($property["purpose"] == "rent") {

            echo " / Month";
        }

        ?>

    </h2>


    <hr>


    <!-- PROPERTY INFORMATION -->

    <h3>Property Information</h3>


    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
        margin-top:20px;
    ">


        <!-- Category -->

        <div>

            <strong>Category:</strong>

            <?php echo htmlspecialchars($property["category"]); ?>

        </div>


        <!-- Property Type -->

        <div>

            <strong>Property Type:</strong>

            <?php echo htmlspecialchars($property["type"]); ?>

        </div>


        <!-- Status -->

        <div>

            <strong>Status:</strong>

            <?php echo htmlspecialchars($property["status"]); ?>

        </div>


        <!-- Area -->

        <div>

            <strong>Area:</strong>

            <?php echo number_format($property["area"]); ?> Sq Ft

        </div>


        <!-- Bedrooms -->

        <div>

            <strong>Bedrooms:</strong>

            <?php

            if ($property["bedrooms"] > 0) {

                echo $property["bedrooms"];
            } else {

                echo "N/A";
            }

            ?>

        </div>


        <!-- Bathrooms -->

        <div>

            <strong>Bathrooms:</strong>

            <?php

            if ($property["bathrooms"] > 0) {

                echo $property["bathrooms"];
            } else {

                echo "N/A";
            }

            ?>

        </div>


        <!-- City -->

        <div>

            <strong>City:</strong>

            <?php echo htmlspecialchars($property["city"]); ?>

        </div>


        <!-- State -->

        <div>

            <strong>State:</strong>

            <?php echo htmlspecialchars($property["state"]); ?>

        </div>


    </div>


    <hr style="margin-top:30px;">


    <!-- USER REVIEW SECTION -->

    <h2>User Reviews</h2>


    <div style="
        margin-top:20px;
        padding:25px;
        background:#f8f8f8;
        border-radius:10px;
    ">


        <h4>Rate this Property</h4>


        <form method="post">


            <!-- Rating -->

            <div style="margin-top:15px;">

                <label>
                    <strong>Rating</strong>
                </label>

                <br>

                <select
                    name="rating"
                    required
                    style="
                        width:100%;
                        padding:10px;
                        margin-top:8px;
                        border:1px solid #ccc;
                        border-radius:5px;
                    ">

                    <option value="">Select Rating</option>

                    <option value="5">★★★★★ - 5 Stars</option>

                    <option value="4">★★★★☆ - 4 Stars</option>

                    <option value="3">★★★☆☆ - 3 Stars</option>

                    <option value="2">★★☆☆☆ - 2 Stars</option>

                    <option value="1">★☆☆☆☆ - 1 Star</option>

                </select>

            </div>


            <!-- Review Text -->

            <div style="margin-top:15px;">

                <label>
                    <strong>Your Review</strong>
                </label>

                <br>

                <textarea
                    name="review"
                    rows="4"
                    placeholder="Write your review..."
                    required
                    style="
                        width:100%;
                        padding:10px;
                        margin-top:8px;
                        border:1px solid #ccc;
                        border-radius:5px;
                    "></textarea>

            </div>


            <!-- Submit Button -->

            <button
                type="submit"
                name="submit_review"
                style="
                    margin-top:15px;
                    padding:10px 25px;
                    background:#0b1f3a;
                    color:white;
                    border:none;
                    border-radius:5px;
                    cursor:pointer;
                ">

                Submit Review

            </button>


        </form>

    </div>


    <!-- BACK BUTTON -->

    <div style="margin-top:30px;">

        <a
            href="list.php"
            style="
                background:#0b1f3a;
                color:white;
                padding:10px 20px;
                text-decoration:none;
                border-radius:5px;
            ">

            ← Back to Properties

        </a>

    </div>


</div>


<?php

// Include footer
require_once __DIR__ . "/../includes/footer.php";

?>