<footer class="un-footer">

    <div class="footer-container">

        <!-- Company Information -->
        <div class="footer-box company-box">

            <h2>Urban<span>Nest</span></h2>

            <p>
                Find your perfect property with UrbanNest.
            </p>

            <p>
                Buy, sell and rent properties easily.
            </p>

        </div>


        <!-- Discover -->
        <div class="footer-box">

            <h3>Discover</h3>

            <a href="index.php">Home</a>

            <a href="about.php">About Us</a>

        </div>


        <!-- Contact -->
        <div class="footer-box">

            <h3>Contact Us</h3>

            <p>Ahmedabad, Gujarat</p>

            <p>+91 98765 43210</p>

            <p>info@urbannest.com</p>

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
    /* =================================
   URBANNEST FOOTER
================================= */

    .un-footer {

        background: #0b1f3a;

        color: white;

        margin-top: 60px;

        padding-top: 55px;

    }


    /* =================================
   FOOTER CONTAINER
================================= */

    .footer-container {

        width: 88%;

        max-width: 1200px;

        margin: auto;

        display: grid;

        grid-template-columns: 2fr 1fr 1.3fr;

        gap: 70px;

        padding-bottom: 45px;

    }


    /* =================================
   COMPANY SECTION
================================= */

    .company-box {

        padding-right: 50px;

    }


    /* Logo */

    .footer-box h2 {

        font-size: 30px;

        font-weight: 700;

        margin-bottom: 18px;

        letter-spacing: 0.5px;

    }


    /* Gold Nest */

    .footer-box h2 span {

        color: #d4af37;

    }


    /* =================================
   FOOTER HEADINGS
================================= */

    .footer-box h3 {

        position: relative;

        font-size: 19px;

        font-weight: 600;

        margin-bottom: 25px;

        padding-bottom: 10px;

    }


    /* Gold Line */

    .footer-box h3::after {

        content: "";

        position: absolute;

        left: 0;

        bottom: 0;

        width: 35px;

        height: 2px;

        background: #d4af37;

    }


    /* =================================
   FOOTER TEXT
================================= */

    .footer-box p {

        color: #d9dce2;

        font-size: 14px;

        line-height: 1.8;

        margin: 0 0 12px 0;

    }


    /* =================================
   FOOTER LINKS
================================= */

    .footer-box a {

        display: block;

        color: #d9dce2;

        text-decoration: none;

        font-size: 14px;

        margin-bottom: 13px;

        transition: 0.3s;

    }


    /* Link Hover */

    .footer-box a:hover {

        color: #d4af37;

        padding-left: 5px;

    }


    /* =================================
   COPYRIGHT
================================= */

    .footer-bottom {

        border-top: 1px solid rgba(255, 255, 255, 0.15);

        text-align: center;

        padding: 20px;

    }


    /* Copyright Text */

    .footer-bottom p {

        margin: 0;

        color: #bfc4cc;

        font-size: 13px;

    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>