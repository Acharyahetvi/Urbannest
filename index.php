<?php

$pageTitle = 'UrbanNest — Luxury Real Estate';
$pageDesc  = 'Discover premium homes, penthouses & investment properties curated by UrbanNest.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/db_connection.php';

?>

<!-- ============== HERO ============== -->
<section class="un-hero">
    <div class="container">

        <div class="row">
            <div class="col-lg-9">

                <span class="un-hero-eyebrow">Discover · Reside · Belong</span>

                <h1>
                    Find the home that <em>defines</em><br>
                    the life you deserve.
                </h1>

                <p class="lead">
                    UrbanNest curates a global portfolio of extraordinary residences
                    matched to your ambitions with quiet precision.
                </p>

                <div class="d-flex flex-wrap gap-3">

                    <a href="properties/list.php" class="btn btn-gold px-4">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>
                        Explore Listings
                    </a>

                    <a href="contact.php" class="btn btn-light-ghost px-4">
                        Talk to an Advisor
                    </a>

                </div>

                <div class="un-hero-stats">

                    <div>
                        <div class="num">2,400+</div>
                        <div class="lbl">Listings</div>
                    </div>

                    <div>
                        <div class="num">180</div>
                        <div class="lbl">Cities</div>
                    </div>

                    <div>
                        <div class="num">98%</div>
                        <div class="lbl">Client Retention</div>
                    </div>

                </div>

            </div>
        </div>


        <!-- ============== SEARCH ============== -->
        <div class="un-search">

            <ul class="nav nav-tabs" role="tablist">

                <li class="nav-item">
                    <button class="nav-link active"
                        data-bs-toggle="tab"
                        data-bs-target="#buyTab"
                        type="button">
                        Buy
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#rentTab"
                        type="button">
                        Rent
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#sellTab"
                        type="button">
                        Sell
                    </button>
                </li>

            </ul>


            <div class="tab-content">

                <!-- BUY -->
                <div class="tab-pane fade show active" id="buyTab">

                    <form action="properties/list.php" method="get">

                        <div class="row g-2 align-items-center">

                            <div class="col-md-2">
                                <select name="state" class="form-select">

                                    <option value="">State</option>

                                    <?php foreach ($UN_STATES as $s): ?>
                                        <option value="<?= e($s['name']) ?>">
                                            <?= e($s['name']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>


                            <div class="col-md-2">

                                <select name="city" class="form-select">

                                    <option value="">City</option>

                                    <?php foreach ($UN_CITIES as $c): ?>

                                        <option value="<?= e($c['name']) ?>">
                                            <?= e($c['name']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <div class="col-md-2">

                                <select name="cat" class="form-select">

                                    <option value="">Category</option>

                                    <?php foreach ($UN_CATEGORIES as $c): ?>

                                        <option value="<?= e($c['slug']) ?>">
                                            <?= e($c['name']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <div class="col-md-2">

                                <select name="sub" class="form-select">

                                    <option value="">Sub Category</option>
                                    <option value="Studio">Apartment</option>
                                    <option value="Duplex">Bungalow</option>
                                    <option value="Triplex">Duplex</option>
                                    <option value="Estate">independent House</option>
                                    <option value="Triplex">Office</option>
                                    <option value="Triplex">Shop</option>
                                    <option value="Triplex">Warehouse</option>
                                    <option value="Triplex">Showroom</option>
                                    <option value="Triplex">Penthouse</option>
                                    <option value="Triplex">Farmhouse</option>
                                    <option value="Triplex">Luxury Villa</option>
                                    <option value="Triplex">Residential</option>
                                    <option value="Triplex">Commercial plots</option>
                                    <option value="Triplex">Agriculture</option>
                                    <option value="Triplex">Factory</option>
                                    <option value="Triplex">Industrial Shed</option>

                                </select>

                            </div>


                            <div class="col-md-2">

                                <select name="status" class="form-select">

                                    <option value="">Status</option>
                                    <option value="Ready to Move">Ready to Move</option>
                                    <option value="Under Construction">Under Construction</option>
                                    <option value="New Launch">New Launch</option>

                                </select>

                            </div>


                            <div class="col-md-2">

                                <button type="submit"
                                    class="btn btn-gold un-search-btn">

                                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                                    Search

                                </button>

                            </div>


                            <div class="col-md-3">

                                <select name="price" class="form-select">

                                    <option value="">Price Range</option>
                                    <option value="under-500k">Under $500k</option>
                                    <option value="500k-1m">$500k – $1M</option>
                                    <option value="1m-5m">$1M – $5M</option>
                                    <option value="5m-10m">$5M – $10M</option>
                                    <option value="10m-plus">$10M+</option>

                                </select>

                            </div>


                            <div class="col-md-3">

                                <select name="beds" class="form-select">

                                    <option value="">Bedrooms</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                    <option value="5">5+</option>

                                </select>

                            </div>


                            <div class="col-md-3">

                                <select name="baths" class="form-select">

                                    <option value="">Bathrooms</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>

                                </select>

                            </div>


                            <div class="col-md-3">

                                <select name="area" class="form-select">

                                    <option value="">Area (sqft)</option>
                                    <option value="500-1500">500 – 1,500</option>
                                    <option value="1500-3000">1,500 – 3,000</option>
                                    <option value="3000-6000">3,000 – 6,000</option>
                                    <option value="6000-plus">6,000+</option>

                                </select>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- RENT -->
                <div class="tab-pane fade" id="rentTab">

                    <form action="properties/list.php" method="get">

                        <input type="hidden" name="purpose" value="rent">

                        <div class="row g-2">

                            <div class="col-md-3">
                                <input type="text"
                                    name="location"
                                    class="form-control"
                                    placeholder="Where do you want to live?">
                            </div>

                            <div class="col-md-3">

                                <select name="type" class="form-select">

                                    <option value="">Any Type</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Loft">Loft</option>
                                    <option value="Villa">Villa</option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <select name="price" class="form-select">

                                    <option value="">Any Price</option>
                                    <option value="under-3k">Under $3k/mo</option>
                                    <option value="3k-6k">$3k – $6k/mo</option>
                                    <option value="6k-plus">$6k+/mo</option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <button type="submit"
                                    class="btn btn-gold un-search-btn">

                                    Find Rentals

                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- SELL -->
                <div class="tab-pane fade" id="sellTab">

                    <div class="row g-2">

                        <div class="col-md-4">
                            <input type="text"
                                class="form-control"
                                placeholder="Property Address">
                        </div>

                        <div class="col-md-3">

                            <select class="form-select">

                                <option>Property Type</option>
                                <option>Villa</option>