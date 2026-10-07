<?php

session_start();

require_once __DIR__ . '/db_connection.php';

$pageTitle = "Owners - UrbanNest";
$pageDesc = "Meet property owners on UrbanNest.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';


// Get all active owners
$sql = "SELECT 
            u.*,
            op.profile_photo
        FROM users u
        LEFT JOIN owner_profiles op
            ON u.user_id = op.user_id
        WHERE u.role_id = 5
        AND u.status = 'Active'
        ORDER BY u.full_name ASC";

$result = mysqli_query($conn, $sql);

?>
<style>
    body {
        background-color: #f6f3ec;
    }

    .owners-section {
        padding: 42px 0 52px;
    }

    .owners-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 22px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 28px;
    }

    .section-title h1 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 36px;
        font-weight: 600;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 10px;
    }

    .title-line {
        width: 48px;
        height: 2px;
        background-color: #d4af37;
        margin: 0 auto 12px;
    }

    .section-title p {
        color: #5f6b7a;
        font-size: 14px;
        line-height: 1.55;
        margin: 0 auto;
        max-width: 520px;
    }

    .owners-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        column-gap: 20px;
        row-gap: 24px;
        justify-content: start;
        align-items: stretch;
    }

    .owner-card {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border: 1px solid #e4ddd0;
        border-top: 3px solid #d4af37;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(11, 31, 58, 0.05);
        min-height: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .owner-card:hover {
        border-color: #d4c6a4;
        border-top-color: #d4af37;
        box-shadow: 0 10px 22px rgba(11, 31, 58, 0.08);
    }

    .owner-image {
        height: 205px;
        background: #0b1f3a;
        overflow: hidden;
        flex-shrink: 0;
    }

    .owner-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 50% 18%;
        display: block;
    }

    .owner-photo[alt="Asha Desai"] {
        transform: scale(0.9);
    }

    .owner-photo[alt="Mihir Shah"] {
        object-position: 50% 0%;
    }

    .owner-photo[alt="Rajesh Patel"] {
        object-position: 50% 12%;
    }

    .owner-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 42px;
        font-weight: 600;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #d4af37;
    }

    .owner-info {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 18px 18px 20px;
        text-align: left;
    }

    .owner-info h3 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 21px;
        font-weight: 600;
        line-height: 1.25;
        margin: 0 0 12px;
    }

    .owner-info p {
        color: #5f6b7a;
        font-size: 13px;
        margin-bottom: 0;
        word-break: break-word;
    }

    .owner-info i {
        color: #d4af37;
        width: 16px;
        margin-right: 6px;
        font-size: 12px;
    }

    .owner-email {
        margin-bottom: 16px !important;
    }

    .owner-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 160px;
        margin-top: auto;
        padding: 9px 16px;
        background-color: #0b1f3a;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        transition: background-color 0.2s ease, color 0.2s ease;
        align-self: center;
        text-align: center;
    }

    .owner-btn:hover {
        background-color: #d4af37;
        color: #0b1f3a;
    }

    .no-owner {
        grid-column: 1 / -1;
        text-align: center;
        padding: 36px 20px;
        color: #5f6b7a;
        background: #ffffff;
        border: 1px solid #e4ddd0;
        border-radius: 10px;
    }

    .no-owner h4 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        margin-bottom: 8px;
    }

    @media (max-width: 991px) {
        .owners-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .section-title h1 {
            font-size: 32px;
        }
    }

    @media (max-width: 639px) {
        .owners-section {
            padding: 28px 0 36px;
        }

        .owners-grid {
            grid-template-columns: 1fr;
            max-width: 420px;
            margin: 0 auto;
        }

        .section-title h1 {
            font-size: 30px;
        }

        .owner-image {
            height: 200px;
        }
    }


</style>


<section class="owners-section">

    <div class="owners-wrap">


        <!-- Page Heading -->

        <div class="section-title">

            <h1>Our Property Owners</h1>

            <div class="title-line"></div>

            <p>
                Connect with property owners and explore their listed properties.
            </p>

        </div>


        <!-- Owner Cards -->

        <div class="owners-grid">

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($owner = mysqli_fetch_assoc($result)) {

            ?>

                    <article class="owner-card">


                            <!-- Owner Letter -->
                            <div class="owner-image">
                                <?php if (!empty($owner['profile_photo'])) { ?>
                                    <img
                                        src="admin/assets/images/owners/<?php echo htmlspecialchars($owner['profile_photo']); ?>"
                                        alt="<?php echo htmlspecialchars($owner['full_name']); ?>"
                                        class="owner-photo"
                                    >
                                <?php } else { ?>
                                    <div class="owner-placeholder">
                                        <?php echo strtoupper(substr($owner['full_name'], 0, 1)); ?>
                                    </div>
                                <?php } ?>
                            </div>
                              

                            <!-- Owner Information -->

                            <div class="owner-info">

                                <h3>
                                    <?php
                                    echo $owner['full_name'];
                                    ?>
                                </h3>


                                <p class="owner-email">

                                    <i class="fa-solid fa-envelope"></i>

                                    <?php
                                    echo $owner['email'];
                                    ?>

                                </p>





                                <a href="owner-details.php?id=<?php echo (int)$owner['user_id']; ?>" class="owner-btn">
                                    View Profile
                                </a>

                            </div>

                    </article>


                <?php

                }
            } else {

                ?>

                <div class="no-owner">

                        <h4>
                            No property owners found.
                        </h4>

                        <p>
                            There are currently no active property owners available.
                        </p>

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