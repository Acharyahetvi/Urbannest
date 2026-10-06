<?php

session_start();

require_once __DIR__ . '/../db_connection.php';

$error = "";
$success = "";


/* ================================
   REGISTER
================================ */

if (isset($_POST['register'])) {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';


    /* CHECK EMPTY FIELDS */

    if (
        $full_name === "" ||
        $email === "" ||
        $password === "" ||
        $confirm_password === ""
    ) {

        $error = "Please fill in all fields.";

    }


    /* CHECK PASSWORD */

    elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    }


    /* CHECK PASSWORD LENGTH */

    elseif (strlen($password) < 8) {

        $error = "Password must be at least 8 characters.";

    }


    /* CHECK EMAIL */

    else {

        $check = mysqli_prepare(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $check,
            "s",
            $email
        );

        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $error = "This email is already registered.";

        } else {

            /* DEFAULT BUYER ROLE */

            $role_id = 2;
            $status = "Active";


            /* INSERT USER */

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (role_id, full_name, email, password, status)
                VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "issss",
                $role_id,
                $full_name,
                $email,
                $password,
                $status
            );


            if (mysqli_stmt_execute($insert)) {

                $success = "Registration successful! You can now login.";

                $full_name = "";
                $email = "";

            } else {

                $error = "Registration failed. Please try again.";
            }

            mysqli_stmt_close($insert);
        }

        mysqli_stmt_close($check);
    }
}


$pageTitle = "Register - UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>

<style>
    .un-topbar {
        display: none !important;
    }


    /* =====================================
   REGISTER BACKGROUND
===================================== */

    .register-section {

        padding: 40px 24px 14px;

        display: flex;

        align-items: center;

        justify-content: center;

        position: relative;

        background-image:
            linear-gradient(rgba(11, 31, 58, 0.68),
                rgba(11, 31, 58, 0.68)),
            url('/urbannest/assets/images/hero.png');

        background-size: cover;

        background-position: center;

        background-attachment: scroll;
    }


    /* =====================================
   MAIN CONTAINER
===================================== */

    .register-container {

        width: 100%;

        max-width: 900px;

        min-height: 480px;

        display: flex;

        overflow: hidden;

        background: white;

        border-radius: 18px;

        border: 1px solid rgba(212, 175, 55, 0.55);

        box-shadow:
            0 25px 65px rgba(0, 0, 0, 0.35);
    }


    /* =====================================
   LEFT IMAGE
===================================== */

    .register-image {

        width: 50%;

        min-height: 480px;

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

    .register-image::before {

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

        font-size: 39px;

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


    /* =====================================
   RIGHT REGISTER AREA
===================================== */

    .register-form-section {

        width: 50%;

        padding: 20px 40px;

        box-sizing: border-box;

        display: flex;

        align-items: center;

        position: relative;

        background: rgba(255, 255, 255, 0.98);
    }


    /* =====================================
   GOLD SIDE LINE
===================================== */

    .register-form-section::before {

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

    .register-form {

        width: 100%;

        max-width: 390px;

        margin: auto;
    }


    /* =====================================
   HEADING
===================================== */

    .register-form h2 {

        color: #0b1f3a;

        font-size: 34px;

        font-weight: 700;

        margin: 0 0 10px 0;
    }


    .subtitle {

        color: #777;

        font-size: 14px;

        line-height: 1.7;

        margin: 0 0 16px 0;
    }


    /* =====================================
   FORM TITLE
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
   SUCCESS
===================================== */

    .success {

        background: #effaf2;

        border: 1px solid #c9e6d0;

        color: #27743b;

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

        margin-bottom: 5px;
    }


    /* =====================================
   INPUT
===================================== */

    input {

        width: 100%;

        height: 47px;

        padding: 0 14px;

        box-sizing: border-box;

        border: 1px solid #dcdcdc;

        border-radius: 8px;

        background: #faf9f6;

        color: #0b1f3a;

        outline: none;

        margin-bottom: 10px;

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
   REGISTER BUTTON
===================================== */

    .register-btn {

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


    .register-btn:hover {

        background: #d4af37;

        color: #0b1f3a;
    }


    /* =====================================
   LOGIN LINK
===================================== */

    .login-link {

        text-align: center;

        border-top: 1px solid #eeeeee;

        margin-top: 14px;

        padding-top: 12px;

        color: #777;

        font-size: 13px;
    }


    .login-link a {

        color: #b28c27;

        font-weight: 600;

        text-decoration: none;
    }


    .login-link a:hover {

        color: #0b1f3a;
    }


    /* =====================================
   MOBILE
===================================== */

    @media (max-width: 850px) {

        .register-container {

            flex-direction: column;

            max-width: 600px;
        }


        .register-image {

            width: 100%;

            min-height: 330px;

            padding: 40px;
        }


        .register-form-section {

            width: 100%;

            padding: 45px;
        }


        .register-form-section::before {

            display: none;
        }

    }


    @media (max-width: 576px) {

        .register-section {

            padding: 25px 15px;
        }


        .register-image {

            min-height: 300px;

            padding: 30px;
        }


        .register-image::before {

            top: 18px;

            left: 18px;

            right: 18px;

            bottom: 18px;
        }


        .image-content h1 {

            font-size: 29px;
        }


        .register-form-section {

            padding: 35px 25px;
        }


        .register-form h2 {

            font-size: 28px;
        }

    }
</style>


<!-- =====================================
     REGISTER SECTION
===================================== -->

<section class="register-section">


    <div class="register-container">


        <!-- =================================
             LEFT SIDE
        ================================== -->

        <div class="register-image">


            <div class="image-content">


                <div class="small-title">

                    UrbanNest Real Estate

                </div>


                <div class="gold-line"></div>


                <h1>

                    Your next chapter starts with the right home.

                </h1>


                <p>

                    Create your UrbanNest account and explore
                    beautiful properties that match your needs.

                </p>


                <div class="luxury-badge">

                    Premium Property Experience

                </div>


            </div>

        </div>


        <!-- =================================
             RIGHT SIDE
        ================================== -->

        <div class="register-form-section">


            <div class="register-form">


                <!-- HEADING -->

                <h2>

                    Create Account

                </h2>


                <p class="subtitle">

                    Join UrbanNest and start exploring
                    your perfect property.

                </p>


                <!-- FORM TITLE -->

                <div class="form-title">

                    <div class="form-title-line"></div>

                    <div class="form-title-text">

                        Create Your Account

                    </div>

                </div>


                <!-- ERROR -->

                <?php

                if ($error != "") {

                    echo "<div class='error'>$error</div>";
                }

                ?>


                <!-- SUCCESS -->

                <?php

                if ($success != "") {

                    echo "<div class='success'>$success</div>";
                }

                ?>


                <!-- REGISTER FORM -->

                <form method="POST">


                    <!-- FULL NAME -->

                    <label>

                        Full Name

                    </label>


                    <input
                        type="text"
                        name="full_name"
                        placeholder="Enter your full name"
                        required>


                    <!-- EMAIL -->

                    <label>

                        Email Address

                    </label>


                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required>


                    <!-- PASSWORD -->

                    <label>

                        Password

                    </label>


                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required>


                    <!-- CONFIRM PASSWORD -->

                    <label>

                        Confirm Password

                    </label>


                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Re-enter your password"
                        required>


                    <!-- BUTTON -->

                    <button
                        type="submit"
                        name="register"
                        class="register-btn">

                        Create Account

                    </button>


                </form>


                <!-- LOGIN -->

                <div class="login-link">

                    Already have an account?

                    <a href="/urbannest/auth/login.php">

                        Login Here

                    </a>

                </div>


            </div>

        </div>

    </div>

</section>
