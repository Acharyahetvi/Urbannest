
<?php
$pageTitle = 'Journal — UrbanNest';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';

$posts = [
    ['t'=>'The New Rules of Luxury: What Buyers Want in 2026','img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80','tag'=>'Trends','date'=>'Jul 21, 2026','author'=>'Isabella Cortez'],
    ['t'=>'Manhattan vs. Miami: Where to Invest This Year','img'=>'https://images.unsplash.com/photo-1449034446853-66c86144b0ad?auto=format&fit=crop&w=1200&q=80','tag'=>'Investing','date'=>'Jul 14, 2026','author'=>'Marcus Ashford'],
    ['t'=>'Inside a Hollywood Hills Modernist Restoration','img'=>'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80','tag'=>'Design','date'=>'Jul 03, 2026','author'=>'Naomi Sato'],
    ['t'=>'The Art of the Private Viewing','img'=>'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80','tag'=>'Etiquette','date'=>'Jun 26, 2026','author'=>'Elias Bergmann'],
    ['t'=>'Commercial Real Estate Enters a New Era','img'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80','tag'=>'Commercial','date'=>'Jun 18, 2026','author'=>'Layla Farooq'],
    ['t'=>'Designing a Home That Ages Beautifully','img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80','tag'=>'Design','date'=>'Jun 05, 2026','author'=>'Isabella Cortez'],
];
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">Journal</li></ol></nav>
        <h1>Journal</h1>
        <p class="opacity-75 mb-0">Insights on markets, homes and the art of living well.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="d-flex flex-wrap gap-2 mb-4">
            <?php foreach (['All','Trends','Investing','Design','Etiquette','Commercial'] as $i=>$t): ?>
                <button class="btn btn-sm <?= $i===0?'btn-navy':'btn-outline-navy' ?>"><?= e($t) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="row g-4">
            <?php foreach ($posts as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="un-blog-card">
                        <div class="un-blog-img"><img src="<?= e($p['img']) ?>" alt="<?= e($p['t']) ?>" loading="lazy"></div>
                        <div class="un-blog-body">
                            <span class="un-blog-tag"><?= e($p['tag']) ?></span>
                            <h5 class="un-blog-title"><a href="#"><?= e($p['t']) ?></a></h5>
                            <p class="text-muted small mb-3">By <?= e($p['author']) ?></p>
                            <div class="un-blog-meta"><i class="fa-regular fa-calendar"></i> <?= e($p['date']) ?> <span class="divider-dot"></span> <i class="fa-regular fa-clock"></i> 6 min read</div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
