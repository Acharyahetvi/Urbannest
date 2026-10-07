<?php

session_start();

require_once __DIR__ . '/../db_connection.php';

$error = "";


/* ================================
   LOGIN
================================ */

if (isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    /* CHECK EMPTY FIELDS */

    if ($email === "" || $password === "") {

        $error = "Please enter your email and password.";

    } else {

        /* FIND ACTIVE USER */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT user_id, full_name, email, password, role_id
             FROM users
             WHERE email = ?
             AND status = 'Active'
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);


        if (mysqli_num_rows($result) === 1) {

            $user = mysqli_fetch_assoc($result);


            /* CHECK PASSWORD */

            if ($password === $user['password']) {

                /* CREATE LOGIN SESSION */

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role_id'];


                /* ADMIN USERS GO TO THE ADMIN DASHBOARD */

                if ((int) $user['role_id'] === 1) {
                    header("Location: /urbannest/admin/dashboard.php");
                } else {
                    header("Location: /urbannest/index.php");
                }

                exit();

            } else {

                $error = "Invalid email or password.";
            }

        } else {

            $error = "Invalid email or password.";
        }


        mysqli_stmt_close($stmt);
    }
}


$pageTitle = "Login - UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>


<style>
    /* =====================================
   LOGIN BACKGROUND
===================================== */

    .login-section {

        min-height: calc(100vh - 96px);

        padding: 38px 24px 24px;

        display: flex;

        align-items: center;

        justify-content: center;

        position: relative;
        box-sizing: border-box;

        background-image:
            linear-gradient(rgba(11, 31, 58, 0.68),
                rgba(11, 31, 58, 0.68)),
            url('/urbannest/assets/images/hero.png');

        background-size: cover;

        background-position: center;

        background-attachment: scroll;
    }


    /* =====================================
   MAIN LOGIN CONTAINER
===================================== */

    .login-container {

        width: 100%;

        max-width: 900px;

        min-height: 440px;

        display: flex;

        overflow: hidden;

        background: white;

        border-radius: 18px;

        border: 1px solid rgba(212, 175, 55, 0.55);

        box-shadow:
            0 25px 65px rgba(0, 0, 0, 0.35);

    }


    /* =====================================
   LEFT SIDE
===================================== */

    .login-image {

        width: 50%;

        min-height: 440px;

        position: relative;

        display: flex;

        align-items: center;

        padding: 30px;

        box-sizing: border-box;

        background-image:
            linear-gradient(rgba(11, 31, 58, 0.48),
                rgba(11, 31, 58, 0.82)),
            url('/urbannest/assets/images/hero.png');

        background-size: cover;

        background-position: center;
    }


    /* =====================================
   GOLD BORDER
===================================== */

    .login-image::before {

        content: "";

        position: absolute;

        top: 25px;

        left: 25px;

        right: 25px;

        bottom: 25px;

        border: 1px solid rgba(212, 175, 55, 0.65);

        border-radius: 12px;
    }


    /* =====================================
   LEFT CONTENT
===================================== */

    .image-content {

        position: relative;

        z-index: 2;

        max-width: 400px;

        color: white;
    }


    .small-title {

        color: #d4af37;

        font-size: 12px;

        font-weight: 600;

        letter-spacing: 3px;

        text-transform: uppercase;

        margin-bottom: 14px;
    }


    .gold-line {

        width: 60px;

        height: 3px;

        background: #d4af37;

        margin-bottom: 18px;
    }


    .image-content h1 {

        color: white;

        font-size: 36px;

        line-height: 1.16;

        font-weight: 700;

        margin: 0 0 14px 0;
    }


    .image-content p {

        color: rgba(255, 255, 255, 0.87);

        font-size: 14px;

        line-height: 1.8;

        margin: 0;

        max-width: 380px;
    }


    /* =====================================
   BADGE
===================================== */

    .luxury-badge {

        display: inline-block;

        margin-top: 20px;

        padding: 9px 16px;

        border: 1px solid rgba(212, 175, 55, 0.55);

        border-radius: 30px;

        background: rgba(0, 0, 0, 0.18);

        color: white;

        font-size: 12px;
    }


    .badge-dot {

        display: inline-block;

        width: 7px;

        height: 7px;

        background: #d4af37;

        border-radius: 50%;

        margin-right: 7px;
    }


    /* =====================================
   RIGHT LOGIN AREA
===================================== */

    .login-form-section {

        width: 50%;

        padding: 26px 40px;

        box-sizing: border-box;

        display: flex;

        align-items: center;

        position: relative;

        background: rgba(255, 255, 255, 0.98);
    }


    /* =====================================
   GOLD SIDE LINE
===================================== */

    .login-form-section::before {

        content: "";

        position: absolute;

        left: 0;

        top: 50%;

        transform: translateY(-50%);

        width: 3px;

        height: 120px;

        background: #d4af37;
    }


    /* =====================================
   FORM
===================================== */

    .login-form {

        width: 100%;

        max-width: 390px;

        margin: auto;
    }


    /* =====================================
   WELCOME HEADING
===================================== */

    .login-form h2 {

        color: #0b1f3a;

        font-size: 32px;

        font-weight: 700;

        margin: 0 0 10px 0;
    }


    .subtitle {

        color: #777;

        font-size: 14px;

        line-height: 1.7;

        margin: 0 0 18px 0;
    }


    /* =====================================
   ACCOUNT LOGIN TITLE
===================================== */

    .form-title {

        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 14px;
    }


    .form-title-line {

        width: 35px;

        height: 2px;

        background: #d4af37;
    }


    .form-title-text {

        color: #999;

        font-size: 11px;

        letter-spacing: 1.5px;

        text-transform: uppercase;
    }


    /* =====================================
   ERROR
===================================== */

    .error {

        background: #fff0f0;

        border: 1px solid #edcccc;

        color: #a00000;

        padding: 10px;

        border-radius: 7px;

        text-align: center;

        font-size: 13px;

        margin-bottom: 14px;
    }


    /* =====================================
   LABEL
===================================== */

    label {

        display: block;

        color: #0b1f3a;

        font-size: 13px;

        font-weight: 600;

        margin-bottom: 6px;
    }


    /* =====================================
   INPUT
===================================== */

    input {

        width: 100%;

        height: 48px;

        padding: 0 15px;

        box-sizing: border-box;

        border: 1px solid #dcdcdc;

        border-radius: 8px;

        background: #faf9f6;

        color: #0b1f3a;

        outline: none;

        margin-bottom: 16px;

        font-size: 14px;
    }


    input::placeholder {

        color: #999;
    }


    input:focus {

        border-color: #d4af37;

        background: white;

        box-shadow:
            0 0 0 3px rgba(212, 175, 55, 0.10);
    }


    /* =====================================
   LOGIN BUTTON
===================================== */

    .login-btn {

        width: 100%;

        height: 50px;

        border: none;

        border-radius: 8px;

        background: #0b1f3a;

        color: white;

        font-size: 15px;

        font-weight: 600;

        cursor: pointer;

        margin-top: 5px;

        box-shadow:
            0 8px 20px rgba(11, 31, 58, 0.18);
    }


    .login-btn:hover {

        background: #d4af37;

        color: #0b1f3a;
    }


    /* =====================================
   REGISTER
===================================== */

    .register {

        text-align: center;

        border-top: 1px solid #eeeeee;

        margin-top: 16px;

        padding-top: 12px;

        color: #777;

        font-size: 13px;
    }


    .register a {

        color: #b28c27;

        font-weight: 600;

        text-decoration: none;
    }


    .register a:hover {

        color: #0b1f3a;
    }


    /* =====================================
   BOTTOM TEXT
===================================== */

    .footer-text {

        text-align: center;

        color: #aaa;

        font-size: 11px;

        margin-top: 10px;
    }


    @media (max-width: 768px) {

        .login-section {
            padding: 28px 20px;
        }

        .login-container {
            max-width: 560px;
            min-height: 0;
            flex-direction: column;
        }

        .login-image,
        .login-form-section {
            width: 100%;
        }

        .login-image {
            min-height: 250px;
            padding: 36px;
        }

        .login-image::before {
            top: 16px;
            right: 16px;
            bottom: 16px;
            left: 16px;
        }

        .image-content {
            max-width: 460px;
        }

        .image-content h1 {
            font-size: 32px;
        }

        .login-form-section {
            padding: 40px 48px;
        }

        .login-form-section::before {
            top: 0;
            left: 50%;
            width: 90px;
            height: 3px;
            transform: translateX(-50%);
        }
    }


    @media (max-width: 480px) {

        .login-section {
            padding: 20px 14px;
        }

        .login-image {
            min-height: 205px;
            padding: 30px;
        }

        .image-content h1 {
            font-size: 28px;
        }

        .image-content p,
        .luxury-badge {
            display: none;
        }

        .login-form-section {
            padding: 34px 26px;
        }

        .login-form h2 {
            font-size: 29px;
        }
    }
</style>


<!-- =====================================
     LOGIN SECTION
===================================== -->

<section class="login-section">


    <div class="login-container">


        <!-- =================================
             LEFT SIDE
        ================================== -->

        <div class="login-image">

            <div class="image-content">

                <div class="small-title">

                    UrbanNest Real Estate

                </div>


                <div class="gold-line"></div>


                <h1>

                    Find a place you'll love to call home.

                </h1>


                <p>

                    Discover beautiful properties and find
                    the perfect space for your next chapter.

                </p>


                <div class="luxury-badge">

                    <span class="badge-dot"></span>

                    Premium Property Experience

                </div>

            </div>

        </div>


        <!-- =================================
             RIGHT SIDE
        ================================== -->

        <div class="login-form-section">


            <div class="login-form">


                <!-- WELCOME -->

                <h2>

                    Welcome Back

                </h2>


                <p class="subtitle">

                    Sign in to your account and continue
                    exploring properties with UrbanNest.

                </p>


                <!-- ACCOUNT LOGIN -->

                <div class="form-title">

                    <div class="form-title-line"></div>

                    <div class="form-title-text">

                        Account Login

                    </div>

                </div>


                <!-- ERROR -->

                <?php

                if ($error != "") {

                    echo "<div class='error'>$error</div>";
                }

                ?>


                <!-- FORM -->

                <form method="POST">


                    <label>

                        Email Address

                    </label>


                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required>


                    <label>

                        Password

                    </label>


                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required>


                    <button
                        type="submit"
                        name="login"
                        class="login-btn">

                        Sign In

                    </button>


                </form>


                <!-- REGISTER -->

                <div class="register">

                    Don't have an account?

                    <a href="/urbannest/auth/register.php">

                        Create Account

                    </a>

                </div>


                <!-- BOTTOM TEXT -->

                <div class="footer-text">

                    Your journey to the perfect home starts here.

                </div>


            </div>

        </div>

    </div>

</section>
