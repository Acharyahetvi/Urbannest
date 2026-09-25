<?php

$pageTitle = "Sell Property | UrbanNest";
$pageDesc = "Sell your property with UrbanNest.";

require_once __DIR__ . "/db_connection.php";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

?>

<style>
    /* ================================
   SELL PAGE
================================ */

    .sell-page {
        background: #f8f7f3;
        min-height: 100vh;
        padding-bottom: 60px;
    }


    /* ================================
   HERO
================================ */

    .sell-hero {
        min-height: 390px;

        display: flex;
        align-items: center;

        background:
            linear-gradient(90deg,
                rgba(7, 24, 45, 0.92),
                rgba(7, 24, 45, 0.45)),
            url("assets/images/sell-hero.jpg") center/cover no-repeat;

        color: white;
        padding: 65px 0;
    }

    .sell-hero-content {
        max-width: 680px;
    }

    .sell-label {
        color: #e0bd55;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .sell-hero h1 {
        font-size: 52px;
        font-weight: 700;
        line-height: 1.15;
        margin: 18px 0;
    }

    .sell-hero h1 em {
        color: #e0bd55;
        font-style: normal;
    }

    .sell-hero p {
        color: #e4e8ed;
        font-size: 16px;
        line-height: 1.8;
        max-width: 550px;
    }


    /* ================================
   SEARCH BOX
================================ */

    .sell-search-wrap {
        position: relative;
        margin-top: -45px;
        z-index: 2;

        width: 92%;
        max-width: 1350px;

        margin-left: auto;
        margin-right: auto;
    }

    .sell-search {
        background: white;

        border-radius: 18px;

        padding: 25px;

        box-shadow:
            0 12px 35px rgba(11, 31, 58, 0.12);

        border: 1px solid #eee9dc;

        width: 100%;
        box-sizing: border-box;
    }


    /* ================================
   HEADING
================================ */

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


    /* ================================
   FORM
================================ */

    .sell-form-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 14px;
    }

    .sell-field label {
        display: block;

        color: #4e5968;

        font-size: 12px;
        font-weight: 700;

        margin-bottom: 7px;
    }

    .sell-field select,
    .sell-field input {
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

    .sell-field select:focus,
    .sell-field input:focus {
        border-color: #d4af37;

        background: white;

        box-shadow:
            0 0 0 3px rgba(212, 175, 55, 0.12);
    }


    /* ================================
   BUTTON
================================ */

    .sell-search-btn {
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

        transition: 0.25s;
    }

    .sell-search-btn:hover {
        background: #0b1f3a;
        color: white;
    }


    /* ================================
   CONTENT
================================ */

    .sell-content {
        padding-top: 50px;
    }

    .sell-section-title {
        color: #0b1f3a;

        font-size: 26px;

        font-weight: 700;

        margin-bottom: 8px;
    }

    .sell-section-desc {
        color: #777;
        margin-bottom: 25px;
    }


    /* ================================
   SELL INFORMATION BOX
================================ */

    .sell-empty {
        background: white;

        border: 1px solid #eee9dc;

        border-radius: 14px;

        padding: 35px;

        text-align: center;

        color: #6b7280;
    }

    .sell-empty i {
        font-size: 35px;

        color: #d4af37;

        margin-bottom: 12px;
    }

    .sell-empty h3 {
        color: #0b1f3a;

        font-size: 19px;

        margin-bottom: 8px;
    }
</style>


<main class="sell-page">


    <!-- =================================
         HERO SECTION
    ================================== -->

    <section class="sell-hero">

        <div class="container">

            <div class="sell-hero-content">

                <span class="sell-label">
                    UrbanNest · Sell With Confidence
                </span>

                <h1>
                    Sell your property
                    <em>with ease.</em>
                </h1>

                <p>
                    Reach genuine buyers and showcase your property
                    to the right audience. List your property with
                    UrbanNest and take the next step with confidence.
                </p>

            </div>

        </div>

    </section>


    <!-- =================================
         SEARCH / FILTER SECTION
    ================================== -->

    <section class="sell-search-wrap">

        <div class="sell-search">

            <div class="search-heading">

                <h2>
                    Find Your Property
                </h2>

                <p>
                    Choose your property details to find suitable
                    selling options.
                </p>

            </div>


            <!-- SELL FORM -->

            <form
                action="properties/list.php"
                method="get">

                <!-- IMPORTANT:
                     This tells list.php
                     that the user selected SELL.
                -->

                <input
                    type="hidden"
                    name="purpose"
                    value="sell">


                <div class="sell-form-grid">


                    <!-- =====================
                         STATE
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            State
                        </label>

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


                    <!-- =====================
                         CITY
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            City
                        </label>

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

                            <option value="Vadodara">
                                Vadodara
                            </option>

                            <option value="Rajkot">
                                Rajkot
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


                    <!-- =====================
                         CATEGORY
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Property Category
                        </label>

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


                    <!-- =====================
                         PROPERTY TYPE
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Property Type
                        </label>

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

                            <option value="Commercial Plots">
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


                    <!-- =====================
                         EXPECTED PRICE
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Expected Price
                        </label>

                        <select name="price">

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

                    </div>


                    <!-- =====================
                         BEDROOMS
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Bedrooms
                        </label>

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


                    <!-- =====================
                         BATHROOMS
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Bathrooms
                        </label>

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


                    <!-- =====================
                         PROPERTY STATUS
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Property Status
                        </label>

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


                    <!-- =====================
                         AREA
                    ====================== -->

                    <div class="sell-field">

                        <label>
                            Area
                        </label>

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


                <!-- =====================
                     SUBMIT BUTTON
                ====================== -->

                <button
                    type="submit"
                    class="sell-search-btn">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    &nbsp; Find Selling Options

                </button>

            </form>

        </div>

    </section>


    <!-- =================================
         SELL PROPERTY INFORMATION
    ================================== -->

    <section class="container sell-content">

        <h2 class="sell-section-title">
            Sell Your Property With UrbanNest
        </h2>

        <p class="sell-section-desc">
            Reach potential buyers and showcase your property
            with the right details.
        </p>


        <div class="sell-empty">

            <i class="fa-solid fa-house-circle-check"></i>

            <h3>
                Ready to Sell Your Property?
            </h3>

            <p>
                Select your property details above to explore
                suitable selling options and reach interested buyers.
            </p>

        </div>

    </section>


</main>


<?php

require_once __DIR__ . "/includes/footer.php";

?>