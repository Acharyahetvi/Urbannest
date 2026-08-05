<?php
$pageTitle = 'About — UrbanNest';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">About</li></ol></nav>
        <h1>Homes with a story. People who listen.</h1>
        <p class="lead opacity-75 mb-0" style="max-width:720px;">UrbanNest was founded on a simple idea — that the most extraordinary homes deserve equally extraordinary representation.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80" class="img-fluid rounded-4 shadow-lg" alt="">
            </div>
            <div class="col-lg-6">
                <span class="eyebrow">Our Story</span>
                <h2 class="section-title mb-3">A boutique approach to a global market.</h2>
                <p class="section-lead">Since 2014, UrbanNest has represented some of the most memorable residences across four continents. We remain deliberately small — a curated team of advisors, designers and technologists working shoulder to shoulder with our clients.</p>
                <p>We believe real estate should feel personal. From your first tour to the day you receive the keys, our team is with you — patient, precise and always available.</p>
                <div class="row g-3 mt-3">
                    <div class="col-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-award"></i></div><h5>28+ Awards</h5><p>Industry recognition for design and service.</p></div></div>
                    <div class="col-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-earth-americas"></i></div><h5>4 Continents</h5><p>Global reach through hand‑picked partners.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad-sm bg-cream">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow d-inline-flex justify-content-center">Principles</span>
            <h2 class="section-title mb-2">What we stand for</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-handshake"></i></div><h5>Discretion</h5><p>Your transaction is private, and stays that way.</p></div></div>
            <div class="col-md-4"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></div><h5>Precision</h5><p>Every decision is grounded in market data.</p></div></div>
            <div class="col-md-4"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-heart"></i></div><h5>Care</h5><p>We treat every home as if it were our own.</p></div></div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow d-inline-flex justify-content-center">Leadership</span>
            <h2 class="section-title mb-2">Meet our leadership</h2>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($UN_AGENTS, 0, 4) as $a): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="un-agent-card">
                        <div class="un-agent-avatar"><img src="<?= e($a['avatar']) ?>" alt=""></div>
                        <div class="un-agent-role"><?= e($a['role']) ?></div>
                        <h5 class="un-agent-name mt-1"><?= e($a['name']) ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
