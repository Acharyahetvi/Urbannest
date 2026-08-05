<?php
$pageTitle = 'Contact — UrbanNest';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">Contact</li></ol></nav>
        <h1>Let's find your next home</h1>
        <p class="opacity-75 mb-0" style="max-width:640px;">Send us a note, or reserve a private consultation with one of our advisors.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <span class="eyebrow">Get in touch</span>
                <h2 class="section-title mb-3">We'd love to hear from you.</h2>
                <p class="section-lead mb-4">Our concierge team responds within one business day. For urgent matters, please call.</p>

                <div class="un-feature mb-3"><div class="d-flex gap-3 align-items-start"><div class="un-feature-icon flex-shrink-0"><i class="fa-solid fa-location-dot"></i></div><div><h5 class="mb-1">Flagship Office</h5><p>432 Park Avenue, Manhattan, NY 10022</p></div></div></div>
                <div class="un-feature mb-3"><div class="d-flex gap-3 align-items-start"><div class="un-feature-icon flex-shrink-0"><i class="fa-solid fa-phone"></i></div><div><h5 class="mb-1">Phone</h5><p>+1 (212) 555‑0117<br><span class="text-muted small">Mon – Sat · 9am to 8pm ET</span></p></div></div></div>
                <div class="un-feature"><div class="d-flex gap-3 align-items-start"><div class="un-feature-icon flex-shrink-0"><i class="fa-regular fa-envelope"></i></div><div><h5 class="mb-1">Email</h5><p>hello@urbannest.com<br><span class="text-muted small">Concierge · reply within 24h</span></p></div></div></div>
            </div>
            <div class="col-lg-7">
                <div class="un-detail-box">
                    <form onsubmit="event.preventDefault(); this.reset(); alert('Thank you. An advisor will be in touch.');">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">First name</label><input class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">Last name</label><input class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">Email</label><input type="email" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">Phone</label><input class="form-control"></div>
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">Interest</label>
                                <select class="form-select"><option>Buying</option><option>Selling</option><option>Renting</option><option>Investment</option><option>Other</option></select>
                            </div>
                            <div class="col-md-6"><label class="form-label small text-uppercase text-muted">Budget</label>
                                <select class="form-select"><option>Under $1M</option><option>$1M – $5M</option><option>$5M – $10M</option><option>$10M+</option></select>
                            </div>
                            <div class="col-12"><label class="form-label small text-uppercase text-muted">Message</label><textarea class="form-control" rows="5" placeholder="Tell us about the home you're looking for…"></textarea></div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree">
                                    <label class="form-check-label small" for="agree">I agree to be contacted by UrbanNest.</label>
                                </div>
                                <button class="btn btn-gold px-4">Send Message <i class="fa-solid fa-paper-plane ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad-sm">
    <div class="container">
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm">
            <iframe src="https://www.google.com/maps?q=432+Park+Avenue+New+York&output=embed" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
