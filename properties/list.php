<?php

$pageTitle = "Properties - UrbanNest";
$pageDesc = "Find properties in Gujarat and Mumbai.";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/data.php";
require_once __DIR__ . "/../includes/navbar.php";


/* =========================
   GET SEARCH DATA
========================= */

$city = $_GET["city"] ?? "";
$state = $_GET["state"] ?? "";
$category = $_GET["category"] ?? "";
$type = $_GET["type"] ?? "";
$purpose = $_GET["purpose"] ?? "";

$beds = $_GET["beds"] ?? "";
$baths = $_GET["baths"] ?? "";
$price = $_GET["price"] ?? "";
$area = $_GET["area"] ?? "";
$status = $_GET["status"] ?? "";


/* =========================
   DEFAULT PROPERTIES
========================= */

$properties = [];


/* =========================
   FILTER PROPERTIES
========================= */

foreach ($UN_PROPERTIES as $property) {

    $match = true;


    /* STATE */

    if ($state != "" && $property["state"] != $state) {

        $match = false;
    }


    /* CITY */

    if ($city != "" && $property["city"] != $city) {

        $match = false;
    }


    /* CATEGORY */

    if ($category != "" && $property["category"] != $category) {

        $match = false;
    }


    /* PROPERTY TYPE */

    if ($type != "" && $property["type"] != $type) {

        $match = false;
    }


    /* PURPOSE */

    if ($purpose != "" && $property["purpose"] != $purpose) {

        $match = false;
    }


    /* BEDROOMS */

    if ($beds != "" && $property["bedrooms"] < $beds) {

        $match = false;
    }


    /* BATHROOMS */

    if ($baths != "" && $property["bathrooms"] < $baths) {

        $match = false;
    }


    /* PROPERTY STATUS */

    if ($status != "" && $property["status"] != $status) {

        $match = false;
    }


    /* AREA */

    if ($area != "") {

        if ($area == "500-1500") {

            if ($property["area"] < 500 || $property["area"] > 1500) {

                $match = false;
            }
        } elseif ($area == "1500-3000") {

            if ($property["area"] < 1500 || $property["area"] > 3000) {

                $match = false;
            }
        } elseif ($area == "3000-6000") {

            if ($property["area"] < 3000 || $property["area"] > 6000) {

                $match = false;
            }
        } elseif ($area == "6000-plus") {

            if ($property["area"] < 6000) {

                $match = false;
            }
        }
    }


    /* PRICE */

    if ($price != "") {

        if ($price == "under-500k") {

            if ($property["price"] >= 5000000) {

                $match = false;
            }
        } elseif ($price == "500k-1m") {

            if (
                $property["price"] < 5000000 ||
                $property["price"] > 10000000
            ) {

                $match = false;
            }
        } elseif ($price == "1m-5m") {

            if (
                $property["price"] < 10000000 ||
                $property["price"] > 50000000
            ) {

                $match = false;
            }
        } elseif ($price == "5m-10m") {

            if (
                $property["price"] < 50000000 ||
                $property["price"] > 100000000
            ) {

                $match = false;
            }
        } elseif ($price == "10m-plus") {

            if ($property["price"] < 100000000) {

                $match = false;
            }
        }
    }


    /* ADD MATCHING PROPERTY */

    if ($match == true) {

        $properties[] = $property;
    }
}

?>


<!-- =========================
     PAGE HEADER
========================= -->

<section class="un-page-head">

    <div class="container">

        <h1>
            Explore Properties
        </h1>

        <p>
            Find properties in Gujarat and Mumbai.
        </p>

    </div>

</section>


<!-- =========================
     PROPERTY SECTION
========================= -->

<section class="section-pad-sm">

    <div class="container">

        <div class="row">


            <!-- =====================
                 FILTER
            ====================== -->

            <div class="col-lg-3">

                <div class="un-filter">

                    <h4>
                        Search Property
                    </h4>


                    <form
                        action="list.php"
                        method="get">


                        <!-- STATE -->

                        <label class="form-label">
                            State
                        </label>

                        <select
                            name="state"
                            class="form-select mb-3">

                            <option value="">
                                Select State
                            </option>

                            <option
                                value="Gujarat"
                                <?= $state == "Gujarat" ? "selected" : "" ?>>
                                Gujarat
                            </option>

                            <option
                                value="Maharashtra"
                                <?= $state == "Maharashtra" ? "selected" : "" ?>>
                                Maharashtra
                            </option>

                        </select>


                        <!-- CITY -->

                        <label class="form-label">
                            City
                        </label>

                        <select
                            name="city"
                            class="form-select mb-3">

                            <option value="">
                                Select City
                            </option>

                            <option
                                value="Ahmedabad"
                                <?= $city == "Ahmedabad" ? "selected" : "" ?>>
                                Ahmedabad
                            </option>

                            <option
                                value="Surat"
                                <?= $city == "Surat" ? "selected" : "" ?>>
                                Surat
                            </option>

                            <option
                                value="Vadodara"
                                <?= $city == "Vadodara" ? "selected" : "" ?>>
                                Vadodara
                            </option>

                            <option
                                value="Rajkot"
                                <?= $city == "Rajkot" ? "selected" : "" ?>>
                                Rajkot
                            </option>

                            <option
                                value="Gandhinagar"
                                <?= $city == "Gandhinagar" ? "selected" : "" ?>>
                                Gandhinagar
                            </option>

                            <option
                                value="Bhavnagar"
                                <?= $city == "Bhavnagar" ? "selected" : "" ?>>
                                Bhavnagar
                            </option>

                            <option
                                value="Mumbai"
                                <?= $city == "Mumbai" ? "selected" : "" ?>>
                                Mumbai
                            </option>

                        </select>


                        <!-- CATEGORY -->

                        <label class="form-label">
                            Property Category
                        </label>

                        <select
                            name="category"
                            class="form-select mb-3">

                            <option value="">
                                Select Category
                            </option>

                            <option
                                value="Residential"
                                <?= $category == "Residential" ? "selected" : "" ?>>
                                Residential
                            </option>

                            <option
                                value="Commercial"
                                <?= $category == "Commercial" ? "selected" : "" ?>>
                                Commercial
                            </option>

                            <option
                                value="Land"
                                <?= $category == "Land" ? "selected" : "" ?>>
                                Land
                            </option>

                            <option
                                value="Industrial"
                                <?= $category == "Industrial" ? "selected" : "" ?>>
                                Industrial
                            </option>

                            <option
                                value="Luxury"
                                <?= $category == "Luxury" ? "selected" : "" ?>>
                                Luxury
                            </option>

                        </select>


                        <!-- PROPERTY TYPE -->

                        <label class="form-label">
                            Property Type
                        </label>

                        <select
                            name="type"
                            class="form-select mb-3">

                            <option value="">
                                Select Property Type
                            </option>

                            <option
                                value="Apartment"
                                <?= $type == "Apartment" ? "selected" : "" ?>>
                                Apartment
                            </option>

                            <option
                                value="Villa"
                                <?= $type == "Villa" ? "selected" : "" ?>>
                                Villa
                            </option>

                            <option
                                value="Independent House"
                                <?= $type == "Independent House" ? "selected" : "" ?>>
                                Independent House
                            </option>

                            <option
                                value="Office"
                                <?= $type == "Office" ? "selected" : "" ?>>
                                Office
                            </option>

                            <option
                                value="Residential Plot"
                                <?= $type == "Residential Plot" ? "selected" : "" ?>>
                                Residential Plot
                            </option>

                        </select>


                        <!-- PURPOSE -->

                        <label class="form-label">
                            Property For
                        </label>

                        <select
                            name="purpose"
                            class="form-select mb-3">

                            <option value="">
                                Buy / Rent / Sell
                            </option>

                            <option
                                value="buy"
                                <?= $purpose == "buy" ? "selected" : "" ?>>
                                Buy
                            </option>

                            <option
                                value="rent"
                                <?= $purpose == "rent" ? "selected" : "" ?>>
                                Rent
                            </option>

                            <option
                                value="sell"
                                <?= $purpose == "sell" ? "selected" : "" ?>>
                                Sell
                            </option>

                        </select>


                        <!-- BEDROOMS -->

                        <label class="form-label">
                            Bedrooms
                        </label>

                        <select
                            name="beds"
                            class="form-select mb-3">

                            <option value="">
                                Any
                            </option>

                            <option value="1">
                                1+ Bedrooms
                            </option>

                            <option value="2">
                                2+ Bedrooms
                            </option>

                            <option value="3">
                                3+ Bedrooms
                            </option>

                            <option value="4">
                                4+ Bedrooms
                            </option>

                        </select>


                        <!-- BATHROOMS -->

                        <label class="form-label">
                            Bathrooms
                        </label>

                        <select
                            name="baths"
                            class="form-select mb-3">

                            <option value="">
                                Any
                            </option>

                            <option value="1">
                                1+ Bathrooms
                            </option>

                            <option value="2">
                                2+ Bathrooms
                            </option>

                            <option value="3">
                                3+ Bathrooms
                            </option>

                            <option value="4">
                                4+ Bathrooms
                            </option>

                        </select>


                        <!-- STATUS -->

                        <label class="form-label">
                            Property Status
                        </label>

                        <select
                            name="status"
                            class="form-select mb-3">

                            <option value="">
                                Any Status
                            </option>

                            <option value="For Sale">
                                For Sale
                            </option>

                            <option value="For Rent">
                                For Rent
                            </option>

                            <option value="Ready to Move">
                                Ready to Move
                            </option>

                            <option value="Under Construction">
                                Under Construction
                            </option>

                            <option value="New Launch">
                                New Launch
                            </option>

                        </select>


                        <!-- AREA -->

                        <label class="form-label">
                            Area
                        </label>

                        <select
                            name="area"
                            class="form-select mb-3">

                            <option value="">
                                Any Area
                            </option>

                            <option value="500-1500">
                                500–1,500 sqft
                            </option>

                            <option value="1500-3000">
                                1,500–3,000 sqft
                            </option>

                            <option value="3000-6000">
                                3,000–6,000 sqft
                            </option>

                            <option value="6000-plus">
                                6,000+ sqft
                            </option>

                        </select>


                        <!-- PRICE -->

                        <label class="form-label">
                            Price
                        </label>

                        <select
                            name="price"
                            class="form-select mb-3">

                            <option value="">
                                Any Price
                            </option>

                            <option value="under-500k">
                                Under ₹50 Lakh
                            </option>

                            <option value="500k-1m">
                                ₹50 Lakh – ₹1 Crore
                            </option>

                            <option value="1m-5m">
                                ₹1 Crore – ₹5 Crore
                            </option>

                            <option value="5m-10m">
                                ₹5 Crore – ₹10 Crore
                            </option>

                            <option value="10m-plus">
                                ₹10 Crore+
                            </option>

                        </select>


                        <!-- SEARCH BUTTON -->

                        <button
                            type="submit"
                            class="btn btn-navy w-100">

                            Search Properties

                        </button>


                        <!-- RESET -->

                        <a
                            href="list.php"
                            class="btn btn-outline-navy w-100 mt-2">

                            Reset

                        </a>

                    </form>

                </div>

            </div>


            <!-- =====================
                 PROPERTIES
            ====================== -->

            <div class="col-lg-9">

                <div class="mb-4">

                    <h3>
                        Available Properties
                    </h3>

                    <p class="text-muted">

                        Properties found:
                        <?= count($properties) ?>

                    </p>

                </div>


                <div class="row g-4">


                    <?php if (count($properties) > 0) { ?>


                        <?php foreach ($properties as $property) { ?>


                            <div class="col-md-6">

                                <div class="card shadow-sm h-100">


                                    <!-- IMAGE -->

                                    <img
                                        src="<?= $property["image"] ?>"
                                        class="card-img-top"
                                        style="
                                            height:220px;
                                            object-fit:cover;
                                        "
                                        alt="<?= $property["title"] ?>">


                                    <div class="card-body">


                                        <!-- CATEGORY -->

                                        <p class="text-muted mb-1">

                                            <?= $property["category"] ?>

                                        </p>


                                        <!-- TITLE -->

                                        <h5>

                                            <?= $property["title"] ?>

                                        </h5>


                                        <!-- LOCATION -->

                                        <p class="text-muted">

                                            <i class="fa-solid fa-location-dot"></i>

                                            <?= $property["city"] ?>,
                                            <?= $property["state"] ?>

                                        </p>


                                        <!-- PRICE -->

                                        <h5>

                                            ₹<?= number_format($property["price"]) ?>

                                            <?php

                                            if ($property["purpose"] == "rent") {

                                                echo " / month";
                                            }

                                            ?>

                                        </h5>


                                        <!-- DETAILS -->

                                        <p class="text-muted">

                                            <?= $property["bedrooms"] ?>
                                            Bedrooms

                                            &nbsp; | &nbsp;

                                            <?= $property["bathrooms"] ?>
                                            Bathrooms

                                            &nbsp; | &nbsp;

                                            <?= number_format($property["area"]) ?>
                                            sqft

                                        </p>


                                        <!-- STATUS -->

                                        <p>

                                            <strong>
                                                <?= $property["status"] ?>
                                            </strong>

                                        </p>


                                        <!-- DETAILS BUTTON -->

                                        <a
                                            href="details.php?id=<?= $property["id"] ?>"
                                            class="btn btn-outline-navy">

                                            View Details

                                        </a>


                                    </div>

                                </div>

                            </div>


                        <?php } ?>


                    <?php } else { ?>


                        <!-- NO PROPERTY -->

                        <div class="col-12">

                            <div class="alert alert-warning">

                                No properties found for the selected filters.

                            </div>

                        </div>


                    <?php } ?>


                </div>

            </div>

        </div>

    </div>

</section>


<?php

require_once __DIR__ . "/../includes/footer.php";

?>