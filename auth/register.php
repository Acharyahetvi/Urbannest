<?php
session_start();

require_once __DIR__ . '/../db_connection.php';

$error = "";
$success = "";

$full_name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Check empty fields
    if (
        empty($full_name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $error = "Please fill in all fields.";
    }

    // Validate email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }

    // Password length
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }

    // Confirm password
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {

        // Check if email already exists
        $sql = "SELECT user_id FROM users WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            $error = "This email is already registered.";
        } else {

            // Hash password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Default role_id = 2 (normal user)
            $role_id = 2;
            $status = "Active";

            // Insert new user
            $sql = "INSERT INTO users
                    (full_name, email, password, role_id, status)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssis",
                $full_name,
                $email,
                $hashed_password,
                $role_id,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {

                $success = "Registration successful! You can now login.";

                $full_name = "";
                $email = "";
            } else {

                $error = "Registration failed. Please try again.";
            }
        }

        mysqli_stmt_close($stmt);
    }
}

$pageTitle = "Register | UrbanNest";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<style>
    /* REGISTER SECTION */

    .register-section {
        background:
            linear-gradient(rgba(11, 31, 58, 0.60),
                rgba(11, 31, 58, 0.60)),
            url('/urbannest/assets/images/hero.png') center / cover no-repeat;

        min-height: 100vh;
        padding: 60px 15px;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* REGISTER CARD */

    .register-card {
        width: 100%;
        max-width: 500px;

        background: #ffffff;
        padding: 35px;

        border-radius: 15px;

        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
    }

    /* HEADING */

    .register-title {
        text-align: center;
        color: #0b1f3a;
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .register-subtitle {
        text-align: center;
        color: #777;
        font-size: 14px;
        margin-bottom: 25px;
    }

    /* LABEL */

    .register-card label {
        font-weight: 600;
        color: #0b1f3a;
        margin-bottom: 7px;
    }

    /* INPUT */

    .register-card .form-control {
        height: 48px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 18px;
    }

    .register-card .form-control:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }

    /* BUTTON */

    .register-btn {
        width: 100%;
        background: #0b1f3a;
        color: #ffffff;

        border: none;
        border-radius: 8px;

        padding: 13px;
        font-size: 16px;
        font-weight: 600;

        transition: 0.3s;
    }

    .register-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    /* LOGIN LINK */

    .login-link {
        text-align: center;
        margin-top: 22px;
        font-size: 14px;
    }

    .login-link a {
        color: #0b1f3a;
        font-weight: 700;
        text-decoration: none;
    }

    .login-link a:hover {
        color: #d4af37;
    }

    /* RESPONSIVE */

    @media (max-width: 576px) {
        .register-card {
            padding: 25px 20px;
        }

        .register-title {
            font-size: 25px;
        }
    }
</style>

<section class="register-section">

    <div class="register-card">

        <h2 class="register-title">Create Account</h2>

        <p class="register-subtitle">
            Join UrbanNest and discover your dream property.
        </p>

        <!-- Error Message -->

        <?php if (!empty($error)) { ?>

            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php } ?>

        <!-- Success Message -->

        <?php if (!empty($success)) { ?>

            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>

        <?php } ?>

        <!-- Registration Form -->

        <form method="POST" action="">

            <!-- Full Name -->

            <label for="full_name">Full Name</label>

            <input
                type="text"
                name="full_name"
                id="full_name"
                class="form-control"
                placeholder="Enter your full name"
                value="<?php echo htmlspecialchars($full_name); ?>"
                required>

            <!-- Email -->

            <label for="email">Email Address</label>

            <input
                type="email"
                name="email"
                id="email"
                class="form-control"
                placeholder="Enter your email"
                value="<?php echo htmlspecialchars($email); ?>"
                required>

            <!-- Password -->

            <label for="password">Password</label>

            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
                placeholder="Enter password (minimum 8 characters)"
                minlength="8"
                required>

            <!-- Confirm Password -->

            <label for="confirm_password">Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                class="form-control"
                placeholder="Re-enter your password"
                minlength="8"
                required>

            <!-- Submit Button -->

            <button type="submit" class="register-btn">
                Create Account
            </button>

        </form>

        <!-- Login Link -->

        <div class="login-link">

            Already have an account?

            <a href="/urbannest/auth/login.php">
                Login Here
            </a>

        </div>

    </div>

</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>