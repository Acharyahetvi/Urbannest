<?php
$pageTitle = 'Create account — UrbanNest';
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
                    <h2 class="section-title mb-1" style="font-size:1.8rem;">Create your account</h2>
                    <p class="text-muted mb-4">Save homes, track inquiries, and view exclusive listings.</p>

                    <form onsubmit="event.preventDefault(); alert('Demo — connect this form to your registration handler.');">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-uppercase text-muted">First name</label>
                                <input class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-uppercase text-muted">Last name</label>
                                <input class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-uppercase text-muted">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-uppercase text-muted">Phone</label>
                            <input class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-uppercase text-muted">I am a</label>
                            <select class="form-select">
                                <option>Home Buyer</option>
                                <option>Home Seller</option>
                                <option>Renter</option>
                                <option>Investor</option>
                                <option>Agent</option>
                                <option>Owner</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted">Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="form-check small mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">I agree to UrbanNest's <a href="#" class="text-navy">Terms</a> and <a href="#" class="text-navy">Privacy Policy</a>.</label>
                        </div>
                        <button class="btn btn-gold w-100 mb-3">Create account</button>
                    </form>
                    <p class="text-center small text-muted mb-0">Already registered? <a href="<?= url('auth/login.php') ?>" class="text-gold fw-semibold">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
