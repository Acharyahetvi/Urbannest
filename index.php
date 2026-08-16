<?php
$pageTitle = 'UrbanNest — Luxury Real Estate';
$pageDesc  = 'Discover premium homes, penthouses & investment properties curated by UrbanNest.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';
include("./db_connection.php");
?>

<!-- ============== HERO ============== -->
<section class="un-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="un-hero-eyebrow">Discover · Reside · Belong</span>
                <h1>Find the home that <em>defines</em><br>the life you deserve.</h1>
                <p class="lead">UrbanNest curates a global portfolio of extraordinary residences — from Manhattan penthouses to Miami waterfront estates — matched to your ambitions with quiet precision.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="properties/list.php" class="btn btn-gold px-4"><i class="fa-solid fa-magnifying-glass me-2"></i>Explore Listings</a>
                    <a href="contact.php" class="btn btn-light-ghost px-4">Talk to an Advisor</a>
                </div>
                <div class="un-hero-stats">
                    <div><div class="num">2,400+</div><div class="lbl">Listings</div></div>
                    <div><div class="num">180</div><div class="lbl">Cities</div></div>
                    <div><div class="num">98%</div><div class="lbl">Client Retention</div></div>
                </div>
            </div>
        </div>

        <!-- Advanced search -->
        <div class="un-search">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#buyTab">Buy</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#rentTab">Rent</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#sellTab">Sell</button></li>
            </ul>
            <form action="properties/list.php" method="get" class="tab-content">
                <div class="tab-pane fade show active" id="buyTab">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-2"><select name="state" class="form-select"><option value="">State</option><?php foreach ($UN_STATES as $s) echo '<option>'.e($s['name']).'</option>'; ?></select></div>
                        <div class="col-md-2"><select name="city" class="form-select"><option value="">City</option><?php foreach ($UN_CITIES as $c) echo '<option>'.e($c['name']).'</option>'; ?></select></div>
                        <div class="col-md-2"><select name="cat" class="form-select"><option value="">Category</option><?php foreach ($UN_CATEGORIES as $c) echo '<option value="'.e($c['slug']).'">'.e($c['name']).'</option>'; ?></select></div>
                        <div class="col-md-2"><select name="sub" class="form-select"><option value="">Sub Category</option><option>Studio</option><option>Duplex</option><option>Triplex</option><option>Estate</option></select></div>
                        <div class="col-md-2"><select name="status" class="form-select"><option value="">Status</option><option>Ready to Move</option><option>Under Construction</option><option>New Launch</option></select></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-gold un-search-btn"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</button></div>
                        <div class="col-md-3"><select name="price" class="form-select"><option value="">Price Range</option><option>Under $500k</option><option>$500k – $1M</option><option>$1M – $5M</option><option>$5M – $10M</option><option>$10M+</option></select></div>
                        <div class="col-md-3"><select name="beds" class="form-select"><option value="">Bedrooms</option><option>1+</option><option>2+</option><option>3+</option><option>4+</option><option>5+</option></select></div>
                        <div class="col-md-3"><select name="baths" class="form-select"><option value="">Bathrooms</option><option>1+</option><option>2+</option><option>3+</option><option>4+</option></select></div>
                        <div class="col-md-3"><select name="area" class="form-select"><option value="">Area (sqft)</option><option>500 – 1,500</option><option>1,500 – 3,000</option><option>3,000 – 6,000</option><option>6,000+</option></select></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rentTab">
                    <div class="row g-2">
                        <div class="col-md-3"><input type="text" name="location" class="form-control" placeholder="Where do you want to live?"></div>
                        <div class="col-md-3"><select class="form-select"><option>Any Type</option><option>Apartment</option><option>Loft</option><option>Villa</option></select></div>
                        <div class="col-md-3"><select class="form-select"><option>Any Price</option><option>Under $3k/mo</option><option>$3k – $6k/mo</option><option>$6k+/mo</option></select></div>
                        <div class="col-md-3"><button class="btn btn-gold un-search-btn">Find Rentals</button></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="sellTab">
                    <div class="row g-2">
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="Property Address"></div>
                        <div class="col-md-3"><select class="form-select"><option>Property Type</option><option>Villa</option><option>Apartment</option><option>Commercial</option></select></div>
                        <div class="col-md-2"><input type="text" class="form-control" placeholder="Asking Price"></div>
                        <div class="col-md-3"><button class="btn btn-gold un-search-btn">Get Valuation</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ============== FEATURED PROPERTIES ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">Handpicked Portfolio</span>
                <h2 class="section-title mb-2">Featured Properties</h2>
                <p class="section-lead">Curated homes reflecting exceptional architecture, provenance and location.</p>
            </div>
            <a href="properties/list.php?featured=1" class="btn btn-outline-navy">View All <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach (array_filter($UN_PROPERTIES, fn($p)=>$p['featured']) as $p): ?>
                <div class="col-md-6 col-lg-4"><?php un_render_card($p); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== POPULAR CATEGORIES ============== -->
<section class="section-pad">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow d-inline-flex justify-content-center">Explore</span>
            <h2 class="section-title mb-2">Popular Categories</h2>
            <p class="section-lead mx-auto">Homes that fit every chapter of an extraordinary life.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($UN_CATEGORIES as $c): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="properties/list.php?cat=<?= e($c['slug']) ?>" class="un-cat-tile">
                        <img src="<?= e($c['img']) ?>" alt="<?= e($c['name']) ?>" loading="lazy">
                        <div class="un-cat-info">
                            <h5><?= e($c['name']) ?></h5>
                            <p><?= random_int(24, 180) ?> listings</p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== LATEST PROPERTIES ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">Fresh on Market</span>
                <h2 class="section-title mb-2">Latest Properties</h2>
                <p class="section-lead">New listings uploaded this week from our advisor network.</p>
            </div>
            <a href="properties/list.php" class="btn btn-outline-navy">Browse Latest <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($UN_PROPERTIES, 3, 6) as $p): ?>
                <div class="col-md-6 col-lg-4"><?php un_render_card($p); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== PREMIUM + AFFORDABLE side by side ============== -->
<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <span class="eyebrow">Signature Tier</span>
                <h2 class="section-title mb-3">Premium Properties</h2>
                <p class="section-lead mb-4">Trophy residences reserved for buyers seeking rarity and provenance.</p>
                <div class="row g-4">
                    <?php foreach (array_slice(array_filter($UN_PROPERTIES, fn($p)=>$p['luxury']), 0, 2) as $p): ?>
                        <div class="col-md-6"><?php un_render_card($p); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="eyebrow">Approachable</span>
                <h2 class="section-title mb-3">Affordable Homes</h2>
                <p class="section-lead mb-4">Well‑designed homes at accessible prices — first‑time buyers welcome.</p>
                <div class="row g-4">
                    <?php
                    $aff = array_values(array_filter($UN_PROPERTIES, fn($p)=>!$p['luxury'] && $p['purpose']!=='rent'));
                    foreach (array_slice($aff, 0, 2) as $p): ?>
                        <div class="col-md-6"><?php un_render_card($p); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============== COMMERCIAL ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">Enterprise Real Estate</span>
                <h2 class="section-title mb-2">Commercial Properties</h2>
                <p class="section-lead">Trophy office floors, flagship retail and hospitality opportunities.</p>
            </div>
            <a href="properties/list.php?cat=commercial" class="btn btn-outline-navy">View Commercial <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php
            $comm = array_values(array_filter($UN_PROPERTIES, fn($p)=>$p['category_slug']==='commercial'));
            // Ensure at least 3 tiles by re-using popular buy listings if we need padding
            $tiles = array_slice(array_merge($comm, array_slice($UN_PROPERTIES, 0, 3)), 0, 3);
            foreach ($tiles as $p): ?>
                <div class="col-md-6 col-lg-4"><?php un_render_card($p); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== TRENDING CITIES ============== -->
<section class="section-pad">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow d-inline-flex justify-content-center">Cities</span>
            <h2 class="section-title mb-2">Trending Destinations</h2>
            <p class="section-lead mx-auto">Where our clients are buying, moving and investing this season.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($UN_CITIES as $c): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="properties/list.php?city=<?= urlencode($c['name']) ?>" class="un-city-card">
                        <img src="<?= e($c['img']) ?>" alt="<?= e($c['name']) ?>" loading="lazy">
                        <div class="un-city-info">
                            <div class="count"><?= (int)$c['count'] ?> homes</div>
                            <h5><?= e($c['name']) ?></h5>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== WHY CHOOSE ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="eyebrow">Why UrbanNest</span>
                <h2 class="section-title mb-3">A different way to find the extraordinary.</h2>
                <p class="section-lead mb-4">UrbanNest brings together white‑glove advisors, verified inventory and modern tooling. From first tour to final signature, everything is designed to feel effortless.</p>
                <a href="about.php" class="btn btn-navy">Discover our story <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-sm-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-shield-halved"></i></div><h5>Verified Inventory</h5><p>Every listing is inspected, photographed and verified by our advisors.</p></div></div>
                    <div class="col-sm-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-user-tie"></i></div><h5>Personal Advisors</h5><p>Dedicated specialists guide you through discovery, tour and closing.</p></div></div>
                    <div class="col-sm-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-globe"></i></div><h5>Global Reach</h5><p>Access properties across four continents through our partner network.</p></div></div>
                    <div class="col-sm-6"><div class="un-feature"><div class="un-feature-icon"><i class="fa-solid fa-file-signature"></i></div><h5>Frictionless Paperwork</h5><p>Digital contracts, secure escrow, and clear timelines from offer to keys.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============== FEATURED AGENTS ============== -->
<section class="section-pad">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">People</span>
                <h2 class="section-title mb-2">Featured Advisors</h2>
                <p class="section-lead">Meet the specialists behind our most memorable transactions.</p>
            </div>
            <a href="agents.php" class="btn btn-outline-navy">All Agents <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($UN_AGENTS as $a): ?>
                <div class="col-6 col-md-3">
                    <div class="un-agent-card">
                        <div class="un-agent-avatar"><img src="<?= e($a['avatar']) ?>" alt="<?= e($a['name']) ?>"></div>
                        <div class="un-agent-role"><?= e($a['role']) ?></div>
                        <h5 class="un-agent-name mt-1"><a href="agents.php?id=<?= (int)$a['id'] ?>"><?= e($a['name']) ?></a></h5>
                        <div class="un-agent-meta"><span><strong><?= (int)$a['listings'] ?></strong> listings</span><span><strong><?= (int)$a['sold'] ?></strong> sold</span></div>
                        <div class="un-agent-social">
                            <a href="#"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="mailto:<?= e($a['email']) ?>"><i class="fa-regular fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== FEATURED OWNERS ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">Portfolio Owners</span>
                <h2 class="section-title mb-2">Featured Owners</h2>
                <p class="section-lead">Trusted owners entrusting their homes to UrbanNest.</p>
            </div>
            <a href="owners.php" class="btn btn-outline-navy">All Owners <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($UN_OWNERS as $o): ?>
                <div class="col-6 col-md-3">
                    <div class="un-agent-card">
                        <div class="un-agent-avatar"><img src="<?= e($o['avatar']) ?>" alt="<?= e($o['name']) ?>"></div>
                        <div class="un-agent-role"><?= e($o['role']) ?></div>
                        <h5 class="un-agent-name mt-1"><a href="owners.php?id=<?= (int)$o['id'] ?>"><?= e($o['name']) ?></a></h5>
                        <div class="un-agent-meta"><span><strong><?= (int)$o['portfolio'] ?></strong> homes</span><span><?= e($o['city']) ?></span></div>
                        <div class="un-agent-social">
                            <a href="#"><i class="fa-regular fa-envelope"></i></a>
                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== STATISTICS ============== -->
<section class="section-pad-sm">
    <div class="container">
        <div class="un-stats">
            <div class="row text-center g-4">
                <div class="col-md-3"><div class="num" data-count="2400" data-suffix="+">0</div><div class="lbl">Active Listings</div></div>
                <div class="col-md-3"><div class="num" data-count="15800">0</div><div class="lbl">Happy Clients</div></div>
                <div class="col-md-3"><div class="num" data-count="180">0</div><div class="lbl">Cities Covered</div></div>
                <div class="col-md-3"><div class="num" data-count="98" data-suffix="%">0</div><div class="lbl">Client Retention</div></div>
            </div>
        </div>
    </div>
</section>

<!-- ============== TESTIMONIALS ============== -->
<section class="section-pad">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow d-inline-flex justify-content-center">Testimonials</span>
            <h2 class="section-title mb-2">What our clients say</h2>
            <p class="section-lead mx-auto">Real stories from families, investors and residents who chose UrbanNest.</p>
        </div>
        <div id="unTestimonials" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row g-4">
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"UrbanNest found us the Central Park apartment we didn't know existed. Their advisor was patient, precise and genuinely caring."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Elena Rousseau</div><div class="r">Buyer · Manhattan</div></div></div></div></div>
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"Selling my Miami villa took less than five weeks. The marketing, photography and negotiation felt entirely bespoke."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Sebastián Duarte</div><div class="r">Seller · Miami</div></div></div></div></div>
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"As an international buyer, I needed trust and clarity. Their team delivered a smooth process from Zurich to Los Angeles."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Ines Fournier</div><div class="r">International Buyer</div></div></div></div></div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row g-4">
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"Everything from tour scheduling to inspection was on rails. This is what modern real estate should feel like."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Marcus Halvorsen</div><div class="r">Buyer · Brooklyn</div></div></div></div></div>
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"We rented a SoHo loft sight unseen and it exceeded every expectation. Genuine advisors, genuine service."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Priya Ananth</div><div class="r">Tenant · SoHo</div></div></div></div></div>
                        <div class="col-md-4"><div class="un-quote"><i class="fa-solid fa-quote-left"></i><p>"My portfolio is now professionally managed. UrbanNest brought me tenants and buyers I could never have reached."</p><div class="un-stars mb-1"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><div class="un-quote-foot"><img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=200&q=80" alt=""><div><div class="n">Henrik Solberg</div><div class="r">Portfolio Owner</div></div></div></div></div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#unTestimonials" data-bs-slide="prev"><span class="carousel-control-prev-icon bg-dark rounded-circle"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#unTestimonials" data-bs-slide="next"><span class="carousel-control-next-icon bg-dark rounded-circle"></span></button>
        </div>
    </div>
</section>

<!-- ============== BLOGS ============== -->
<section class="section-pad bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap mb-5">
            <div>
                <span class="eyebrow">Journal</span>
                <h2 class="section-title mb-2">From the UrbanNest Journal</h2>
                <p class="section-lead">Insights, market pulses and design stories from our team.</p>
            </div>
            <a href="blog.php" class="btn btn-outline-navy">All Articles <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <?php
            $posts = [
                ['t'=>'The New Rules of Luxury: What Buyers Want in 2026','img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80','tag'=>'Trends','date'=>'Jul 21, 2026'],
                ['t'=>'Manhattan vs. Miami: Where to Invest This Year','img'=>'https://images.unsplash.com/photo-1449034446853-66c86144b0ad?auto=format&fit=crop&w=1000&q=80','tag'=>'Investing','date'=>'Jul 14, 2026'],
                ['t'=>'Inside a Hollywood Hills Modernist Restoration','img'=>'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=80','tag'=>'Design','date'=>'Jul 03, 2026'],
            ];
            foreach ($posts as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="un-blog-card">
                        <div class="un-blog-img"><img src="<?= e($p['img']) ?>" alt="<?= e($p['t']) ?>" loading="lazy"></div>
                        <div class="un-blog-body">
                            <span class="un-blog-tag"><?= e($p['tag']) ?></span>
                            <h5 class="un-blog-title"><a href="blog.php"><?= e($p['t']) ?></a></h5>
                            <div class="un-blog-meta"><i class="fa-regular fa-calendar"></i> <?= e($p['date']) ?> <span class="divider-dot"></span> <i class="fa-regular fa-clock"></i> 5 min read</div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============== NEWSLETTER CTA ============== -->
<section class="section-pad-sm">
    <div class="container">
        <div class="un-cta text-center">
            <span class="eyebrow d-inline-flex justify-content-center text-gold">Stay in the know</span>
            <h2 class="mt-2 mb-3">Join the UrbanNest inner circle.</h2>
            <p class="mb-4 opacity-75">Weekly market pulses, unlisted properties and design stories delivered to your inbox.</p>
            <form class="row g-2 justify-content-center" onsubmit="event.preventDefault(); this.reset(); alert('Welcome. Check your inbox.');">
                <div class="col-md-6"><input type="email" class="form-control" placeholder="Your email address" required></div>
                <div class="col-md-auto"><button class="btn btn-gold">Subscribe</button></div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
