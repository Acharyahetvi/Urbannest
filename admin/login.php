<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UrbanNest — Admin Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
  :root{
    --navy-deep:#0E2039;
    --navy:#17315A;
    --royal:#24497F;
    --gold:#D4AF37;
    --gold-light:#F1D98A;
    --white:#FFFFFF;
    --gray:#F4F7FB;
    --ink-soft:#6B7A99;
    --radius-lg:28px;
    --radius-md:16px;
    --shadow-soft:0 20px 50px -20px rgba(23,49,90,0.35);
  }

  *{ box-sizing:border-box; margin:0; padding:0; }

  html,body{ height:100%; }

  body{
    font-family:'Poppins', sans-serif;
    background:var(--gray);
    color:var(--navy);
    -webkit-font-smoothing:antialiased;
  }

  .page{
    display:flex;
    min-height:100vh;
    width:100%;
  }

  /* ================= LEFT SHOWCASE PANEL ================= */
  .showcase{
    position:relative;
    flex:1 1 48%;
    min-height:100vh;
    overflow:hidden;
    background:
      radial-gradient(circle at 80% 15%, rgba(212,175,55,0.16), transparent 45%),
      linear-gradient(160deg, var(--navy-deep) 0%, var(--navy) 48%, var(--royal) 100%);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:56px 56px 40px;
  }

  /* faint architectural blueprint grid, ties to the real-estate/plans theme */
  .showcase::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:
      linear-gradient(rgba(255,255,255,0.045) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.045) 1px, transparent 1px);
    background-size:42px 42px;
    mask-image:radial-gradient(circle at 50% 40%, black 10%, transparent 75%);
    pointer-events:none;
  }

  .brand-mark{
    position:relative;
    display:flex;
    align-items:center;
    gap:12px;
    z-index:2;
  }





  .logo-icon{
    width:38px;
    height:38px;
    border-radius:10px;
    background:linear-gradient(135deg, var(--gold), var(--gold-light));
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 6px 18px rgba(212,175,55,0.35);
    flex-shrink:0;
  }

  .logo-icon i{ color:var(--navy-deep); font-size:16px; }

  .brand-mark span{
    color:var(--white);
    font-weight:600;
    letter-spacing:2px;
    font-size:15px;
  }

  /* ---- illustration stage ---- */
  .stage{
    position:relative;
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:2;
  }

  .orbit{
    position:absolute;
    width:420px;
    height:420px;
    border:1px dashed rgba(212,175,55,0.22);
    border-radius:50%;
    animation:spin 60s linear infinite;
  }

  @keyframes spin{ to{ transform:rotate(360deg); } }

  .building-illustration{
    width:280px;
    height:auto;
    filter:drop-shadow(0 30px 40px rgba(0,0,0,0.35));
    animation:float 6s ease-in-out infinite;
  }

  @keyframes float{
    0%,100%{ transform:translateY(0px); }
    50%{ transform:translateY(-14px); }
  }

  .chip{
    position:absolute;
    display:flex;
    align-items:center;
    gap:10px;
    padding:11px 16px;
    border-radius:14px;
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.16);
    backdrop-filter:blur(10px);
    -webkit-backdrop-filter:blur(10px);
    color:var(--white);
    font-size:13px;
    font-weight:500;
    box-shadow:0 12px 30px -12px rgba(0,0,0,0.4);
  }

  .chip i{ color:var(--gold); font-size:13px; }

  .chip-1{ top:8%; left:2%; animation:float 5.5s ease-in-out infinite; }
  .chip-2{ bottom:14%; right:0%; animation:float 7s ease-in-out infinite 0.6s; }
  .chip-3{ top:46%; right:-4%; animation:float 6.2s ease-in-out infinite 1.2s; }

  .showcase-footer{
    position:relative;
    z-index:2;
    color:rgba(255,255,255,0.72);
    max-width:420px;
  }

  .showcase-footer p{
    font-size:15px;
    line-height:1.6;
    font-weight:300;
    border-left:2px solid var(--gold);
    padding-left:16px;
  }

  .showcase-footer span{
    display:block;
    margin-top:10px;
    font-size:12px;
    letter-spacing:1px;
    color:rgba(255,255,255,0.45);
    padding-left:16px;
  }

  /* ================= RIGHT FORM PANEL ================= */
  .form-panel{
    flex:1 1 52%;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 24px;
    background:var(--gray);
  }

  .login-card{
    width:100%;
    max-width:420px;
    background:var(--white);
    border-radius:var(--radius-lg);
    padding:48px 42px 38px;
    box-shadow:var(--shadow-soft);
    border:1px solid rgba(23,49,90,0.05);
    animation:rise 0.7s cubic-bezier(.2,.8,.2,1);
  }

  @keyframes rise{
    from{ opacity:0; transform:translateY(18px); }
    to{ opacity:1; transform:translateY(0); }
  }

  .card-brand{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:6px;
  }

  .card-brand h1{
    font-size:22px;
    font-weight:700;
    color:var(--navy);
    letter-spacing:0.3px;
  }

  .tagline{
    font-size:11.5px;
    font-weight:600;
    letter-spacing:1.6px;
    text-transform:uppercase;
    color:var(--gold);
    margin-bottom:26px;
  }

  .login-card h2{
    font-size:25px;
    font-weight:600;
    color:var(--navy);
    margin-bottom:8px;
  }

  .subtitle{
    font-size:14px;
    color:var(--ink-soft);
    font-weight:300;
    margin-bottom:30px;
    line-height:1.5;
  }

  .field{ margin-bottom:18px; }

  .field label{
    display:block;
    font-size:12.5px;
    font-weight:500;
    color:var(--navy);
    margin-bottom:7px;
  }

  .input-box{
    display:flex;
    align-items:center;
    gap:12px;
    background:var(--gray);
    border:1.5px solid transparent;
    border-radius:var(--radius-md);
    padding:13px 16px;
    transition:border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
  }

  .input-box i{
    color:var(--ink-soft);
    font-size:14.5px;
    width:16px;
    transition:color 0.25s ease;
  }

  .input-box input{
    border:none;
    outline:none;
    background:transparent;
    font-family:'Poppins', sans-serif;
    font-size:14px;
    color:var(--navy);
    width:100%;
  }

  .input-box input::placeholder{ color:#A7B3C9; font-weight:300; }

  .input-box:focus-within{
    border-color:var(--gold);
    background:var(--white);
    box-shadow:0 0 0 4px rgba(212,175,55,0.12);
  }

  .input-box:focus-within i{ color:var(--gold); }

  .toggle-password{ cursor:pointer; }
  .toggle-password:hover{ color:var(--royal); }

  .form-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:6px 0 26px;
  }

  .remember{
    display:flex;
    align-items:center;
    gap:9px;
    font-size:13px;
    color:var(--ink-soft);
    cursor:pointer;
    user-select:none;
  }

  .remember input{ display:none; }

  .checkmark{
    width:17px;
    height:17px;
    border-radius:5px;
    border:1.5px solid #C7D0E0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:all 0.2s ease;
    flex-shrink:0;
  }

  .checkmark::after{
    content:"\f00c";
    font-family:"Font Awesome 6 Free";
    font-weight:900;
    font-size:9px;
    color:var(--white);
    opacity:0;
    transform:scale(0.6);
    transition:all 0.2s ease;
  }

  .remember input:checked + .checkmark{
    background:var(--gold);
    border-color:var(--gold);
  }

  .remember input:checked + .checkmark::after{ opacity:1; transform:scale(1); }

  .forgot{
    font-size:13px;
    color:var(--royal);
    text-decoration:none;
    font-weight:500;
    transition:color 0.2s ease;
  }

  .forgot:hover{ color:var(--gold); }

  .login-btn{
    position:relative;
    width:100%;
    padding:15px;
    border:none;
    border-radius:var(--radius-md);
    background:linear-gradient(135deg, var(--gold), var(--gold-light));
    color:var(--navy-deep);
    font-family:'Poppins', sans-serif;
    font-size:15px;
    font-weight:600;
    letter-spacing:0.3px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    overflow:hidden;
    box-shadow:0 14px 30px -10px rgba(212,175,55,0.55);
    transition:transform 0.2s ease, box-shadow 0.2s ease;
  }

  .login-btn i{ font-size:13px; transition:transform 0.25s ease; }

  .login-btn::before{
    content:"";
    position:absolute;
    top:0; left:-120%;
    width:60%; height:100%;
    background:linear-gradient(120deg, transparent, rgba(255,255,255,0.55), transparent);
    transform:skewX(-20deg);
    transition:left 0.6s ease;
  }

  .login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 36px -10px rgba(212,175,55,0.7);
  }

  .login-btn:hover::before{ left:130%; }
  .login-btn:hover i{ transform:translateX(4px); }
  .login-btn:active{ transform:translateY(0); }

  .footer-note{
    text-align:center;
    font-size:11.5px;
    color:#A7B3C9;
    margin-top:26px;
    font-weight:300;
  }

  /* ================= RESPONSIVE ================= */
  @media (max-width: 980px){
    .page{ flex-direction:column; }
    .showcase{ min-height:340px; padding:36px 32px 28px; }
    .stage{ transform:scale(0.85); }
    .orbit{ width:320px; height:320px; }
    .chip-3{ display:none; }
    .form-panel{ padding:36px 20px 56px; }
  }

  @media (max-width: 480px){
    .login-card{ padding:38px 26px 30px; border-radius:20px; }
    .showcase{ min-height:300px; }
    .chip-1, .chip-2{ font-size:11.5px; padding:8px 12px; }
  }

  @media (prefers-reduced-motion: reduce){
    .building-illustration, .chip, .orbit, .login-card{ animation:none !important; }
  }
</style>

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

<script>
  const toggle = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  toggle.addEventListener('click', () => {
    const isHidden = password.type === 'password';
    password.type = isHidden ? 'text' : 'password';
    toggle.classList.toggle('fa-eye');
    toggle.classList.toggle('fa-eye-slash');
  });
</script>

</body>
</html>