<footer class="un-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a class="un-brand un-brand-light mb-3 d-inline-flex" href="<?= url('index.php') ?>">
                    <span class="un-brand-mark"><i class="fa-solid fa-building-columns"></i></span>
                    <span class="un-brand-text">Urban<span>Nest</span></span>
                </a>
                <p class="text-white-50">UrbanNest curates the world's most extraordinary residences — from sky‑high penthouses to seaside estates — pairing discerning buyers with legacy homes.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="un-social"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="un-social"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="un-social"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="un-social"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="un-social"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="un-footer-title">Company</h6>
                <ul class="list-unstyled un-footer-links">
                    <li><a href="<?= url('about.php') ?>">About Us</a></li>
                    <li><a href="<?= url('agents.php') ?>">Our Agents</a></li>
                    <li><a href="<?= url('blog.php') ?>">Journal</a></li>
                    <li><a href="<?= url('contact.php') ?>">Contact</a></li>
                    <li><a href="<?= url('faq.php') ?>">FAQ</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="un-footer-title">Discover</h6>
                <ul class="list-unstyled un-footer-links">
                    <li><a href="<?= url('properties/list.php?purpose=buy') ?>">Buy</a></li>
                    <li><a href="<?= url('properties/list.php?purpose=rent') ?>">Rent</a></li>
                    <li><a href="<?= url('properties/list.php?purpose=sell') ?>">Sell</a></li>
                    <li><a href="<?= url('properties/list.php?cat=commercial') ?>">Commercial</a></li>
                    <li><a href="<?= url('owners.php') ?>">Owners</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="un-footer-title">Get in touch</h6>
                <p class="text-white-50 small mb-2"><i class="fa-solid fa-location-dot text-gold me-2"></i>432 Park Avenue, Manhattan, NY 10022</p>
                <p class="text-white-50 small mb-2"><i class="fa-solid fa-phone text-gold me-2"></i>+1 (212) 555‑0117</p>
                <p class="text-white-50 small mb-3"><i class="fa-regular fa-envelope text-gold me-2"></i>hello@urbannest.com</p>
                <form class="un-footer-news" onsubmit="event.preventDefault(); this.reset(); alert('Subscribed. Welcome to UrbanNest.');">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button class="btn btn-gold" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white-50 small">
            <div>&copy; <?= date('Y') ?> UrbanNest. Crafted for extraordinary homes.</div>
            <div class="d-flex gap-3 mt-2 mt-md-0">
                <a href="#" class="link-light-50">Privacy</a>
                <a href="#" class="link-light-50">Terms</a>
                <a href="#" class="link-light-50">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<a href="#" class="un-scrolltop" id="unScrollTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
