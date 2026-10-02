<?php

$pageTitle = 'About Us — UrbanNest';
$pageDesc = 'Learn more about UrbanNest and our real estate platform.';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';

?>

<style>
    /* =====================================
   URBANNEST ABOUT PAGE
===================================== */

    .un-about-hero {

        position: relative;

        min-height: 430px;

        display: flex;
        align-items: center;

        color: #fff;

        background:
            linear-gradient(90deg,
                rgba(11, 31, 58, 0.92),
                rgba(11, 31, 58, 0.70),
                rgba(11, 31, 58, 0.35)),
            url('assets/images/hero.png') center / cover no-repeat;
    }


    .un-about-hero-content {

        max-width: 720px;

    }


    .un-about-label {

        display: inline-block;

        margin-bottom: 18px;

        color: #d4af37;

        font-size: 13px;

        font-weight: 700;

        letter-spacing: 3px;

        text-transform: uppercase;
    }


    .un-about-hero h1 {

        margin-bottom: 18px;

        font-size: clamp(40px, 5vw, 60px);

        line-height: 1.15;

        font-weight: 700;
    }


    .un-about-hero h1 span {

        color: #d4af37;

    }


    .un-about-hero p {

        max-width: 620px;

        margin: 0;

        color: #e4e8ee;

        font-size: 17px;

        line-height: 1.8;
    }


    /* =====================================
   ABOUT INTRO
===================================== */

    .un-about-section {

        padding: 80px 0;

        background: #f8f7f2;
    }


    .un-about-content {

        padding-right: 35px;

    }


    .un-about-small-title {

        margin-bottom: 10px;

        color: #d4af37;

        font-size: 13px;

        font-weight: 700;

        letter-spacing: 2px;

        text-transform: uppercase;
    }


    .un-about-content h2 {

        margin-bottom: 20px;

        color: #0b1f3a;

        font-size: 38px;

        font-weight: 700;

        line-height: 1.25;
    }


    .un-about-content h2 span {

        color: #b9922e;

    }


    .un-about-content p {

        margin-bottom: 16px;

        color: #687386;

        font-size: 15px;

        line-height: 1.9;
    }


    /* =====================================
   ABOUT IMAGE
===================================== */

    .un-about-image {

        position: relative;

        overflow: hidden;

        border-radius: 16px;

        box-shadow:
            0 18px 45px rgba(11, 31, 58, 0.14);
    }


    .un-about-image img {

        width: 100%;

        height: 390px;

        display: block;

        object-fit: cover;
    }


    /* GOLD FRAME */

    .un-about-image::after {

        content: "";

        position: absolute;

        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;

        border: 1px solid rgba(212, 175, 55, 0.65);

        border-radius: 12px;

        pointer-events: none;
    }


    /* =====================================
   OUR VALUES
===================================== */

    .un-values-section {

        padding: 80px 0;

        background: #fff;
    }


    .un-section-heading {

        margin-bottom: 45px;

        text-align: center;
    }


    .un-section-heading::before {

        content: "";

        display: block;

        width: 65px;

        height: 3px;

        margin: 0 auto 22px;

        background: #d4af37;

        border-radius: 10px;
    }


    .un-section-heading h2 {

        margin-bottom: 10px;

        color: #0b1f3a;

        font-size: 36px;

        font-weight: 700;
    }


    .un-section-heading p {

        margin: 0;

        color: #687386;

        font-size: 15px;
    }


    /* =====================================
   VALUE CARDS
===================================== */

    .un-value-card {

        height: 100%;

        padding: 32px 28px;

        text-align: center;

        background: #fff;

        border: 1px solid #eee5cf;

        border-radius: 15px;

        box-shadow: 0 8px 25px rgba(11, 31, 58, 0.06);

        transition: 0.3s ease;
    }


    .un-value-card:hover {

        transform: translateY(-7px);

        border-color: #d4af37;

        box-shadow: 0 16px 35px rgba(11, 31, 58, 0.12);
    }


    /* =====================================
   VALUE IMAGE
===================================== */

    .un-value-icon {

        width: 100px;

        height: 100px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin: 0 auto 20px;

        border-radius: 50%;

        overflow: hidden;

        background: #f8f4e8;

        border: 1px solid #d4af37;
    }


    .un-value-icon img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;
    }


    /* =====================================
   VALUE CARD TEXT
===================================== */

    .un-value-card h3 {

        margin-bottom: 12px;

        color: #0b1f3a;

        font-size: 20px;

        font-weight: 700;
    }


    .un-value-card p {

        margin: 0;

        color: #687386;

        font-size: 14px;

        line-height: 1.8;
    }


    /* =====================================
   WHY URBANNEST
===================================== */

    .un-why-section {

        padding: 80px 0;

        background:
            linear-gradient(135deg,
                #0b1f3a,
                #102b4d);

        color: #fff;
    }


    .un-why-content {

        max-width: 800px;

        margin: auto;

        text-align: center;
    }


    .un-why-content h2 {

        margin-bottom: 18px;

        font-size: 38px;

        font-weight: 700;
    }


    .un-why-content h2 span {

        color: #d4af37;
    }


    .un-why-content p {

        margin-bottom: 28px;

        color: #dce3ec;

        font-size: 16px;

        line-height: 1.9;
    }


    .un-about-btn {

        display: inline-flex;

        align-items: center;

        gap: 9px;

        padding: 13px 25px;

        background: #d4af37;

        color: #0b1f3a;

        border: 1px solid #d4af37;

        border-radius: 8px;

        text-decoration: none;

        font-weight: 700;

        transition: 0.25s;
    }


    .un-about-btn:hover {

        background: #fff;

        border-color: #fff;

        color: #0b1f3a;

        transform: translateY(-2px);
    }


    /* =====================================
   RESPONSIVE
===================================== */

    @media (max-width: 768px) {

        .un-about-hero {

            min-height: 390px;

            padding: 70px 0;
        }


        .un-about-hero h1 {

            font-size: 40px;
        }


        .un-about-hero p {

            font-size: 15px;
        }


        .un-about-section {

            padding: 60px 0;
        }


        .un-about-content {

            padding-right: 0;

            margin-bottom: 35px;
        }


        .un-about-content h2 {

            font-size: 30px;
        }


        .un-about-image img {

            height: 300px;
        }


        .un-values-section {

            padding: 60px 0;
        }


        .un-section-heading h2 {

            font-size: 30px;
        }


        .un-why-section {

            padding: 60px 20px;
        }


        .un-why-content h2 {

            font-size: 30px;
        }

    }
</style>


<!-- =====================================
     ABOUT HERO
===================================== -->

<section class="un-about-hero">

    <div class="container">

        <div class="un-about-hero-content">

            <span class="un-about-label">

                About UrbanNest

            </span>


            <h1>

                Where Every Home
                <span>Feels Right.</span>

            </h1>


            <p>

                UrbanNest is a modern real estate portal designed
                to make finding, buying, renting and listing properties
                simple, convenient and reliable.

            </p>

        </div>

    </div>

</section>


<!-- =====================================
     ABOUT INTRODUCTION
===================================== -->

<section class="un-about-section">

    <div class="container">

        <div class="row align-items-center g-5">


            <!-- TEXT -->

            <div class="col-lg-6">

                <div class="un-about-content">

                    <div class="un-about-small-title">

                        Who We Are

                    </div>


                    <h2>

                        Discover a Better Way
                        to Find Your <span>Home</span>

                    </h2>


                    <p>

                        UrbanNest is a real estate portal created
                        to provide users with an easy and organized
                        way to explore properties.

                    </p>


                    <p>

                        From buying and renting a home to listing
                        a property for sale or rent, UrbanNest brings
                        different property services together in one
                        convenient platform.

                    </p>


                    <p>

                        Users can explore property details, compare
                        available options, connect with owners and
                        agents, and book property visits.

                    </p>

                </div>

            </div>


            <!-- IMAGE -->

            <div class="col-lg-6">

                <div class="un-about-image">

                    <img src="assets/images/hero.png"
                        alt="UrbanNest Real Estate">

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =====================================
     OUR VALUES
===================================== -->

<section class="un-values-section">

    <div class="container">

        <div class="un-section-heading">

            <h2>What We Focus On</h2>

            <p>

                Making the property search experience simple and convenient.

            </p>

        </div>


        <div class="row g-4">


            <!-- VALUE 1 -->

            <div class="col-md-4">

                <div class="un-value-card">

                    <div class="un-value-icon">

                        <img src="assets/images/properties.jpeg"
                            alt="Quality Properties">

                    </div>


                    <h3>

                        Quality Properties

                    </h3>


                    <p>

                        Explore a variety of properties with
                        useful information about location, price,
                        features and amenities.

                    </p>

                </div>

            </div>


            <!-- VALUE 2 -->

            <div class="col-md-4">

                <div class="un-value-card">

                    <div class="un-value-icon">

                        <img src="assets/images/search.png"
                            alt="Easy Property Search">

                    </div>


                    <h3>

                        Easy Property Search

                    </h3>


                    <p>

                        Find suitable properties using simple
                        search options based on your requirements.

                    </p>

                </div>

            </div>


            <!-- VALUE 3 -->

            <div class="col-md-4">

                <div class="un-value-card">

                    <div class="un-value-icon">

                        <img src="assets/images/connection.jpeg"
                            alt="Easy Connection">

                    </div>


                    <h3>

                        Easy Connection

                    </h3>


                    <p>

                        Connect with property owners and agents
                        and take the next step towards your property.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- =====================================
     WHY URBANNEST
===================================== -->

<section class="un-why-section">

    <div class="container">

        <div class="un-why-content">

            <h2>

                Your Property Journey,
                <span>Made Simpler.</span>

            </h2>


            <p>

                Whether you are looking for your next home,
                searching for a rental property or planning to
                list your property, UrbanNest brings the essential
                property services together in one place.

            </p>


            <a href="properties/list.php"
                class="un-about-btn">

                <i class="fa-solid fa-building"></i>

                Explore Properties

            </a>

        </div>

    </div>

</section>


<?php

require_once __DIR__ . '/includes/footer.php';

?>