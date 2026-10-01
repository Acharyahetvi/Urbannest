<?php

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/db.php';


// Get all active agents
$sql = "SELECT * FROM users
        WHERE role_id = 3
        AND status = 'Active'
        ORDER BY full_name ASC";

$result = mysqli_query($con, $sql);

?>

<style>
    body {
        background-color: #f8f7f2;
    }


    /* Agents Section */

    .agents-section {
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


    /* Agent Card */

    .agent-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eeeeee;
        box-shadow: 0 5px 20px rgba(11, 31, 58, 0.08);
        transition: 0.3s;
        height: 100%;
    }

    .agent-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(11, 31, 58, 0.15);
    }


    /* Agent Image Area */

    .agent-image {
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

    .agent-placeholder {
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

    .agent-card:hover .agent-placeholder {
        background: rgba(255, 255, 255, 0.18);

        border-color: #d4af37;

        box-shadow:
            0 0 25px rgba(212, 175, 55, 0.25);

        transform: scale(1.05);
    }


    /* Agent Information */

    .agent-info {
        padding: 25px;
        text-align: center;
    }

    .agent-info h3 {
        color: #0b1f3a;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .agent-role {
        color: #d4af37;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .agent-info p {
        color: #687386;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .agent-info i {
        color: #d4af37;
        width: 20px;
    }


    /* Contact Button */

    .agent-btn {
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

    .agent-btn:hover {
        background-color: #d4af37;
        color: #0b1f3a;
    }


    /* No Agent */

    .no-agent {
        text-align: center;
        padding: 50px 20px;
        color: #687386;
    }


    /* Responsive */

    @media (max-width: 768px) {

        .agents-section {
            padding: 45px 15px;
        }

        .section-title h1 {
            font-size: 32px;
        }

        .agent-image {
            height: 220px;
        }

        .agent-placeholder {
            width: 80px;
            height: 80px;
            font-size: 34px;
        }

    }
</style>


<section class="agents-section">

    <div class="container">


        <!-- Page Heading -->

        <div class="section-title">

            <h1>Meet Our Trusted Agents</h1>

            <div class="title-line"></div>

            <p>
                Connect with our trusted agents and make your property journey easier.
            </p>

        </div>

        <!-- Agent Cards -->

        <div class="row g-4">

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($agent = mysqli_fetch_assoc($result)) {

            ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="agent-card">


                            <!-- Agent Letter -->

                            <div class="agent-image">

                                <div class="agent-placeholder">

                                    <?php
                                    echo strtoupper(substr($agent['full_name'], 0, 1));
                                    ?>

                                </div>

                            </div>


                            <!-- Agent Information -->

                            <div class="agent-info">

                                <h3>
                                    <?php
                                    echo $agent['full_name'];
                                    ?>
                                </h3>


                                <p class="agent-role">
                                    Real Estate Agent
                                </p>


                                <p>

                                    <i class="fa-solid fa-envelope"></i>

                                    <?php
                                    echo $agent['email'];
                                    ?>


                                </p>





                                <a href="contact.php" class="agent-btn">

                                    <i class="fa-solid fa-envelope"></i>

                                    Contact Agent

                                </a>

                            </div>

                        </div>

                    </div>


                <?php

                }
            } else {

                ?>

                <div class="col-12">

                    <div class="no-agent">

                        <h4>
                            No Agents Found
                        </h4>

                        <p>
                            No active agents are available.
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