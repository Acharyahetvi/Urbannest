<?php
/*
|--------------------------------------------------------------------------
| UrbanNest - Property Requirements
|--------------------------------------------------------------------------
| Reusable requirements page for Buy / Rent / Sale.
|
| Examples:
|   requirements.php?type=buy
|   requirements.php?type=rent
|   requirements.php?type=sale
|
| The form is intentionally kept separate so the same page can be reused
| from multiple entry points.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$type = strtolower(trim($_GET['type'] ?? 'buy'));

if (!in_array($type, ['buy', 'rent', 'sale'], true)) {
    $type = 'buy';
}

/*
 * "Buy" and "Sale" both mean properties whose listing_type is "For Sale"
 * in the database. "Rent" maps to "For Rent".
 */
$listingType = ($type === 'rent') ? 'For Rent' : 'For Sale';

/* Requirement options based on the actual property data. */
$cities = [
    'Ahmedabad', 'Surat', 'Mumbai', 'Rajkot',
    'Vadodara', 'Gandhinagar', 'Bhavnagar'
];

$areaRanges = [
    ['label' => 'Up to 1,000 sq.ft.', 'min' => '', 'max' => '1000'],
    ['label' => '1,001 – 1,500 sq.ft.', 'min' => '1001', 'max' => '1500'],
    ['label' => '1,501 – 2,000 sq.ft.', 'min' => '1501', 'max' => '2000'],
    ['label' => '2,001 – 2,500 sq.ft.', 'min' => '2001', 'max' => '2500'],
    ['label' => '2,501 – 3,000 sq.ft.', 'min' => '2501', 'max' => '3000'],
    ['label' => '3,001 – 4,000 sq.ft.', 'min' => '3001', 'max' => '4000'],
    ['label' => '4,001 – 5,000 sq.ft.', 'min' => '4001', 'max' => '5000'],
    ['label' => '5,001 – 7,500 sq.ft.', 'min' => '5001', 'max' => '7500'],
    ['label' => '7,501 – 10,000 sq.ft.', 'min' => '7501', 'max' => '10000'],
    ['label' => '10,001 – 15,000 sq.ft.', 'min' => '10001', 'max' => '15000']
];

if ($type === 'rent') {
    $budgetRanges = [
        ['label' => 'Under ₹30,000', 'min' => '', 'max' => '29999'],
        ['label' => '₹30,000 – ₹49,999', 'min' => '30000', 'max' => '49999'],
        ['label' => '₹50,000 – ₹79,999', 'min' => '50000', 'max' => '79999'],
        ['label' => '₹80,000 – ₹1.19 Lakh', 'min' => '80000', 'max' => '119999'],
        ['label' => '₹1.20 – ₹1.59 Lakh', 'min' => '120000', 'max' => '159999'],
        ['label' => '₹1.60 – ₹2.25 Lakh', 'min' => '160000', 'max' => '225000']
    ];
} else {
    $budgetRanges = [
        ['label' => 'Under ₹80 Lakh', 'min' => '', 'max' => '7999999'],
        ['label' => '₹80 Lakh – ₹1.19 Crore', 'min' => '8000000', 'max' => '11999999'],
        ['label' => '₹1.20 – ₹1.99 Crore', 'min' => '12000000', 'max' => '19999999'],
        ['label' => '₹2 – ₹2.99 Crore', 'min' => '20000000', 'max' => '29999999'],
        ['label' => '₹3 – ₹4.99 Crore', 'min' => '30000000', 'max' => '49999999'],
        ['label' => '₹5 – ₹9.99 Crore', 'min' => '50000000', 'max' => '99999999'],
        ['label' => '₹10 – ₹12.5 Crore', 'min' => '100000000', 'max' => '125000000']
    ];
}

$pageHeading = ($type === 'rent')
    ? 'Find a Property to Rent'
    : 'Find a Property to Buy';

$pageSubheading = ($type === 'rent')
    ? 'Tell us what you need and we’ll show properties that match.'
    : 'Tell us what you are looking for and we’ll show matching properties.';

/* Categories + subcategories */
$categories = [];
$subcategories = [];

$categoryResult = mysqli_query(
    $conn,
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name"
);

if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row;
    }
}

$subcategoryResult = mysqli_query(
    $conn,
    "SELECT subcategory_id, category_id, subcategory_name
     FROM sub_categories
     WHERE status = 'Active'
     ORDER BY subcategory_name"
);

if ($subcategoryResult) {
    while ($row = mysqli_fetch_assoc($subcategoryResult)) {
        $subcategories[] = $row;
    }
}

/* A few normal/common properties shown before the user submits requirements. */
$commonSql = "
    SELECT
        p.property_id,
        p.title,
        p.listing_type,
        p.price,
        p.area_sqft,
        p.bedrooms,
        p.bathrooms,
        p.city,
        p.state,
        s.subcategory_name,
        pi.image_path
    FROM properties p
    LEFT JOIN sub_categories s
        ON p.subcategory_id = s.subcategory_id
    LEFT JOIN property_images pi
        ON p.property_id = pi.property_id
        AND pi.is_main = 1
    WHERE p.approval_status = 'Approved'
      AND p.status = 'Active'
      AND p.listing_type = ?
      AND EXISTS (
          SELECT 1
          FROM property_images pi_photo
          WHERE pi_photo.property_id = p.property_id
            AND pi_photo.is_main = 1
      )
    ORDER BY p.property_id ASC
    LIMIT 6
";

$commonStmt = mysqli_prepare($conn, $commonSql);
$commonProperties = [];

if ($commonStmt) {
    mysqli_stmt_bind_param($commonStmt, 's', $listingType);
    mysqli_stmt_execute($commonStmt);
    $commonResult = mysqli_stmt_get_result($commonStmt);

    if ($commonResult) {
        while ($row = mysqli_fetch_assoc($commonResult)) {
            $commonProperties[] = $row;
        }
    }

    mysqli_stmt_close($commonStmt);
}

function propertyImage(string $path = null): string
{
    if (!empty($path)) {
        return 'admin/' . ltrim($path, '/');
    }

    return 'admin/assets/images/no-property.png';
}
?>

<style>
    .requirements-page {
        background: #f5f6fa;
        padding: 38px 0 70px;
    }

    .requirements-heading {
        max-width: 820px;
        margin: 0 auto 28px;
        text-align: center;
    }

    .requirements-heading h1 {
        color: #0b1f3a;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .requirements-heading p {
        color: #777;
        margin: 0;
        font-size: 15px;
    }

    .requirements-layout {
        display: grid;
        grid-template-columns: 300px minmax(0, 1fr);
        gap: 28px;
        align-items: start;
    }

    .requirements-sidebar {
        position: sticky;
        top: 88px;
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 5px 22px rgba(11,31,58,.08);
        border: 1px solid #edf0f4;
    }

    .sidebar-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 4px;
    }

    .filter-kicker {
        display: block;
        color: #b08d22;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 3px;
    }

    .requirements-sidebar h2 {
        color: #0b1f3a;
        font-size: 21px;
        margin: 0;
    }

    .filter-icon {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        border-radius: 8px;
        background: #f7f3e6;
        color: #b08d22;
        font-size: 20px;
    }

    .requirements-sidebar .small-intro {
        color: #858585;
        font-size: 12px;
        line-height: 1.45;
        margin: 0 0 19px;
    }

    .requirement-group {
        margin-bottom: 13px;
    }

    .requirement-group label,
    .amenities-heading label {
        display: block;
        color: #26364d;
        font-weight: 650;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .requirements-sidebar input[type="text"],
    .requirements-sidebar input[type="number"],
    .requirements-sidebar select {
        width: 100%;
        min-height: 38px;
        border: 1px solid #e1e5eb;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 12px;
        color: #26364d;
        outline: none;
        background: #fff;
    }

    .requirements-sidebar input:focus,
    .requirements-sidebar select:focus {
        border-color: #b08d22;
        box-shadow: 0 0 0 3px rgba(176,141,34,.09);
    }

    .range-row,
    .compact-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
    }

    .compact-two .requirement-group {
        min-width: 0;
    }

    .amenities-filter {
        border-top: 1px solid #edf0f4;
        margin-top: 17px;
        padding-top: 16px;
    }

    .amenities-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .amenities-heading label {
        margin: 0;
    }

    .amenities-heading span {
        font-size: 10px;
        color: #999;
    }

    .amenity-options {
        display: grid;
        grid-template-columns: 1fr;
        gap: 5px;
    }

    .amenity-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 7px;
        border-radius: 7px;
        margin: 0;
        cursor: pointer;
        transition: .15s ease;
    }

    .amenity-option:hover {
        background: #f8f9fb;
    }

    .amenity-option input {
        width: 14px;
        height: 14px;
        margin: 0;
        accent-color: #0b1f3a;
    }

    .amenity-option span {
        font-size: 12px;
        color: #555;
    }

    .amenity-hidden {
        display: none;
    }

    .amenities-filter.expanded .amenity-hidden {
        display: flex;
    }

    .show-more-btn {
        border: 0;
        background: transparent;
        color: #b08d22;
        padding: 7px 0 0;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .requirement-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
        margin-top: 18px;
    }

    .btn-find {
        border: 0;
        border-radius: 8px;
        min-height: 40px;
        padding: 9px 13px;
        background: #0b1f3a;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-reset {
        display: grid;
        place-items: center;
        min-height: 40px;
        border: 1px solid #e1e5eb;
        border-radius: 8px;
        padding: 0 12px;
        background: #fff;
        color: #555;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .requirements-main {
        min-width: 0;
    }

    .common-box {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 22px rgba(11,31,58,.06);
        border: 1px solid #edf0f4;
    }

    .common-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 19px;
    }

    .common-box-header h2 {
        color: #0b1f3a;
        font-size: 21px;
        margin: 0;
    }

    .common-box-header span {
        color: #999;
        font-size: 12px;
    }

    .property-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .property-card {
        background: #fff;
        border: 1px solid #eceef2;
        border-radius: 12px;
        overflow: hidden;
        transition: .2s ease;
        height: 100%;
    }

    .property-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0,0,0,.10);
    }

    .property-card img {
        width: 100%;
        height: 175px;
        object-fit: cover;
        display: block;
    }

    .property-card-content {
        padding: 15px;
    }

    .property-badge {
        display: inline-block;
        background: #f5e7b2;
        color: #0b1f3a;
        border-radius: 5px;
        padding: 4px 8px;
        font-size: 10px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .property-card h3 {
        color: #0b1f3a;
        font-size: 16px;
        margin: 0 0 7px;
        line-height: 1.35;
    }

    .property-price {
        color: #b08d22;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .property-price small {
        font-size: 11px;
        font-weight: 500;
        color: #888;
    }

    .property-location,
    .property-meta {
        color: #666;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .property-details-link {
        display: block;
        text-align: center;
        margin-top: 11px;
        background: #0b1f3a;
        color: #fff;
        text-decoration: none;
        border-radius: 7px;
        padding: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-properties {
        color: #777;
        text-align: center;
        padding: 40px 10px;
    }

    .mobile-filter-button {
        display: none;
        width: 100%;
        border: 0;
        border-radius: 9px;
        padding: 11px;
        background: #0b1f3a;
        color: #fff;
        font-weight: 700;
        margin-bottom: 18px;
    }

    @media (max-width: 991px) {
        .requirements-layout {
            grid-template-columns: 1fr;
        }

        .requirements-sidebar {
            position: static;
        }

        .mobile-filter-button {
            display: block;
        }

        .requirements-sidebar.collapsed {
            display: none;
        }
    }

    @media (max-width: 650px) {
        .requirements-heading h1 {
            font-size: 27px;
        }

        .property-grid {
            grid-template-columns: 1fr;
        }

        .common-box {
            padding: 18px;
        }
    }
</style>

<section class="requirements-page">
    <div class="container">

        <div class="requirements-heading">
            <h1><?= htmlspecialchars($pageHeading) ?></h1>
            <p><?= htmlspecialchars($pageSubheading) ?></p>
        </div>

        <button
            type="button"
            class="mobile-filter-button"
            id="filterToggle"
        >
            ☰ Tell Us What You Need
        </button>

        <div class="requirements-layout">

            <!-- REQUIREMENTS SIDEBAR -->
            <aside class="requirements-sidebar" id="requirementsSidebar">
                <div class="sidebar-title-row">
                    <div>
                        <span class="filter-kicker">SEARCH</span>
                        <h2>Your Requirements</h2>
                    </div>
                    <span class="filter-icon">⌕</span>
                </div>

                <p class="small-intro">
                    Narrow down the properties using the details that matter to you.
                </p>

                <form action="property-results.php" method="GET">

                    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

                    <div class="requirement-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id">
                            <option value="">All categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int)$category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="requirement-group">
                        <label for="subcategory_id">Property Type</label>
                        <select name="subcategory_id" id="subcategory_id">
                            <option value="">All property types</option>
                        </select>
                    </div>

                    <div class="requirement-group">
                        <label for="city">City</label>
                        <select name="city" id="city">
                            <option value="">Any city</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city) ?>">
                                    <?= htmlspecialchars($city) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="requirement-group">
                        <label for="budget_range">Budget</label>
                        <select name="budget_range" id="budget_range">
                            <option value="">Any budget</option>
                            <?php foreach ($budgetRanges as $range): ?>
                                <option value="<?= htmlspecialchars($range['min'] . '|' . $range['max']) ?>">
                                    <?= htmlspecialchars($range['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="min_price" id="min_price" value="">
                        <input type="hidden" name="max_price" id="max_price" value="">
                    </div>

                    <div class="requirement-group">
                        <label for="area_range">Area</label>
                        <select name="area_range" id="area_range">
                            <option value="">Any area</option>
                            <?php foreach ($areaRanges as $range): ?>
                                <option value="<?= htmlspecialchars($range['min'] . '|' . $range['max']) ?>">
                                    <?= htmlspecialchars($range['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="min_area" id="min_area" value="">
                        <input type="hidden" name="max_area" id="max_area" value="">
                    </div>

                    <div class="compact-two">
                        <div class="requirement-group">
                            <label for="bedrooms">Bedrooms</label>
                            <select name="bedrooms" id="bedrooms">
                                <option value="">Any</option>
                                <?php for ($i = 2; $i <= 6; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> BHK</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="requirement-group">
                            <label for="bathrooms">Bathrooms</label>
                            <select name="bathrooms" id="bathrooms">
                                <option value="">Any</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="requirement-group">
                        <label for="furnishing">Furnishing</label>
                        <select name="furnishing" id="furnishing">
                            <option value="">Any furnishing</option>
                            <option value="Furnished">Furnished</option>
                            <option value="Semi-Furnished">Semi-Furnished</option>
                            <option value="Unfurnished">Unfurnished</option>
                        </select>
                    </div>

                    <div class="requirement-group">
                        <label for="condition">Property Condition</label>
                        <select name="condition" id="condition">
                            <option value="">Any condition</option>
                            <option value="Ready to Move">Ready to Move</option>
                            <option value="Ready to Build">Ready to Build</option>
                            <option value="New Launch">New Launch</option>
                            <option value="Under Construction">Under Construction</option>
                        </select>
                    </div>

                    <!-- AMENITIES -->
                    <div class="amenities-filter">
                        <div class="amenities-heading">
                            <label>Amenities</label>
                            <span>Popular</span>
                        </div>

                        <div class="amenity-options">
                            <?php
                            $amenityResult = mysqli_query(
                                $conn,
                                "SELECT amenity_id, amenity_name FROM amenities ORDER BY amenity_id"
                            );

                            $amenityCount = 0;

                            if ($amenityResult):
                                while ($amenity = mysqli_fetch_assoc($amenityResult)):
                                    $amenityCount++;
                                    $hiddenClass = $amenityCount > 5 ? ' amenity-hidden' : '';
                            ?>
                                    <label class="amenity-option<?= $hiddenClass ?>">
                                        <input
                                            type="checkbox"
                                            name="amenities[]"
                                            value="<?= (int)$amenity['amenity_id'] ?>"
                                        >
                                        <span><?= htmlspecialchars($amenity['amenity_name']) ?></span>
                                    </label>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </div>

                        <?php if ($amenityCount > 5): ?>
                            <button type="button" class="show-more-btn" id="showAmenities">
                                + Show more amenities
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="requirement-actions">
                        <button type="submit" class="btn-find">
                            Find Properties
                        </button>

                        <a
                            href="requirements.php?type=<?= htmlspecialchars($type) ?>"
                            class="btn-reset"
                        >
                            Reset
                        </a>
                    </div>

                </form>
            </aside>

            <!-- COMMON PROPERTIES -->
            <main class="requirements-main">

                <div class="common-box">

                    <div class="common-box-header">
                        <h2>Common Properties</h2>
                        <span>Browse first, filter when you're ready.</span>
                    </div>

                    <?php if (!empty($commonProperties)): ?>

                        <div class="property-grid">

                            <?php foreach ($commonProperties as $property): ?>

                                <article class="property-card">

                                    <img
                                        src="<?= htmlspecialchars(propertyImage($property['image_path'] ?? null)) ?>"
                                        alt="Property Image"
                                    >

                                    <div class="property-card-content">

                                        <span class="property-badge">
                                            <?= htmlspecialchars($property['listing_type']) ?>
                                        </span>

                                        <h3>
                                            <?= htmlspecialchars($property['title']) ?>
                                        </h3>

                                        <div class="property-price">
                                            ₹<?= number_format((float)$property['price']) ?>
                                            <?php if ($property['listing_type'] === 'For Rent'): ?>
                                                <small>/month</small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="property-location">
                                            📍 <?= htmlspecialchars($property['city']) ?>,
                                            <?= htmlspecialchars($property['state']) ?>
                                        </div>

                                        <div class="property-meta">
                                            <?= htmlspecialchars($property['subcategory_name'] ?? 'Property') ?>
                                            · <?= number_format((int)$property['area_sqft']) ?> sq.ft.
                                            <?php if ($property['bedrooms'] !== null): ?>
                                                · <?= (int)$property['bedrooms'] ?> BHK
                                            <?php endif; ?>
                                        </div>

                                        <a
                                            class="property-details-link"
                                            href="properties/details.php?id=<?= (int)$property['property_id'] ?>"
                                        >
                                            View Property
                                        </a>

                                    </div>
                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="empty-properties">
                            No common properties are available right now.
                        </div>

                    <?php endif; ?>

                </div>

            </main>

        </div>
    </div>
</section>

<script>
    const budgetRange = document.getElementById('budget_range');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');

    const areaRange = document.getElementById('area_range');
    const minAreaInput = document.getElementById('min_area');
    const maxAreaInput = document.getElementById('max_area');

    function syncRange(select, minInput, maxInput) {
        if (!select || !minInput || !maxInput) return;

        const parts = select.value.split('|');
        minInput.value = parts[0] || '';
        maxInput.value = parts[1] || '';
    }

    if (budgetRange) {
        budgetRange.addEventListener('change', () => {
            syncRange(budgetRange, minPriceInput, maxPriceInput);
        });
    }

    if (areaRange) {
        areaRange.addEventListener('change', () => {
            syncRange(areaRange, minAreaInput, maxAreaInput);
        });
    }

    const subcategories = <?= json_encode(
        $subcategories,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ) ?>;

    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    function loadSubcategories() {
        const categoryId = categorySelect.value;

        subcategorySelect.innerHTML =
            '<option value="">Any Property Type</option>';

        if (!categoryId) {
            return;
        }

        subcategories
            .filter(item => String(item.category_id) === String(categoryId))
            .forEach(item => {
                const option = document.createElement('option');
                option.value = item.subcategory_id;
                option.textContent = item.subcategory_name;
                subcategorySelect.appendChild(option);
            });
    }

    categorySelect.addEventListener('change', loadSubcategories);

    const filterToggle = document.getElementById('filterToggle');
    const requirementsSidebar = document.getElementById('requirementsSidebar');

    if (filterToggle) {
        filterToggle.addEventListener('click', () => {
            requirementsSidebar.classList.toggle('collapsed');
        });
    }

    const showAmenities = document.getElementById('showAmenities');
    const amenitiesFilter = document.querySelector('.amenities-filter');

    if (showAmenities && amenitiesFilter) {
        showAmenities.addEventListener('click', () => {
            amenitiesFilter.classList.toggle('expanded');

            showAmenities.textContent =
                amenitiesFilter.classList.contains('expanded')
                    ? '− Show fewer amenities'
                    : '+ Show more amenities';
        });
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
