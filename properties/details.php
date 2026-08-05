<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/data.php';

$id = $_GET['id'] ?? 'UN-1024';
$property = null;
foreach ($UN_PROPERTIES as $p) if ($p['id'] === $id) { $property = $p; break; }
if (!$property) $property = $UN_PROPERTIES[0];

$agent = $UN_AGENTS[array_search($property['agent_id'], array_column($UN_AGENTS, 'id'))] ?? $UN_AGENTS[0];
$owner = $UN_OWNERS[array_search($property['owner_id'], array_column($UN_OWNERS, 'id'))] ?? $UN_OWNERS[0];

$pageTitle = $property['title'] . ' — UrbanNest';
$pageDesc  = $property['title'] . ' at ' . $property['address'];

$gallery = [
    $property['image'],
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
];

require_once __DIR__ . '/../includes/navbar.php';
?>

<section class="un-page-head" style="padding: 120px 0 60px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= url('properties/list.php') ?>">Properties</a></li>
                <li class="breadcrumb-item active"><?= e($property['id']) ?></li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
            <div>
                <div class="d-flex gap-2 mb-2">
                    <?php if ($property['featured']): ?><span class="un-badge un-badge-featured"><i class="fa-solid fa-star"></i> Featured</span><?php endif; ?>
                    <?php if ($property['luxury']):  ?><span class="un-badge un-badge-luxury"><i class="fa-solid fa-crown"></i> Luxury</span><?php endif; ?>
                    <span class="un-badge un-badge-new"><?= e($property['status']) ?></span>
                </div>
                <h1><?= e($property['title']) ?></h1>
                <p class="lead opacity-75 mb-0"><i class="fa-solid fa-location-dot text-gold me-2"></i><?= e($property['address']) ?></p>
            </div>
            <div class="text-lg-end">
                <div style="font-family:'Playfair Display',serif;font-size:2.4rem;color:#fff;">$<?= number_format((float)$property['price']) ?><?= $property['purpose']==='rent'?' <small class="opacity-50">/mo</small>':'' ?></div>
                <span class="opacity-75">ID · <?= e($property['id']) ?></span>
            </div>
        </div>
    </div>
</section>

<section class="section-pad-sm">
    <div class="container">
        <!-- Gallery -->
        <div class="un-gallery-main mb-3">
            <img id="galleryMain" src="<?= e($gallery[0]) ?>" alt="<?= e($property['title']) ?>" style="transition: opacity .3s;">
        </div>
        <div class="row g-3 mb-5">
            <?php foreach ($gallery as $i => $src): ?>
                <div class="col-3 col-md-2">
                    <div class="un-gallery-thumb <?= $i===0?'active':'' ?>" data-src="<?= e($src) ?>">
                        <img src="<?= e($src) ?>" alt="Photo <?= $i+1 ?>" loading="lazy">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">
                <!-- Overview -->
                <div class="un-detail-box">
                    <span class="eyebrow">Overview</span>
                    <h2 class="section-title mb-3" style="font-size:1.8rem;">A residence composed with rare precision.</h2>
                    <div class="un-detail-meta">
                        <div><i class="fa-solid fa-bed"></i> <?= (int)$property['beds'] ?> Bedrooms</div>
                        <div><i class="fa-solid fa-bath"></i> <?= (int)$property['baths'] ?> Bathrooms</div>
                        <div><i class="fa-solid fa-vector-square"></i> <?= number_format((int)$property['area']) ?> sqft</div>
                        <div><i class="fa-solid fa-tags"></i> <?= e($property['category']) ?></div>
                        <div><i class="fa-solid fa-city"></i> <?= e($property['city']) ?>, <?= e($property['state']) ?></div>
                        <div><i class="fa-regular fa-calendar"></i> Listed 2 days ago</div>
                    </div>
                    <p>Set within one of the most sought‑after addresses in <?= e($property['city']) ?>, this residence is defined by generous proportions, floor‑to‑ceiling glazing and quiet, considered materials. Interiors were reimagined by a leading studio to blend architectural clarity with the warmth of a private home.</p>
                    <p>The layout unfolds across a formal reception, a chef's kitchen with dual islands, and a primary suite with dual dressing rooms. Every principal room enjoys skyline or waterfront views, and the residence is fitted with a full‑home smart system, high‑fidelity audio and climate‑controlled wine storage.</p>
                </div>

                <!-- Features -->
                <div class="un-detail-box">
                    <span class="eyebrow">Features</span>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;">Property Features</h3>
                    <div class="row g-3">
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Floor‑to‑ceiling windows throughout</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Radiant heated stone flooring</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Chef's kitchen with dual islands</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Dual primary dressing rooms</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Whole‑home smart automation</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Climate‑controlled wine storage</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Private elevator direct to residence</div>
                        <div class="col-md-6"><i class="fa-solid fa-check text-gold me-2"></i>Secure two‑car parking</div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="un-detail-box">
                    <span class="eyebrow">Building</span>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;">Amenities</h3>
                    <ul class="un-amen-list ps-0">
                        <?php foreach ($UN_AMENITIES as $a): ?>
                            <li><i class="<?= e($a['icon']) ?>"></i> <?= e($a['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Floor plan -->
                <div class="un-detail-box">
                    <span class="eyebrow">Floor Plan</span>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;">Layout</h3>
                    <img src="https://images.unsplash.com/photo-1580216643062-cf460548a66a?auto=format&fit=crop&w=1400&q=80" alt="Floor plan" class="img-fluid rounded-4" style="filter: grayscale(1) contrast(1.05);">
                    <div class="row g-3 mt-3 small">
                        <div class="col-md-4"><strong>Level 1:</strong> Reception, kitchen, terrace</div>
                        <div class="col-md-4"><strong>Level 2:</strong> Primary suite + two bedrooms</div>
                        <div class="col-md-4"><strong>Rooftop:</strong> Private garden + plunge pool</div>
                    </div>
                </div>

                <!-- Video -->
                <div class="un-detail-box">
                    <span class="eyebrow">Property Tour</span>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;">Cinematic Walkthrough</h3>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
                        <iframe src="https://www.youtube.com/embed/9K68nxUcqKM" title="Property tour" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Map -->
                <div class="un-detail-box">
                    <span class="eyebrow">Location</span>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;">On the Map</h3>
                    <div class="ratio ratio-21x9 rounded-4 overflow-hidden">
                        <iframe src="https://www.google.com/maps?q=<?= urlencode($property['address']) ?>&output=embed" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="col-lg-4">
                <div class="un-agent-side mb-4">
                    <img src="<?= e($agent['avatar']) ?>" alt="<?= e($agent['name']) ?>">
                    <div class="un-agent-role mt-3"><?= e($agent['role']) ?></div>
                    <h5 class="un-agent-name mt-1"><?= e($agent['name']) ?></h5>
                    <p class="text-muted small"><i class="fa-solid fa-phone text-gold me-1"></i><?= e($agent['phone']) ?></p>
                    <form class="text-start mt-3" onsubmit="event.preventDefault(); alert('Inquiry sent to '+'<?= e($agent['name']) ?>');">
                        <div class="mb-2"><input class="form-control" placeholder="Your name" required></div>
                        <div class="mb-2"><input class="form-control" type="email" placeholder="Email" required></div>
                        <div class="mb-2"><input class="form-control" placeholder="Phone"></div>
                        <div class="mb-2"><textarea class="form-control" rows="3" placeholder="I'm interested in <?= e($property['id']) ?>…"></textarea></div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-navy">Send Inquiry</button>
                            <button type="button" class="btn btn-gold"><i class="fa-regular fa-calendar-check me-2"></i>Schedule Visit</button>
                        </div>
                    </form>
                </div>

                <div class="un-detail-box">
                    <span class="eyebrow">Owner</span>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <img src="<?= e($owner['avatar']) ?>" alt="" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                        <div>
                            <div class="fw-semibold text-navy"><?= e($owner['name']) ?></div>
                            <div class="small text-muted"><?= e($owner['role']) ?> · Since <?= e($owner['since']) ?></div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid"><a href="<?= url('owners.php?id='.$owner['id']) ?>" class="btn btn-outline-navy btn-sm">View Owner Profile</a></div>
                </div>

                <div class="un-detail-box mt-4">
                    <span class="eyebrow">Actions</span>
                    <div class="d-grid gap-2 mt-2">
                        <button class="btn btn-outline-navy"><i class="fa-regular fa-heart me-2"></i>Save to Wishlist</button>
                        <button class="btn btn-outline-navy"><i class="fa-solid fa-code-compare me-2"></i>Add to Compare</button>
                        <button class="btn btn-outline-navy"><i class="fa-solid fa-share-nodes me-2"></i>Share Listing</button>
                        <button class="btn btn-outline-navy"><i class="fa-solid fa-print me-2"></i>Print Details</button>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Similar properties -->
        <div class="mt-5 pt-4">
            <div class="d-flex justify-content-between align-items-end flex-wrap mb-4">
                <div>
                    <span class="eyebrow">You may also like</span>
                    <h2 class="section-title mb-0" style="font-size:1.8rem;">Similar Properties</h2>
                </div>
                <a href="<?= url('properties/list.php') ?>" class="btn btn-outline-navy btn-sm">See All</a>
            </div>
            <div class="row g-4">
                <?php
                $similar = array_values(array_filter($UN_PROPERTIES, fn($p)=>$p['id']!==$property['id']));
                foreach (array_slice($similar, 0, 3) as $p): ?>
                    <div class="col-md-6 col-lg-4"><?php un_render_card($p); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
