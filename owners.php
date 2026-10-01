<?php

session_start();

require_once __DIR__ . '/db_connection.php';

$pageTitle = "Owners - UrbanNest";
$pageDesc = "Meet property owners on UrbanNest.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';


// Get all active owners
$sql = "SELECT * FROM users
        WHERE role_id = 5
        AND status = 'Active'
        ORDER BY full_name ASC";

$result = mysqli_query($conn, $sql);

?>

<style>
    body {
        background-color: #f8f7f2;
    }


    /* Owners Section */

    .owners-section {
        padding: 70px 0;
    }


    /* Page Heading */

    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-title h1 {
        color: #0b1f3a;
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .section-title p {
        color: #687386;
        font-size: 16px;
        margin: 0;
    }

    .title-line {
        width: 70px;
        height: 3px;
        background-color: #d4af37;
        margin: 18px auto;
    }


    /* Owner Card */

    .owner-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eeeeee;
        box-shadow: 0 5px 20px rgba(11, 31, 58, 0.08);
        transition: 0.3s;
        height: 100%;
    }

    .owner-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(11, 31, 58, 0.15);
    }


    /* Owner Image Area */

    .owner-image {
        height: 240px;

        background:
            linear-gradient(rgba(11, 31, 58, 0.55),
                rgba(11, 31, 58, 0.55)),
            #0b1f3a;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }


    /* Transparent Round Circle */

    .owner-placeholder {
        width: 90px;
        height: 90px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.12);

        border: 1px solid rgba(212, 175, 55, 0.8);

        color: #d4af37;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 38px;
        font-weight: 600;

        backdrop-filter: blur(4px);

        box-shadow:
            0 0 18px rgba(212, 175, 55, 0.12);

        transition: 0.3s;
    }


    /* Circle Hover */

    .owner-card:hover .owner-placeholder {
        background: rgba(255, 255, 255, 0.18);

        border-color: #d4af37;

        box-shadow:
            0 0 25px rgba(212, 175, 55, 0.25);

        transform: scale(1.05);
    }


    /* Owner Information */

    .owner-info {
        padding: 25px;
        text-align: center;
    }

    .owner-info h3 {
        color: #0b1f3a;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .owner-info p {
        color: #687386;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .owner-info i {
        color: #d4af37;
        width: 20px;
    }


    /* Contact Button */

    .owner-btn {
        display: inline-block;

        margin-top: 18px;

        padding: 10px 22px;

        background-color: #0b1f3a;

        color: #ffffff;

        text-decoration: none;

        border-radius: 6px;

        font-size: 14px;

        font-weight: 500;

        transition: 0.3s;
    }

    .owner-btn:hover {
        background-color: #d4af37;
        color: #0b1f3a;
    }


    /* No Owner */

    .no-owner {
        text-align: center;
        padding: 50px 20px;
        color: #687386;
    }


    /* Responsive */

    @media (max-width: 768px) {

        .owners-section {
            padding: 45px 15px;
        }

        .section-title h1 {
            font-size: 32px;
        }

        .owner-image {
            height: 220px;
        }

        .owner-placeholder {
            width: 80px;
            height: 80px;
            font-size: 34px;
        }

    }
</style>


<section class="owners-section">

    <div class="container">


        <!-- Page Heading -->

        <div class="section-title">

            <h1>Our Property Owners</h1>

            <div class="title-line"></div>

            <p>
                Connect with property owners and explore their listed properties.
            </p>

        </div>


        <!-- Owner Cards -->

        <div class="row g-4">

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($owner = mysqli_fetch_assoc($result)) {

            ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="owner-card">


                            <!-- Owner Letter -->

                            <div class="owner-image">

                                <div class="owner-placeholder">

                                    <?php
                                    echo strtoupper(substr($owner['full_name'], 0, 1));
                                    ?>

                                </div>

                            </div>


                            <!-- Owner Information -->

                            <div class="owner-info">

                                <h3>
                                    <?php
                                    echo $owner['full_name'];
                                    ?>
                                </h3>


                                <p>

                                    <i class="fa-solid fa-envelope"></i>

                                    <?php
                                    echo $owner['email'];
                                    ?>

                                </p>





                                <a href="contact.php" class="owner-btn">

                                    <i class="fa-solid fa-envelope"></i>

                                    Contact Owner

                                </a>

                            </div>

                        </div>

                    </div>


                <?php

                }
            } else {

                ?>

                <div class="col-12">

                    <div class="no-owner">

                        <h4>
                            No property owners found.
                        </h4>

                        <p>
                            There are currently no active property owners available.
                        </p>

                    </div>

                </div>

            <?php

            }

            ?>

        </div>

    </div>

</section>


<?php

require_once __DIR__ . '/includes/footer.php';

?>