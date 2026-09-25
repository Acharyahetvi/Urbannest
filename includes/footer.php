<footer class="un-footer">

    <div class="footer-container">

        <!-- Company Information -->
        <div class="footer-box">
            <h2>Urban<span>Nest</span></h2>

            <p>
                Find your perfect property with UrbanNest.
            </p>
            <br>
            <p>
                Buy, sell and rent properties easily.
            </p>

            <!-- Social Media Icons -->
            <div class="un-social-icons">

                <a href="#">
                    <i class="fab fa-facebook-f"></i>
                </a>

                <a href="#">
                    <i class="fab fa-instagram"></i>
                </a>

                <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                </a>

                <a href="#">
                    <i class="fab fa-youtube"></i>
                </a>

            </div>
        </div>

        <!-- Discover -->
        <div class="footer-box">
            <h3>Discover</h3>

            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="properties.php">Properties</a>
            <a href="agents.php">Agents</a>
        </div>

        <!-- Contact -->
        <div class="footer-box">
            <h3>Contact Us</h3>
            <br>

            <p>Ahmedabad, Gujarat</p>
            <p>+91 98765 43210</p>
            <p>info@urbannest.com</p>
        </div>

        <!-- Newsletter -->
        <div class="footer-box">
            <h3>Newsletter</h3>
            <br>

            <p>
                Subscribe to get the latest property updates.
            </p>

            <form>
                <input
                    type="email"
                    placeholder="Enter your email">

                <button type="submit">
                    Subscribe
                </button>
            </form>
        </div>

    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>
            © 2026 UrbanNest. All Rights Reserved.
        </p>
    </div>

</footer>


<style>
    /* ==============================
   URBANNEST - FOOTER CSS
============================== */

    /* Main Footer */
    .un-footer {
        background: #0b1f3a;
        color: white;
        padding-top: 50px;
        margin-top: 50px;
    }

    /* Footer Container */
    .footer-container {
        width: 90%;
        max-width: 1200px;
        margin: auto;

        display: grid;
        grid-template-columns: 2fr 1fr 1fr 2fr;
        gap: 40px;
    }

    /* Footer Heading */
    .footer-box h2 {
        font-size: 28px;
        margin-bottom: 15px;
    }

    .footer-box h2 span {
        color: #d4af37;
    }

    /* Footer Text */
    .footer-box p {
        line-height: 1.7;
        margin-bottom: 10px;
    }

    /* Footer Links */
    .footer-box a {
        display: block;
        color: white;
        text-decoration: none;
        margin-bottom: 10px;
    }

    .footer-box a:hover {
        color: #d4af37;
    }

    /* Social Media Icons */
    .un-social-icons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    /* Social Icon Circle */
    .un-social-icons a {
        width: 38px;
        height: 38px;

        border-radius: 50%;

        background: white;
        color: #0b1f3a;

        display: flex;
        align-items: center;
        justify-content: center;

        text-decoration: none;
    }

    /* Social Icon Hover */
    .un-social-icons a:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    /* Newsletter Email Box */
    .footer-box input {
        width: 100%;
        padding: 10px;

        border: none;
        margin-bottom: 10px;

        /* Rounded four corners */
        border-radius: 10px;

        box-sizing: border-box;
        outline: none;
    }

    /* Email Box Focus */
    .footer-box input:focus {
        border: 1px solid #d4af37;
    }

    /* Subscribe Button */
    .footer-box button {
        padding: 10px 18px;

        background: #d4af37;
        color: #0b1f3a;

        border: none;
        cursor: pointer;

        /* Rounded four corners */
        border-radius: 10px;
    }

    /* Button Hover */
    .footer-box button:hover {
        background: white;
    }

    /* Bottom Copyright */
    .footer-bottom {
        text-align: center;

        border-top: 1px solid #ffffff33;

        margin-top: 40px;
        padding: 20px;
    }
</style>