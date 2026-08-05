<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';

$UN_OWNERS_ALL = array_merge($UN_OWNERS, [
    ['id'=>5,'name'=>'Camille Duverger','role'=>'Private Owner','avatar'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80','portfolio'=>2,'city'=>'San Francisco','since'=>'2021'],
    ['id'=>6,'name'=>'Yusuf Al-Rashid','role'=>'Portfolio Owner','avatar'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80','portfolio'=>11,'city'=>'Manhattan','since'=>'2014'],
    ['id'=>7,'name'=>'Beatrix Novak','role'=>'Estate Owner','avatar'=>'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=400&q=80','portfolio'=>5,'city'=>'Chicago','since'=>'2017'],
    ['id'=>8,'name'=>'Julien Moreau','role'=>'Investment Owner','avatar'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80','portfolio'=>8,'city'=>'Los Angeles','since'=>'2016'],
]);

$viewId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($viewId) {
    $owner = null;
    foreach ($UN_OWNERS_ALL as $o) if ($o['id']===$viewId) { $owner = $o; break; }
    if (!$owner) $owner = $UN_OWNERS_ALL[0];
    $pageTitle = $owner['name'] . ' — Owner · UrbanNest';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="un-page-head" style="padding: 110px 0 60px;">
        <div class="container">
            <nav><ol class="breadcrumb mb-2"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item"><a href="<?= url('owners.php') ?>">Owners</a></li><li class="breadcrumb-item active"><?= e($owner['name']) ?></li></ol></nav>
            <h1><?= e($owner['name']) ?></h1>
            <p class="opacity-75 mb-0"><?= e($owner['role']) ?> · <?= e($owner['city']) ?></p>
        </div>
    </section>
    <section class="section-pad-sm">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="un-agent-side">
                        <img src="<?= e($owner['avatar']) ?>" alt="">
                        <h4 class="un-agent-name mt-3"><?= e($owner['name']) ?></h4>
                        <div class="un-agent-role"><?= e($owner['role']) ?></div>
                        <div class="un-agent-meta my-3">
                            <span><strong><?= (int)$owner['portfolio'] ?></strong> homes</span>
                            <span>Since <?= e($owner['since']) ?></span>
                        </div>
                        <hr>
                        <p class="text-start small mb-2"><i class="fa-solid fa-location-dot text-gold me-2"></i><?= e($owner['city']) ?></p>
                        <div class="d-grid"><a href="<?= url('contact.php') ?>" class="btn btn-navy btn-sm">Contact Owner</a></div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="un-detail-box">
                        <span class="eyebrow">About</span>
                        <h3 class="section-title" style="font-size:1.6rem;">A quiet steward of remarkable homes.</h3>
                        <p><?= e($owner['name']) ?> maintains a considered portfolio of residences chosen for provenance, architecture and long‑term value. Their homes are entrusted exclusively to UrbanNest for representation.</p>
                    </div>
                    <div class="un-detail-box">
                        <span class="eyebrow">Portfolio</span>
                        <h3 class="section-title mb-3" style="font-size:1.4rem;">Homes Represented</h3>
                        <div class="row g-4">
                            <?php foreach (array_slice($UN_PROPERTIES, 0, 3) as $p): ?>
                                <div class="col-md-6 col-xl-4"><?php un_render_card($p); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = 'Owners — UrbanNest';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">Owners</li></ol></nav>
        <h1>Featured Owners</h1>
        <p class="opacity-75 mb-0">Meet the owners whose extraordinary homes we represent.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($UN_OWNERS_ALL as $o): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="un-agent-card">
                        <div class="un-agent-avatar"><img src="<?= e($o['avatar']) ?>" alt="<?= e($o['name']) ?>"></div>
                        <div class="un-agent-role"><?= e($o['role']) ?></div>
                        <h5 class="un-agent-name mt-1"><a href="owners.php?id=<?= (int)$o['id'] ?>"><?= e($o['name']) ?></a></h5>
                        <div class="un-agent-meta"><span><strong><?= (int)$o['portfolio'] ?></strong> homes</span><span><?= e($o['city']) ?></span></div>
                        <a href="owners.php?id=<?= (int)$o['id'] ?>" class="btn btn-sm btn-outline-navy">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
