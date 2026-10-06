<?php

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/db.php';


// Get all active agents
$sql = "SELECT 
            u.*,
            ap.profile_photo
        FROM users u
        LEFT JOIN agent_profiles ap
            ON u.user_id = ap.user_id
        WHERE u.role_id = 3
        AND u.status = 'Active'
        ORDER BY u.full_name ASC";

$result = mysqli_query($con, $sql);

?>

<style>
    body {
        background-color: #f6f3ec;
    }

    .agents-section {
        padding: 42px 0 52px;
    }

    .agents-wrap {
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

    .agents-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        column-gap: 20px;
        row-gap: 24px;
        justify-content: start;
        align-items: stretch;
    }

    .agent-card {
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

    .agent-card:hover {
        border-color: #d4c6a4;
        border-top-color: #d4af37;
        box-shadow: 0 10px 22px rgba(11, 31, 58, 0.08);
    }

    .agent-image {
        height: 205px;
        background: #0b1f3a;
        overflow: hidden;
        flex-shrink: 0;
    }

    .agent-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 50% 18%;
        display: block;
    }

    .agent-card:nth-child(4) .agent-photo,
    .agent-card:nth-child(5) .agent-photo {
        object-position: 50% 10%;
    }

    .agent-placeholder {
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

    .agent-info {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 18px 18px 20px;
        text-align: left;
    }

    .agent-info h3 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 21px;
        font-weight: 600;
        line-height: 1.25;
        margin: 0 0 6px;
    }

    .agent-role {
        color: #d4af37;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .agent-info p {
        color: #5f6b7a;
        font-size: 13px;
        margin-bottom: 0;
        word-break: break-word;
    }

    .agent-info i {
        color: #d4af37;
        width: 16px;
        margin-right: 6px;
        font-size: 12px;
    }

    .agent-btn {
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

    .agent-btn:hover {
        background-color: #d4af37;
        color: #0b1f3a;
    }

    .agent-email {
        margin-bottom: 16px !important;
    }

    .no-agent {
        grid-column: 1 / -1;
        text-align: center;
        padding: 36px 20px;
        color: #5f6b7a;
        background: #ffffff;
        border: 1px solid #e4ddd0;
        border-radius: 10px;
    }

    .no-agent h4 {
        color: #0b1f3a;
        font-family: "Playfair Display", Georgia, serif;
        margin-bottom: 8px;
    }

    @media (max-width: 991px) {
        .agents-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .section-title h1 {
            font-size: 32px;
        }
    }

    @media (max-width: 639px) {
        .agents-section {
            padding: 28px 0 36px;
        }

        .agents-grid {
            grid-template-columns: 1fr;
            max-width: 420px;
            margin: 0 auto;
        }

        .section-title h1 {
            font-size: 30px;
        }

        .agent-image {
            height: 200px;
        }
    }
</style>


<section class="agents-section">

    <div class="agents-wrap">


        <!-- Page Heading -->

        <div class="section-title">

            <h1>Meet Our Trusted Agents</h1>

            <div class="title-line"></div>

            <p>
                Connect with our trusted agents and make your property journey easier.
            </p>

        </div>

        <!-- Agent Cards -->

        <div class="agents-grid">

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($agent = mysqli_fetch_assoc($result)) {

            ?>

                    <article class="agent-card">


                            <!-- Agent Letter -->

                            <div class="agent-image">

                            <?php if (!empty($agent['profile_photo'])) { ?>

                                <img
                                    src="admin/assets/images/agents/<?php echo htmlspecialchars($agent['profile_photo']); ?>"
                                    alt="<?php echo htmlspecialchars($agent['full_name']); ?>"
                                    class="agent-photo"
                                >

                            <?php } else { ?>

                                <div class="agent-placeholder">

                                    <?php
                                    echo strtoupper(substr($agent['full_name'], 0, 1));
                                    ?>

                                </div>

                            <?php } ?>

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


                                <p class="agent-email">

                                    <i class="fa-solid fa-envelope"></i>

                                    <?php
                                    echo $agent['email'];
                                    ?>


                                </p>

                                <a href="agent-details.php?id=<?php echo (int)$agent['user_id']; ?>" class="agent-btn">
                                    View Profile
                                </a>

                            </div>

                    </article>


                <?php

                }
            } else {

                ?>

                <div class="no-agent">

                    <h4>
                        No Agents Found
                    </h4>

                    <p>
                        No active agents are available.
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
