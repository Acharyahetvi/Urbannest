<?php
$pageTitle = 'Properties — UrbanNest';
$pageDesc  = 'Browse UrbanNest\'s curated portfolio of luxury properties for sale and rent.';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/navbar.php';

$catFilter = $_GET['cat'] ?? '';
$results = $UN_PROPERTIES;
if ($catFilter) {
    $results = array_values(array_filter($results, fn($p)=>$p['category_slug']===$catFilter));
    if (!$results) $results = $UN_PROPERTIES; // fallback for demo
}
?>

<section class="un-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Properties</li>
            </ol>
        </nav>
        <h1>Explore the Portfolio</h1>
        <p class="lead opacity-75 mb-0"><?= count($results) ?> curated residences match your criteria.</p>
    </div>
</section>

<section class="section-pad-sm">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar filters -->
            <aside class="col-lg-3">
                <form class="un-filter">
                    <h6>Category</h6>
                    <select class="form-select">
                        <option>All Categories</option>
                        <?php foreach ($UN_CATEGORIES as $c) echo '<option'.($catFilter===$c['slug']?' selected':'').'>'.e($c['name']).'</option>'; ?>
                    </select>

                    <h6>Sub Category</h6>
                    <select class="form-select">
                        <option>Any</option><option>Studio</option><option>Duplex</option><option>Triplex</option><option>Estate</option><option>Loft</option>
                    </select>

                    <h6>State</h6>
                    <select class="form-select">
                        <option>All States</option>
                        <?php foreach ($UN_STATES as $s) echo '<option>'.e($s['name']).'</option>'; ?>
                    </select>

                    <h6>City</h6>
                    <select class="form-select">
                        <option>All Cities</option>
                        <?php foreach ($UN_CITIES as $c) echo '<option>'.e($c['name']).'</option>'; ?>
                    </select>

                    <h6>Property Status</h6>
                    <?php foreach (['Ready to Move','Under Construction','New Launch','For Rent','For Sale'] as $st): ?>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="st_<?= md5($st) ?>"><label class="form-check-label" for="st_<?= md5($st) ?>"><?= e($st) ?></label></div>
                    <?php endforeach; ?>

                    <h6>Price Range</h6>
                    <div class="un-range">
                        <input type="number" class="form-control" placeholder="Min $">
                        <input type="number" class="form-control" placeholder="Max $">
                    </div>

                    <h6>Area (sqft)</h6>
                    <div class="un-range">
                        <input type="number" class="form-control" placeholder="Min">
                        <input type="number" class="form-control" placeholder="Max">
                    </div>

                    <h6>Bedrooms</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach (['1+','2+','3+','4+','5+'] as $b): ?>
                            <input type="radio" class="btn-check" name="beds" id="bed_<?= $b ?>"><label class="btn btn-sm btn-outline-navy" for="bed_<?= $b ?>"><?= $b ?></label>
                        <?php endforeach; ?>
                    </div>

                    <h6>Bathrooms</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach (['1+','2+','3+','4+'] as $b): ?>
                            <input type="radio" class="btn-check" name="baths" id="bath_<?= $b ?>"><label class="btn btn-sm btn-outline-navy" for="bath_<?= $b ?>"><?= $b ?></label>
                        <?php endforeach; ?>
                    </div>

                    <h6>Amenities</h6>
                    <?php foreach (array_slice($UN_AMENITIES, 0, 8) as $a): ?>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="am_<?= $a['id'] ?>"><label class="form-check-label" for="am_<?= $a['id'] ?>"><?= e($a['name']) ?></label></div>
                    <?php endforeach; ?>

                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-navy">Apply Filters</button>
                        <button type="reset" class="btn btn-outline-navy btn-sm">Reset</button>
                    </div>
                </form>
            </aside>

            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 p-3 bg-cream rounded-4">
                    <div><strong><?= count($results) ?></strong> <span class="text-muted">properties found</span></div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="small text-muted">Sort by</span>
                        <select class="form-select form-select-sm" style="min-width: 200px;">
                            <option>Recommended</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest First</option>
                            <option>Largest Area</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4">
                    <?php foreach ($results as $p): ?>
                        <div class="col-md-6 col-xl-4"><?php un_render_card($p); ?></div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                        <li class="page-item"><a class="page-link" href="#">12</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
