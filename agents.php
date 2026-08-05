<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';

// Extend agent list for a fuller directory
$UN_AGENTS_ALL = array_merge($UN_AGENTS, [
    ['id'=>5,'name'=>'Layla Farooq','role'=>'New Development','avatar'=>'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80','listings'=>17,'sold'=>52,'phone'=>'+1 212 555 0166','email'=>'layla@urbannest.com'],
    ['id'=>6,'name'=>'Kenji Watanabe','role'=>'Commercial Advisor','avatar'=>'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80','listings'=>12,'sold'=>39,'phone'=>'+1 212 555 0170','email'=>'kenji@urbannest.com'],
    ['id'=>7,'name'=>'Charlotte Beaumont','role'=>'Rental Specialist','avatar'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80','listings'=>26,'sold'=>81,'phone'=>'+1 212 555 0175','email'=>'charlotte@urbannest.com'],
    ['id'=>8,'name'=>'Diego Salvatore','role'=>'International Sales','avatar'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80','listings'=>22,'sold'=>68,'phone'=>'+1 212 555 0180','email'=>'diego@urbannest.com'],
]);

// Single-agent profile view
$viewId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($viewId) {
    $agent = null;
    foreach ($UN_AGENTS_ALL as $a) if ($a['id']===$viewId) { $agent = $a; break; }
    if (!$agent) $agent = $UN_AGENTS_ALL[0];
    $pageTitle = $agent['name'] . ' — Agent · UrbanNest';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="un-page-head" style="padding: 110px 0 60px;">
        <div class="container">
            <nav><ol class="breadcrumb mb-2"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item"><a href="<?= url('agents.php') ?>">Agents</a></li><li class="breadcrumb-item active"><?= e($agent['name']) ?></li></ol></nav>
            <h1><?= e($agent['name']) ?></h1>
            <p class="opacity-75 mb-0"><?= e($agent['role']) ?></p>
        </div>
    </section>
    <section class="section-pad-sm">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="un-agent-side">
                        <img src="<?= e($agent['avatar']) ?>" alt="">
                        <h4 class="un-agent-name mt-3"><?= e($agent['name']) ?></h4>
                        <div class="un-agent-role"><?= e($agent['role']) ?></div>
                        <div class="un-agent-meta my-3"><span><strong><?= (int)$agent['listings'] ?></strong> listings</span><span><strong><?= (int)$agent['sold'] ?></strong> sold</span></div>
                        <hr>
                        <p class="text-start small mb-2"><i class="fa-solid fa-phone text-gold me-2"></i><?= e($agent['phone']) ?></p>
                        <p class="text-start small mb-3"><i class="fa-regular fa-envelope text-gold me-2"></i><?= e($agent['email']) ?></p>
                        <div class="un-agent-social">
                            <a href="#"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="un-detail-box">
                        <span class="eyebrow">About</span>
                        <h3 class="section-title" style="font-size:1.6rem;">A trusted voice for extraordinary homes.</h3>
                        <p><?= e($agent['name']) ?> has advised over a decade of families, investors and cultural figures across the world's most competitive markets. Known for discretion and deep market fluency, <?= e(strtok($agent['name'], ' ')) ?> pairs quiet negotiation with rigorous data.</p>
                        <p>Fluent in three languages and a member of the UrbanNest Global Council, <?= e(strtok($agent['name'], ' ')) ?> holds a network spanning New York, London, Miami and Singapore.</p>
                    </div>
                    <div class="un-detail-box">
                        <span class="eyebrow">Active Listings</span>
                        <h3 class="section-title" style="font-size:1.4rem;">Current Portfolio</h3>
                        <div class="row g-4 mt-1">
                            <?php foreach (array_slice($UN_PROPERTIES, 0, 3) as $p): ?>
                                <div class="col-md-6 col-xl-4"><?php un_render_card($p); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="un-detail-box">
                        <span class="eyebrow">Reviews</span>
                        <h3 class="section-title mb-3" style="font-size:1.4rem;">Client Voices</h3>
                        <?php for ($i=0;$i<3;$i++): ?>
                            <div class="d-flex gap-3 py-3 border-bottom">
                                <img src="https://i.pravatar.cc/64?img=<?= 10+$i ?>" alt="" style="width:52px;height:52px;border-radius:50%;">
                                <div>
                                    <div class="d-flex gap-3 align-items-center"><strong>Client #<?= $i+1 ?></strong><span class="un-stars small"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></div>
                                    <p class="mb-0 text-muted small mt-1">Consistently outperformed expectations. Professional, thoughtful, and always available.</p>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Directory view
$pageTitle = 'Our Agents — UrbanNest';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">Agents</li></ol></nav>
        <h1>Meet the Advisors</h1>
        <p class="opacity-75 mb-0">A hand‑picked team of specialists across residential, commercial and international real estate.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($UN_AGENTS_ALL as $a): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="un-agent-card">
                        <div class="un-agent-avatar"><img src="<?= e($a['avatar']) ?>" alt="<?= e($a['name']) ?>"></div>
                        <div class="un-agent-role"><?= e($a['role']) ?></div>
                        <h5 class="un-agent-name mt-1"><a href="agents.php?id=<?= (int)$a['id'] ?>"><?= e($a['name']) ?></a></h5>
                        <div class="un-agent-meta"><span><strong><?= (int)$a['listings'] ?></strong> listings</span><span><strong><?= (int)$a['sold'] ?></strong> sold</span></div>
                        <a href="agents.php?id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-outline-navy">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
