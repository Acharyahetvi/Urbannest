<?php

$pageTitle = 'Buy Properties | UrbanNest';
$pageDesc = 'Find your dream home with UrbanNest.';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

?>

<style>
    /* =========================================
   BUY PAGE
========================================= */

    .buy-page {
        background: #f8f7f3;
        min-height: 100vh;
        padding-bottom: 60px;
    }


    /* =========================================
   HERO
========================================= */

    .buy-hero {
        min-height: 390px;
        display: flex;
        align-items: center;

        background:
            linear-gradient(90deg,
                rgba(7, 24, 45, .92),
                rgba(7, 24, 45, .45)),
            url('assets/images/buy-hero.jpg') center/cover no-repeat;

        color: #fff;
        padding: 65px 0;
    }

    .buy-hero-content {
        max-width: 680px;
    }

    .buy-label {
        color: #e0bd55;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .buy-hero h1 {
        font-size: 56px;
        font-weight: 700;
        line-height: 1.15;
        margin: 18px 0;
    }

    .buy-hero h1 em {
        color: #e0bd55;
        font-style: normal;
    }

    .buy-hero p {
        color: #e4e8ed;
        font-size: 16px;
        line-height: 1.8;
        max-width: 550px;
    }


    /* =========================================
   SEARCH CARD
========================================= */

    .buy-search-wrap {
        position: relative;
        margin-top: -45px;
        z-index: 2;

        width: 92%;
        max-width: 1350px;

        margin-left: auto;
        margin-right: auto;
    }

    .buy-search {
        background: #fff;
        border-radius: 18px;
        padding: 25px;

        box-shadow: 0 12px 35px rgba(11, 31, 58, .12);

        border: 1px solid #eee9dc;

        width: 100%;
        box-sizing: border-box;
    }


    /* =========================================
   SEARCH HEADING
========================================= */

    .search-heading {
        margin-bottom: 20px;
    }

    .search-heading h2 {
        color: #0b1f3a;
        font-size: 21px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .search-heading p {
        color: #777;
        font-size: 13px;
        margin: 0;
    }


    /* =========================================
   FORM GRID
========================================= */

    .buy-form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }


    /* =========================================
   FORM FIELDS
========================================= */

    .buy-field label {
        display: block;
        color: #4e5968;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .buy-field select,
    .buy-field input {
        width: 100%;
        height: 48px;

        padding: 0 12px;

        border: 1px solid #e5e5e5;
        border-radius: 9px;

        background: #fafafa;
        color: #0b1f3a;

        font-size: 14px;
        outline: none;

        box-sizing: border-box;
    }

    .buy-field select:focus,
    .buy-field input:focus {
        border-color: #d4af37;
        background: #fff;

        box-shadow:
            0 0 0 3px rgba(212, 175, 55, .12);
    }


    /* =========================================
   SEARCH BUTTON
========================================= */

    .buy-search-btn {
        width: 100%;
        height: 48px;

        margin-top: 22px;

        border: none;
        border-radius: 9px;

        background: #d4af37;
        color: #0b1f3a;

        font-size: 14px;
        font-weight: 700;

        cursor: pointer;

        transition: .25s;
    }

    .buy-search-btn:hover {
        background: #0b1f3a;
        color: #fff;
    }


    /* =========================================
   PAGE CONTENT
========================================= */

    .buy-content {
        padding-top: 50px;
    }

    .buy-section-title {
        color: #0b1f3a;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .buy-section-desc {
        color: #777;
        margin-bottom: 25px;
    }


    /* =========================================
   PROPERTY PLACEHOLDER
========================================= */

    .buy-empty {
        background: #fff;

        border: 1px solid #eee9dc;
        border-radius: 14px;

        padding: 35px;

        text-align: center;

        color: #6b7280;
    }

    .buy-empty i {
        font-size: 35px;
        color: #d4af37;
        margin-bottom: 12px;
    }

    .buy-empty h3 {
        color: #0b1f3a;
        font-size: 19px;
        margin-bottom: 8px;
    }
</style>


<main class="buy-page">


    <!-- =========================================
         HERO
    ========================================== -->

    <section class="buy-hero">

        <div class="container">

            <div class="buy-hero-content">

                <span class="buy-label">
                    UrbanNest · Find Your Place
                </span>

                <h1>
                    Find a home that
                    <em>feels like yours.</em>
                </h1>

                <p>
                    Explore homes, apartments and properties
                    that match your lifestyle, location and budget.
                    Your next chapter starts here.
                </p>

            </div>

        </div>

    </section>


    <!-- =========================================
         SEARCH SECTION
    ========================================== -->

    <section class="buy-search-wrap">

        <div class="buy-search">

            <div class="search-heading">

                <h2>
                    Find Your Perfect Property
                </h2>

                <p>
                    Choose your preferences to explore available properties.
                </p>

            </div>


            <!-- IMPORTANT:
                 purpose=buy means only BUY properties
            -->

            <form action="properties/list.php" method="get">

                <input
                    type="hidden"
                    name="purpose"
                    value="buy">


                <div class="buy-form-grid">


                    <!-- STATE -->

                    <div class="buy-field">

                        <label>State</label>

                        <select name="state">

                            <option value="">
                                All States
                            </option>

                            <option value="Gujarat">
                                Gujarat
                            </option>

                            <option value="Maharashtra">
                                Maharashtra
                            </option>

                        </select>

                    </div>


                    <!-- CITY -->

                    <div class="buy-field">

                        <label>City</label>

                        <select name="city">

                            <option value="">
                                All Cities
                            </option>

                            <option value="Ahmedabad">
                                Ahmedabad
                            </option>

                            <option value="Surat">
                                Surat
                            </option>

                            <option value="Rajkot">
                                Rajkot
                            </option>

                            <option value="Vadodara">
                                Vadodara
                            </option>

                            <option value="Gandhinagar">
                                Gandhinagar
                            </option>

                            <option value="Bhavnagar">
                                Bhavnagar
                            </option>

                            <option value="Mumbai">
                                Mumbai
                            </option>

                        </select>

                    </div>


                    <!-- CATEGORY -->

                    <div class="buy-field">

                        <label>Property Category</label>

                        <select name="category">

                            <option value="">
                                All Categories
                            </option>

                            <option value="Residential">
                                Residential
                            </option>

                            <option value="Commercial">
                                Commercial
                            </option>

                            <option value="Land">
                                Land
                            </option>

                            <option value="Industrial">
                                Industrial
                            </option>

                            <option value="Luxury">
                                Luxury
                            </option>

                        </select>

                    </div>


                    <!-- PROPERTY TYPE -->

                    <div class="buy-field">

                        <label>Property Type</label>

                        <select name="type">

                            <option value="">
                                All Property Types
                            </option>

                            <option value="Apartment">
                                Apartment
                            </option>

                            <option value="Villa">
                                Villa
                            </option>

                            <option value="Bungalow">
                                Bungalow
                            </option>

                            <option value="Duplex">
                                Duplex
                            </option>

                            <option value="Independent House">
                                Independent House
                            </option>

                            <option value="Office">
                                Office
                            </option>

                            <option value="Shop">
                                Shop
                            </option>

                            <option value="Warehouse">
                                Warehouse
                            </option>

                            <option value="Showroom">
                                Showroom
                            </option>

                            <option value="Residential Plot">
                                Residential Plot
                            </option>

                            <option value="Commercial plots">
                                Commercial Plots
                            </option>

                            <option value="Agriculture">
                                Agriculture
                            </option>

                            <option value="Factory">
                                Factory
                            </option>

                            <option value="Industrial Shed">
                                Industrial Shed
                            </option>

                            <option value="Penthouse">
                                Penthouse
                            </option>

                            <option value="Farmhouse">
                                Farmhouse
                            </option>

                            <option value="Luxury Villa">
                                Luxury Villa
                            </option>

                        </select>

                    </div>


                    <!-- BUDGET -->

                    <div class="buy-field">

                        <label>Budget</label>

                        <select name="price">

                            <option value="">
                                Any Budget
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

                    </div>


                    <!-- BEDROOMS -->

                    <div class="buy-field">

                        <label>Bedrooms</label>

                        <select name="beds">

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

                            <option value="5">
                                5+ Bedrooms
                            </option>

                        </select>

                    </div>


                    <!-- BATHROOMS -->

                    <div class="buy-field">

                        <label>Bathrooms</label>

                        <select name="baths">

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

                    </div>


                    <!-- STATUS -->

                    <div class="buy-field">

                        <label>Property Status</label>

                        <select name="status">

                            <option value="">
                                Any Status
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

                    </div>


                    <!-- AREA -->

                    <div class="buy-field">

                        <label>Area</label>

                        <select name="area">

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

                    </div>


                </div>


                <!-- SEARCH BUTTON -->

                <button
                    type="submit"
                    class="buy-search-btn">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    &nbsp; Search Properties

                </button>


            </form>

        </div>

    </section>


    <!-- =========================================
         PROPERTY LISTINGS
    ========================================== -->

    <section class="container buy-content">

        <h2 class="buy-section-title">
            Explore Properties
        </h2>

        <p class="buy-section-desc">
            Discover homes and properties for your next move.
        </p>


        <div class="buy-empty">

            <i class="fa-solid fa-house"></i>

            <h3>
                Find a Property You'll Love
            </h3>

            <p>
                Select your preferred location and budget above,
                then search to explore matching properties.
            </p>

        </div>

    </section>


</main>


<?php

require_once __DIR__ . '/includes/footer.php';

?>