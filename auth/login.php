<?php
$pageTitle = 'Sign in — UrbanNest';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="un-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="un-auth-card">
                    <a class="un-brand mb-3 d-inline-flex" href="<?= url('index.php') ?>">
                         <img src="/urbannest/assets/images/img1.png" class="un-brand-mark">
                        <span class="un-brand-text">Urban<span>Nest</span></span>
                    </a>
                    <h2 class="section-title mb-1" style="font-size:1.8rem;">Welcome back</h2>
                    <p class="text-muted mb-4">Sign in to continue to your dashboard.</p>

                    <form action="#" method="post" onsubmit="event.preventDefault(); alert('Demo — connect this form to your auth handler.');">
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-uppercase text-muted">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4 small">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-navy">Forgot password?</a>
                        </div>
                        <button class="btn btn-gold w-100 mb-3">Sign in <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        <div class="text-center text-muted small mb-3">— or continue with —</div>
                        <div class="row g-2">
                            <div class="col-6"><button type="button" class="btn btn-outline-navy w-100"><i class="fa-brands fa-google me-2"></i>Google</button></div>
                            <div class="col-6"><button type="button" class="btn btn-outline-navy w-100"><i class="fa-brands fa-apple me-2"></i>Apple</button></div>
                        </div>
                    </form>
                    <p class="text-center small text-muted mt-4 mb-0">Don't have an account? <a href="<?= url('urbannest/auth/register.php') ?>" class="text-gold fw-semibold">Create one</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
