<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UrbanNest — Admin Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="assets/css/login.css">

</head>
<body>

<div class="page">

  <!-- LEFT SHOWCASE PANEL -->
  <div class="showcase">

    <div class="brand-mark">
      <div class="logo-icon"><i class="fa-solid fa-building"></i></div>
      <span>URBANNEST</span>
    </div>

    <div class="stage">
      <div class="orbit"></div>

      <svg class="building-illustration" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="towerA" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#3A5C91"/>
            <stop offset="100%" stop-color="#152B4E"/>
          </linearGradient>
          <linearGradient id="towerB" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#2C4E80"/>
            <stop offset="100%" stop-color="#12233F"/>
          </linearGradient>
          <linearGradient id="goldFade" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#F1D98A"/>
          </linearGradient>
        </defs>

        <ellipse cx="200" cy="352" rx="130" ry="16" fill="#0A1830" opacity="0.5"/>

        <!-- side tower -->
        <rect x="46" y="176" width="82" height="164" rx="6" fill="url(#towerB)"/>
        <rect x="46" y="170" width="82" height="8" rx="4" fill="url(#goldFade)"/>
        <g fill="#D4AF37" opacity="0.85">
          <rect x="60" y="196" width="12" height="12" rx="2"/>
          <rect x="88" y="196" width="12" height="12" rx="2"/>
          <rect x="60" y="222" width="12" height="12" rx="2" opacity="0.5"/>
          <rect x="88" y="222" width="12" height="12" rx="2"/>
          <rect x="60" y="248" width="12" height="12" rx="2"/>
          <rect x="88" y="248" width="12" height="12" rx="2" opacity="0.5"/>
          <rect x="60" y="274" width="12" height="12" rx="2" opacity="0.5"/>
          <rect x="88" y="274" width="12" height="12" rx="2"/>
        </g>

        <!-- main tower -->
        <rect x="132" y="92" width="150" height="248" rx="8" fill="url(#towerA)"/>
        <rect x="132" y="84" width="150" height="10" rx="5" fill="url(#goldFade)"/>
        <rect x="202" y="46" width="6" height="40" fill="#D4AF37"/>
        <circle cx="205" cy="42" r="5" fill="#F1D98A"/>

        <g fill="#F1D98A">
          <rect x="150" y="120" width="16" height="16" rx="2"/>
          <rect x="182" y="120" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="214" y="120" width="16" height="16" rx="2"/>
          <rect x="246" y="120" width="16" height="16" rx="2" opacity="0.55"/>

          <rect x="150" y="152" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="182" y="152" width="16" height="16" rx="2"/>
          <rect x="214" y="152" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="246" y="152" width="16" height="16" rx="2"/>

          <rect x="150" y="184" width="16" height="16" rx="2"/>
          <rect x="182" y="184" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="214" y="184" width="16" height="16" rx="2"/>
          <rect x="246" y="184" width="16" height="16" rx="2" opacity="0.55"/>

          <rect x="150" y="216" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="182" y="216" width="16" height="16" rx="2"/>
          <rect x="214" y="216" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="246" y="216" width="16" height="16" rx="2"/>

          <rect x="150" y="248" width="16" height="16" rx="2"/>
          <rect x="182" y="248" width="16" height="16" rx="2" opacity="0.55"/>
          <rect x="214" y="248" width="16" height="16" rx="2"/>
          <rect x="246" y="248" width="16" height="16" rx="2" opacity="0.55"/>
        </g>

        <!-- entrance -->
        <rect x="188" y="300" width="44" height="40" rx="4" fill="#0E2039"/>
        <rect x="205" y="300" width="2" height="40" fill="#D4AF37" opacity="0.6"/>
      </svg>

      <div class="chip chip-1"><i class="fa-solid fa-house"></i> 1,248 Listings</div>
      <div class="chip chip-2"><i class="fa-solid fa-arrow-trend-up"></i> 32% YoY Growth</div>
      <div class="chip chip-3"><i class="fa-solid fa-check"></i> 96% Occupancy</div>
    </div>

    <div class="showcase-footer">
      <p>Manage properties, agents, and listings from a single command center.</p>
      <span>TRUSTED BY 200+ AGENCIES ACROSS 40 CITIES</span>
    </div>

  </div>

  <!-- RIGHT FORM PANEL -->
  <div class="form-panel">
    <div class="login-card">

      <div class="card-brand">
         <img src="/urbannest/assets/images/img1.png" class="logo-icon">
        <!--<div class="logo-icon"><i class="fa-solid fa-building"></i></div>-->
        <h1>UrbanNest</h1>
      </div>
      <p class="tagline">Real Estate Admin Panel</p>

      <h2>Welcome back</h2>
      <p class="subtitle">Sign in to continue managing your properties and listings.</p>

      <form action="#" method="POST" autocomplete="off">

        <div class="field">
          <label for="email">Email Address</label>
          <div class="input-box">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="you@urbannest.com" required>
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
          </div>
        </div>

        <div class="form-row">
          <label class="remember">
            <input type="checkbox" name="remember">
            <span class="checkmark"></span>
            Remember me
          </label>
          <a href="#" class="forgot">Forgot password?</a>
        </div>

        <button type="submit" class="login-btn">
          <span>Sign In</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>

      </form>

      <p class="footer-note">Protected by enterprise-grade encryption · UrbanNest © 2026</p>

    </div>
  </div>

</div>

<script src="assets/js/login.js"></script>

</body>
</html>