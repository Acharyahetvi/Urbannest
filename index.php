<?php

$pageTitle = 'UrbanNest — Luxury Real Estate';
$pageDesc = 'Discover premium homes, penthouses & investment properties curated by UrbanNest';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';


// ===================================== 
// GET REVIEWS FROM DATABASE 
// ===================================== 

$reviews = [];

$sql = "SELECT users.full_name, reviews.rating, reviews.review, reviews.created_at 
        FROM reviews 
        INNER JOIN users ON reviews.user_id = users.user_id 
        ORDER BY reviews.created_at DESC 
        LIMIT 10";

$result = mysqli_query($conn, $sql);


// STORE REVIEWS 

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $reviews[] = $row;
    }
}

?>

<style>
    /* ===================================== 
   URBANNEST HOME PAGE 
===================================== */


    /* =====================================
   HERO SECTION
===================================== */

    .un-hero {

        position: relative;
        min-height: 620px;
        padding: 90px 0 70px;

        color: #fff;

        background:
            linear-gradient(90deg,
                rgba(11, 31, 58, 0.85),
                rgba(11, 31, 58, 0.60),
                rgba(11, 31, 58, 0.20)),
            url('assets/images/hero.png') center / cover no-repeat;
    }


    .un-hero-content {

        max-width: 720px;
    }


    .un-hero-eyebrow {

        display: inline-block;
        margin-bottom: 18px;

        color: #d4af37;

        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;

        text-transform: uppercase;
    }


    .un-hero h1 {

        margin-bottom: 22px;

        font-size: clamp(36px, 5vw, 62px);
        line-height: 1.15;

        font-weight: 700;
    }


    .un-hero h1 em {

        color: #d4af37;
        font-style: normal;
    }


    .un-hero p {

        max-width: 600px;
        margin-bottom: 28px;

        color: #e2e7ef;

        font-size: 17px;
        line-height: 1.8;
    }


    /* HERO BUTTONS */

    .un-hero-buttons {

        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }


    .un-btn-gold {

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;

        padding: 13px 24px;

        border: 1px solid #d4af37;
        border-radius: 8px;

        background: #d4af37;
        color: #0b1f3a;

        font-weight: 700;
        text-decoration: none;

        transition: 0.25s;
    }


    .un-btn-gold:hover {

        background: #fff;
        border-color: #fff;

        color: #0b1f3a;

        transform: translateY(-2px);
    }


    .un-btn-outline {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 13px 24px;

        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 8px;

        color: #fff;

        font-weight: 600;
        text-decoration: none;

        transition: 0.25s;
    }


    .un-btn-outline:hover {

        background: #fff;
        color: #0b1f3a;
    }


    /* =====================================
   HERO STATISTICS
===================================== */

    .un-hero-stats {

        display: flex;
        flex-wrap: wrap;
        gap: 45px;

        margin-top: 45px;
    }


    .un-stat .num {

        color: #d4af37;

        font-size: 27px;
        font-weight: 700;
    }


    .un-stat .lbl {

        margin-top: 4px;

        color: #e2e7ef;

        font-size: 13px;
    }


    /* =====================================
   BUY / RENT / SELL SECTION
===================================== */

    .un-purpose-section {

        position: relative;
        z-index: 2;

        margin-top: -45px;

        padding-bottom: 65px;
    }


    .un-purpose-heading {

        margin-bottom: 24px;

        color: #021020;

        text-align: center;
    }


    .un-purpose-heading h2 {

        margin-bottom: 8px;

        font-size: 30px;
        font-weight: 700;
    }


    .un-purpose-heading p {

        margin: 0;

        color: #687386;
    }


    /* =====================================
   PURPOSE CARDS
===================================== */

    .un-purpose-card {

        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 15px;

        height: 100%;
        padding: 18px;

        border: 1px solid #eee9dc;
        border-radius: 15px;

        background: #fff;

        box-shadow: 0 8px 25px rgba(11, 31, 58, 0.08);

        text-decoration: none;

        transition: 0.25s;
    }


    .un-purpose-card:hover {

        border-color: #d4af37;

        box-shadow: 0 14px 30px rgba(11, 31, 58, 0.13);

        transform: translateY(-5px);
    }


    /* =====================================
   PURPOSE IMAGE
===================================== */

    .un-purpose-image {

        width: 100%;
        height: 220px;

        overflow: hidden;

        border-radius: 10px;

        flex-shrink: 0;
    }


    .un-purpose-image img {

        width: 100%;
        height: 100%;

        object-fit: cover;

        display: block;

        transition: transform 0.4s ease;
    }


    .un-purpose-card:hover .un-purpose-image img {

        transform: scale(1.05);
    }


    /* =====================================
   PURPOSE ICON
===================================== */

    .un-purpose-icon {

        display: flex;
        flex-shrink: 0;

        align-items: center;
        justify-content: center;

        width: 58px;
        height: 58px;

        border-radius: 13px;

        background: #f8f4e8;
        color: #b9922e;

        font-size: 24px;
    }


    /* =====================================
   PURPOSE INFO
===================================== */

    .un-purpose-info {

        flex: 1;
        min-width: 0;
        width: 100%;
    }


    .un-purpose-info h3 {

        margin: 0 0 10px;

        color: #0b1f3a;

        font-size: 21px;
        font-weight: 700;

        line-height: 1.4;
    }


    .un-purpose-info p {

        margin: 0;

        color: #687386;

        font-size: 14px;
        line-height: 1.7;

        overflow-wrap: break-word;
    }


    /* =====================================
   PURPOSE ARROW
===================================== */

    .un-purpose-arrow {

        flex-shrink: 0;

        align-self: flex-end;

        margin-top: auto;

        color: #b9922e;

        font-size: 18px;

        transition: transform 0.25s;
    }


    .un-purpose-card:hover .un-purpose-arrow {

        transform: translateX(5px);
    }


    /* =====================================
   USER REVIEWS SECTION
===================================== */

    .un-review-section {

        position: relative;

        padding: 70px 0;

        background:
            linear-gradient(180deg,
                #f8f7f2 0%,
                #f5f2e9 100%);
    }


    /* GOLD DECORATIVE LINE */

    .un-review-section::before {

        content: "";

        display: block;

        width: 70px;
        height: 3px;

        margin: 0 auto 25px;

        background: #d4af37;

        border-radius: 10px;
    }


    /* =====================================
   REVIEW HEADING
===================================== */

    .un-review-heading {

        margin-bottom: 32px;

        text-align: center;
    }


    .un-review-heading h2 {

        margin-bottom: 8px;

        color: #0b1f3a;

        font-size: 34px;
        font-weight: 700;
    }


    .un-review-heading p {

        margin: 0;

        color: #687386;

        font-size: 15px;
    }


    /* =====================================
   REVIEW SLIDER
===================================== */

    .un-review-slider-wrapper {

        position: relative;

        width: 100%;

        overflow: hidden;

        touch-action: pan-y;
    }


    .un-review-track {

        display: flex;

        width: 100%;

        transition: transform 0.4s ease;
    }


    .un-review-slide {

        flex: 0 0 100%;

        width: 100%;
    }


    .un-review-slide-row {

        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 20px;
    }


    /* =====================================
   REVIEW CARD
===================================== */

    .un-review-card {

        position: relative;

        min-height: 205px;

        padding: 25px 24px;

        overflow: hidden;

        background: #fff;

        border: 1px solid #eee5cf;
        border-radius: 14px;

        box-shadow: 0 7px 22px rgba(11, 31, 58, 0.07);

        transition: 0.25s;
    }


    .un-review-card::before {

        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 4px;

        background: #d4af37;
    }


    .un-review-card:hover {

        transform: translateY(-5px);

        border-color: rgba(212, 175, 55, 0.55);

        box-shadow: 0 14px 30px rgba(11, 31, 58, 0.11);
    }


    /* =====================================
   REVIEW STARS
===================================== */

    .un-review-stars {

        margin-bottom: 13px;

        color: #d4af37;

        font-size: 18px;

        letter-spacing: 2px;
    }


    /* =====================================
   REVIEW TEXT
===================================== */

    .un-review-text {

        min-height: 66px;

        margin-bottom: 18px;

        color: #596273;

        font-size: 14px;

        line-height: 1.65;

        overflow-wrap: anywhere;
    }


    /* =====================================
   CUSTOMER NAME
===================================== */

    .un-review-name {

        margin-bottom: 4px;

        color: #0b1f3a;

        font-size: 16px;

        font-weight: 700;
    }


    /* =====================================
   REVIEW DATE
===================================== */

    .un-review-date {

        color: #8a919c;

        font-size: 12px;
    }


    /* =====================================
   REVIEW CONTROLS
===================================== */

    .un-review-controls {

        display: flex;

        align-items: center;
        justify-content: center;

        gap: 14px;

        margin-top: 22px;
    }


    .un-review-arrow {

        display: flex;

        align-items: center;
        justify-content: center;

        width: 36px;
        height: 36px;

        border: 1px solid #d4af37;
        border-radius: 50%;

        background: #fff;

        color: #0b1f3a;

        cursor: pointer;

        transition: 0.2s;
    }


    .un-review-arrow:hover {

        background: #d4af37;

        color: #0b1f3a;
    }


    .un-review-dots {

        display: flex;

        align-items: center;

        gap: 6px;
    }


    .un-review-dot {

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #d6d6d6;

        transition: 0.2s;
    }


    .un-review-dot.active {

        width: 20px;

        border-radius: 10px;

        background: #d4af37;
    }


    /* =====================================
   NO REVIEW
===================================== */

    .un-no-review {

        padding: 30px;

        color: #687386;

        text-align: center;
    }


    /* =====================================
   RESPONSIVE
===================================== */

    @media (max-width: 767px) {

        .un-review-section {

            padding: 55px 0;
        }

        .un-review-heading h2 {

            font-size: 29px;
        }

        .un-review-slide-row {

            grid-template-columns: 1fr;
        }

        .un-review-card {

            min-height: 190px;
        }

    }
</style>


<!-- =====================================
     HERO SECTION
===================================== -->

<section class="un-hero">

    <div class="container">

        <div class="un-hero-content">

            <!-- EYEBROW -->

            <span class="un-hero-eyebrow">

                Discover · Reside · Belong

            </span>


            <!-- HERO HEADING -->

            <h1>

                Find the home that <em>defines</em>
                the life you deserve.

            </h1>


            <!-- HERO DESCRIPTION -->

            <p>

                Discover beautiful properties, explore new places
                and find a home that fits your lifestyle with UrbanNest.

            </p>


            <!-- HERO BUTTONS -->

            <div class="un-hero-buttons">

                <a href="properties/list.php" class="un-btn-gold">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    Explore Listings

                </a>


                <a href="contact.php" class="un-btn-outline">

                    Talk to an Advisor

                </a>

            </div>


            <!-- HERO STATISTICS -->

            <div class="un-hero-stats">

                <div class="un-stat">

                    <div class="num">2,400+</div>

                    <div class="lbl">Listings</div>

                </div>


                <div class="un-stat">

                    <div class="num">180</div>

                    <div class="lbl">Cities</div>

                </div>


                <div class="un-stat">

                    <div class="num">98%</div>

                    <div class="lbl">Client Retention</div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =====================================
     BUY / RENT / SELL SECTION
===================================== -->

<section class="un-purpose-section">

    <div class="container">

        <!-- SECTION HEADING -->

        <div class="un-purpose-heading">

            <h2>What are you looking for?</h2>

            <p>Choose an option to get started.</p>

        </div>


        <div class="row g-3">


            <!-- BUY PROPERTY -->

            <div class="col-md-4">

                <a href="buy.php" class="un-purpose-card">

                    <!-- Property Image -->

                    <div class="un-purpose-image">

                        <img src="assets/images/buy.jpg"
                            alt="Buy a Property">

                    </div>


                    <div class="un-purpose-info">

                        <h3>Buy a Property</h3>

                        <p>

                            Find your dream home from our property listings.

                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right un-purpose-arrow"></i>

                </a>

            </div>


            <!-- RENT PROPERTY -->

            <div class="col-md-4">

                <a href="rent.php" class="un-purpose-card">

                    <!-- Property Image -->

                    <div class="un-purpose-image">

                        <img src="assets/images/rent.jpg"
                            alt="Rent a Property">

                    </div>


                    <div class="un-purpose-info">

                        <h3>Rent a Property</h3>

                        <p>

                            Explore homes and spaces available for rent.

                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right un-purpose-arrow"></i>

                </a>

            </div>


            <!-- SELL PROPERTY -->

            <div class="col-md-4">

                <a href="sale.php" class="un-purpose-card">

                    <!-- Property Image -->

                    <div class="un-purpose-image">

                        <img src="assets/images/sell.jpg"
                            alt="Sell a Property">

                    </div>


                    <div class="un-purpose-info">

                        <h3>Sale a Property</h3>

                        <p>

                            List your property and connect with buyers.

                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right un-purpose-arrow"></i>

                </a>

            </div>


        </div>

    </div>

</section>


<!-- =====================================
     USER REVIEWS SECTION
===================================== -->

<section class="un-review-section">

    <div class="container">

        <!-- REVIEW HEADING -->

        <div class="un-review-heading">

            <h2>What Our Customers Say</h2>

            <p>
                Read reviews from our valued customers.
            </p>

        </div>


        <?php if (count($reviews) > 0) { ?>

            <?php $reviewSlides = array_chunk($reviews, 3); ?>

            <div class="un-review-slider-wrapper" id="reviewSliderWrapper">

                <div class="un-review-track" id="reviewTrack">

                    <?php foreach ($reviewSlides as $slide) { ?>

                        <div class="un-review-slide">

                            <div class="un-review-slide-row">

                                <?php foreach ($slide as $review) { ?>

                                    <div class="un-review-card">

                                        <!-- RATING -->

                                        <div class="un-review-stars">

                                            <?php

                                            $rating = (int)$review["rating"];

                                            for ($i = 1; $i <= 5; $i++) {

                                                echo ($i <= $rating) ? "★" : "☆";

                                            }

                                            ?>

                                        </div>


                                        <!-- REVIEW TEXT -->

                                        <p class="un-review-text">

                                            <?php
                                            echo htmlspecialchars($review["review"]);
                                            ?>

                                        </p>


                                        <!-- CUSTOMER NAME -->

                                        <div class="un-review-name">

                                            <?php
                                            echo htmlspecialchars($review["full_name"]);
                                            ?>

                                        </div>


                                        <!-- REVIEW DATE -->

                                        <div class="un-review-date">

                                            <?php

                                            echo date(
                                                "d M Y",
                                                strtotime($review["created_at"])
                                            );

                                            ?>

                                        </div>

                                    </div>

                                <?php } ?>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            </div>


            <!-- REVIEW CONTROLS -->

            <?php if (count($reviewSlides) > 1) { ?>

                <div class="un-review-controls">

                    <button
                        type="button"
                        class="un-review-arrow"
                        id="reviewPrev"
                        aria-label="Previous reviews">

                        <i class="fa-solid fa-chevron-left"></i>

                    </button>


                    <div class="un-review-dots" id="reviewDots">

                        <?php foreach ($reviewSlides as $index => $slide) { ?>

                            <span
                                class="un-review-dot <?php echo ($index === 0) ? 'active' : ''; ?>">
                            </span>

                        <?php } ?>

                    </div>


                    <button
                        type="button"
                        class="un-review-arrow"
                        id="reviewNext"
                        aria-label="Next reviews">

                        <i class="fa-solid fa-chevron-right"></i>

                    </button>

                </div>

            <?php } ?>


        <?php } else { ?>

            <div class="un-no-review">

                No reviews available yet.

            </div>

        <?php } ?>

    </div>

</section>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const track = document.getElementById("reviewTrack");
    const wrapper = document.getElementById("reviewSliderWrapper");
    const prevButton = document.getElementById("reviewPrev");
    const nextButton = document.getElementById("reviewNext");
    const dots = document.querySelectorAll(".un-review-dot");

    if (!track || !wrapper) {
        return;
    }


    const slides = track.querySelectorAll(".un-review-slide");

    if (slides.length <= 1) {
        return;
    }


    let currentSlide = 0;

    let touchStartX = 0;
    let touchEndX = 0;


    function updateSlider() {

        track.style.transform =
            "translateX(-" + (currentSlide * 100) + "%)";


        dots.forEach(function (dot, index) {

            dot.classList.toggle(
                "active",
                index === currentSlide
            );

        });

    }


    function showNext() {

        if (currentSlide < slides.length - 1) {

            currentSlide++;

        } else {

            currentSlide = 0;

        }

        updateSlider();

    }


    function showPrevious() {

        if (currentSlide > 0) {

            currentSlide--;

        } else {

            currentSlide = slides.length - 1;

        }

        updateSlider();

    }


    if (nextButton) {

        nextButton.addEventListener(
            "click",
            showNext
        );

    }


    if (prevButton) {

        prevButton.addEventListener(
            "click",
            showPrevious
        );

    }


    dots.forEach(function (dot, index) {

        dot.addEventListener("click", function () {

            currentSlide = index;

            updateSlider();

        });

    });


    /* Swipe */

    wrapper.addEventListener("touchstart", function (event) {

        touchStartX = event.changedTouches[0].screenX;

    }, { passive: true });


    wrapper.addEventListener("touchend", function (event) {

        touchEndX = event.changedTouches[0].screenX;

        const swipeDistance = touchStartX - touchEndX;

        if (Math.abs(swipeDistance) < 50) {
            return;
        }


        if (swipeDistance > 0) {

            showNext();

        } else {

            showPrevious();

        }

    }, { passive: true });

});

</script>


<?php

// =====================================
// INCLUDE FOOTER
// =====================================

require_once __DIR__ . '/includes/footer.php';

?>