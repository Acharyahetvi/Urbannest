<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanNest Admin Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

    <div class="login-container">

        <img src="assets\images\real_estate.jpg" alt="UrbanNest">


        <div class="login-right">
            
        <h1>UrbanNest</h1>
        <p>Real Estate Admin Panel</p>
        <h2>Welcome Back 👋</h2>
        <p class="subtitle">
            Sign in to continue managing UrbanNest.
        </p>

        <form action="" method="POST">

            <div class="input-box">
                <i class="fa-solid fa-envelope"></i>
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    required>
            </div>

            <div class="input-box">
                <i class="fa-solid fa-lock"></i>
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required>
            </div>

            <div class="options">
                <label>
                    <input type="checkbox">
                    Remember Me
                </label>
            </div>

            <button type="submit">
                Login
            </button>

        </form>
    </div>
</div>
</body>
</html>