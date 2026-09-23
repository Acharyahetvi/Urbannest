<?php

session_start();

require_once __DIR__ . "/../includes/db.php";

// ===============================
// ERROR VARIABLE
// ===============================

$error = "";


// ===============================
// LOGIN FORM
// ===============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';


  // CHECK EMPTY FIELDS

  if ($email === "" || $password === "") {

    $error = "Please enter email and password.";
  } else {

    // ===============================
    // CHECK ADMIN LOGIN
    // ===============================

    $sql = "SELECT *
                FROM users
                WHERE email = ?
                AND password = ?
                AND role_id = 1
                AND status = 'Active'";

    $stmt = mysqli_prepare($con, $sql);


    if ($stmt) {

      mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $email,
        $password
      );

      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);


      // ===============================
      // IF ADMIN FOUND
      // ===============================

      if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);


        // ===============================
        // CREATE ADMIN SESSION
        // ===============================

        session_regenerate_id(true);

        $_SESSION['admin_logged_in'] = true;

        $_SESSION['admin_id'] =
          $user['user_id'];

        $_SESSION['admin_name'] =
          $user['full_name'];

        $_SESSION['admin_email'] =
          $user['email'];

        $_SESSION['admin_role'] =
          $user['role_id'];


        // ===============================
        // REDIRECT TO DASHBOARD
        // ===============================

        mysqli_stmt_close($stmt);

        header(
          "Location: /urbannest/admin/dashboard.php"
        );

        exit;
      } else {

        $error = "Invalid email or password.";
      }


      mysqli_stmt_close($stmt);
    } else {

      $error = "Database error.";
    }
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>UrbanNest - Admin Login</title>


  <!-- GOOGLE FONT -->

  <link rel="preconnect"
    href="https://fonts.googleapis.com">

  <link rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">


  <!-- FONT AWESOME -->

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


  <!-- LOGIN CSS -->

  <link rel="stylesheet"
    href="/urbannest/admin/assets/css/login.css">

</head>


<body>


  <!-- LOGIN PAGE -->

  <div class="form-panel">

    <div class="login-card">


      <!-- LOGO -->

      <div class="card-brand">

        <img
          src="/urbannest/assets/images/img1.png"
          alt="UrbanNest Logo">

        <h1>
          <span class="urban">Urban</span><span class="nest">Nest</span>
        </h1>

      </div>


      <p class="tagline">
        REAL ESTATE ADMIN PANEL
      </p>


      <!-- HEADING -->

      <h2>Welcome Back</h2>

      <p class="subtitle">
        Sign in to access the admin dashboard.
      </p>


      <!-- ERROR MESSAGE -->

      <?php if ($error !== "") { ?>

        <div class="error-message">

          <i class="fa-solid fa-circle-exclamation"></i>

          <?php
          echo htmlspecialchars($error);
          ?>

        </div>

      <?php } ?>


      <!-- LOGIN FORM -->

      <form method="POST"
        action="/urbannest/admin/login.php">


        <!-- EMAIL -->

        <div class="field">

          <label for="email">
            Email Address
          </label>

          <div class="input-box">

            <i class="fa-solid fa-envelope"></i>

            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your email"
              autocomplete="username"
              required>

          </div>

        </div>


        <!-- PASSWORD -->

        <div class="field">

          <label for="password">
            Password
          </label>

          <div class="input-box">

            <i class="fa-solid fa-lock"></i>

            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              autocomplete="current-password"
              required>

            <button
              type="button"
              id="togglePassword"
              class="password-toggle"
              aria-label="Show password">

              <i class="fa-solid fa-eye"></i>

            </button>

          </div>

        </div>


        <!-- LOGIN BUTTON -->

        <button
          type="submit"
          class="login-btn">

          Sign In

          <i class="fa-solid fa-arrow-right"></i>

        </button>


      </form>


      <!-- FOOTER -->

      <p class="footer-note">
        UrbanNest © 2026
      </p>


    </div>

  </div>


  <!-- SHOW / HIDE PASSWORD -->

  <script>
    const togglePassword =
      document.getElementById("togglePassword");

    const password =
      document.getElementById("password");

    togglePassword.addEventListener(
      "click",
      function() {

        if (password.type === "password") {

          password.type = "text";

          togglePassword.innerHTML =
            '<i class="fa-solid fa-eye-slash"></i>';

          togglePassword.setAttribute(
            "aria-label",
            "Hide password"
          );

        } else {

          password.type = "password";

          togglePassword.innerHTML =
            '<i class="fa-solid fa-eye"></i>';

          togglePassword.setAttribute(
            "aria-label",
            "Show password"
          );

        }

      }
    );
  </script>


</body>

</html>