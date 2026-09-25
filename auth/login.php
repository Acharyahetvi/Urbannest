<?php
session_start();

require_once __DIR__ . '/../db_connection.php';

$error = "";

// ===============================
// LOGIN FORM
// ===============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // CHECK USER LOGIN
    $sql = "SELECT * FROM users
            WHERE email = ?
            AND password = ?
            AND role_id != 1
            AND status = 'Active'";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            // CREATE SESSION
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_id'];

            // REDIRECT TO HOMEPAGE
            mysqli_stmt_close($stmt);

            header("Location: /urbannest/index.php");
            exit;
        } else {

            $error = "Invalid email or password.";
        }

        mysqli_stmt_close($stmt);
    } else {

        $error = "Database error. Please try again.";
    }
}

$pageTitle = "Login - UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<style>
    /* ===============================
   LOGIN SECTION
=============================== */

    .login-section {
        background:
            linear-gradient(rgba(11, 31, 58, 0.55),
                rgba(11, 31, 58, 0.55)),
            url('/urbannest/assets/images/hero.png');

        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        padding: 60px 0;
        min-height: 80vh;

        display: flex;
        align-items: center;
    }

    /* ===============================
   LOGIN BOX
=============================== */

    .login-box {
        max-width: 450px;
        margin: auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    /* ===============================
   LOGIN HEADING
=============================== */

    .login-box h2 {
        color: #0b1f3a;
        font-weight: 700;
        margin-bottom: 10px;
    }

    /* ===============================
   INPUT FIELDS
=============================== */

    .form-control {
        padding: 12px;
        border-radius: 7px;
        border: 1px solid #ddd;
    }

    .form-control:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.15rem rgba(212, 175, 55, 0.15);
    }

    /* ===============================
   LOGIN BUTTON
=============================== */

    .btn-login {
        width: 100%;
        padding: 12px;
        background: #0b1f3a;
        color: #ffffff;
        border: none;
        border-radius: 7px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-login:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    /* ===============================
   REGISTER LINK
=============================== */

    .register-link {
        color: #d4af37;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link:hover {
        color: #0b1f3a;
    }
</style>


<!-- ===============================
     LOGIN SECTION
=============================== -->

<section class="login-section">

    <div class="container">

        <div class="login-box">

            <!-- HEADING -->

            <h2>Welcome Back</h2>

            <p class="text-muted mb-4">
                Sign in to continue to your account.
            </p>


            <!-- ERROR MESSAGE -->

            <?php if ($error != ""): ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>


            <!-- LOGIN FORM -->

            <form method="POST">

                <!-- EMAIL -->

                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email"
                        autocomplete="username"
                        required>

                </div>


                <!-- PASSWORD -->

                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required>

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="btn-login">

                    Sign In

                </button>

            </form>


            <!-- REGISTER LINK -->

            <p class="text-center mt-4 mb-0">

                Don't have an account?

                <a
                    href="/urbannest/auth/register.php"
                    class="register-link">

                    Create Account

                </a>

            </p>

        </div>

    </div>

</section>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>