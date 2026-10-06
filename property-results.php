<?php
$pageTitle = 'Property Results — UrbanNest';
$pageDesc = 'Find properties matching your requirements.';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

/*
|--------------------------------------------------------------------------
| GET FILTERS
|--------------------------------------------------------------------------
*/

$type = strtolower(trim($_GET['type'] ?? 'buy'));

if (!in_array($type, ['buy', 'rent', 'sale'], true)) {
    $type = 'buy';
}

$listingType = ($type === 'rent') ? 'For Rent' : 'For Sale';

$categoryId    = (int)($_GET['category_id'] ?? 0);
$subcategoryId = (int)($_GET['subcategory_id'] ?? 0);

$city       = trim($_GET['city'] ?? '');
$minPrice   = trim($_GET['min_price'] ?? '');
$maxPrice   = trim($_GET['max_price'] ?? '');
$minArea    = trim($_GET['min_area'] ?? '');
$maxArea    = trim($_GET['max_area'] ?? '');
$bedrooms   = trim($_GET['bedrooms'] ?? '');
$bathrooms  = trim($_GET['bathrooms'] ?? '');
$furnishing = trim($_GET['furnishing'] ?? '');
$condition  = trim($_GET['condition'] ?? '');
$parking    = trim($_GET['parking'] ?? '');

$amenities = $_GET['amenities'] ?? [];

if (!is_array($amenities)) {
    $amenities = [];
}

$amenities = array_values(
    array_filter(
        array_map('intval', $amenities),
        static fn($id) => $id > 0
    )
);


/*
|--------------------------------------------------------------------------
| BUILD PROPERTY QUERY
|--------------------------------------------------------------------------
| Every filter is optional.
| Selected amenities use AND logic:
| a property must contain ALL selected amenities.
|--------------------------------------------------------------------------
*/

$where = [
    "p.approval_status = 'Approved'",
    "p.status = 'Active'",
    "p.listing_type = ?",
    "EXISTS (
        SELECT 1
        FROM property_images pi_photo
        WHERE pi_photo.property_id = p.property_id
          AND pi_photo.is_main = 1
    )"
];

$params = [$listingType];
$types  = "s";

if ($categoryId > 0) {
    $where[] = "s.category_id = ?";
    $params[] = $categoryId;
    $types .= "i";
}

if ($subcategoryId > 0) {
    $where[] = "p.subcategory_id = ?";
    $params[] = $subcategoryId;
    $types .= "i";
}

if ($city !== '') {
    // The DB contains 7 intended cities; one older Ahmedabad row is misspelled
    // as "Ahemdabad", so treat both spellings as Ahmedabad.
    $where[] = "REPLACE(LOWER(p.city), 'ahemdabad', 'ahmedabad') = LOWER(?)";
    $params[] = $city;
    $types .= "s";
}

if ($minPrice !== '' && is_numeric($minPrice)) {
    $where[] = "p.price >= ?";
    $params[] = (float)$minPrice;
    $types .= "d";
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $where[] = "p.price <= ?";
    $params[] = (float)$maxPrice;
    $types .= "d";
}

if ($minArea !== '' && is_numeric($minArea)) {
    $where[] = "p.area_sqft >= ?";
    $params[] = (int)$minArea;
    $types .= "i";
}

if ($maxArea !== '' && is_numeric($maxArea)) {
    $where[] = "p.area_sqft <= ?";
    $params[] = (int)$maxArea;
    $types .= "i";
}

if ($bedrooms !== '' && ctype_digit($bedrooms)) {
    $where[] = "p.bedrooms = ?";
    $params[] = (int)$bedrooms;
    $types .= "i";
}

if ($bathrooms !== '' && ctype_digit($bathrooms)) {
    $where[] = "p.bathrooms = ?";
    $params[] = (int)$bathrooms;
    $types .= "i";
}

if ($furnishing !== '') {
    $where[] = "p.furnishing = ?";
    $params[] = $furnishing;
    $types .= "s";
}

if ($condition !== '') {
    $where[] = "p.property_condition = ?";
    $params[] = $condition;
    $types .= "s";
}

if ($parking !== '' && ctype_digit($parking)) {
    $where[] = "p.parking_spaces >= ?";
    $params[] = (int)$parking;
    $types .= "i";
}


/*
|--------------------------------------------------------------------------
| AMENITY FILTER
|--------------------------------------------------------------------------
| property_amenities uses amenity_id in the current database.
|--------------------------------------------------------------------------
*/

foreach ($amenities as $amenityId) {
    $where[] = "EXISTS (
        SELECT 1
        FROM property_amenities pa_filter
        WHERE pa_filter.property_id = p.property_id
          AND pa_filter.amenity_id = ?
    )";
    $params[] = $amenityId;
    $types .= "i";
}


/*
|--------------------------------------------------------------------------
| MAIN QUERY
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT DISTINCT
        p.property_id,
        p.title,
        p.description,
        p.listing_type,
        p.price,
        p.area_sqft,
        p.bedrooms,
        p.bathrooms,
        p.furnishing,
        p.parking_spaces,
        p.floor_number,
        p.total_floors,
        p.address,
        p.city,
        p.state,
        p.property_condition,
        s.subcategory_name,
        pi.image_path
    FROM properties p
    LEFT JOIN sub_categories s
        ON p.subcategory_id = s.subcategory_id
    LEFT JOIN property_images pi
        ON p.property_id = pi.property_id
        AND pi.is_main = 1
    WHERE " . implode(" AND ", $where) . "
    ORDER BY p.property_id ASC
";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = false;
}


/*
|--------------------------------------------------------------------------
| IMAGE HELPER
|--------------------------------------------------------------------------
*/

function resultPropertyImage(?string $path): string
{
    if (!empty($path)) {
        return 'admin/' . ltrim($path, '/');
    }

    return 'admin/assets/images/no-property.png';
}


/*
|--------------------------------------------------------------------------
| DISPLAY LABEL
|--------------------------------------------------------------------------
*/

$typeLabel = ($type === 'rent') ? 'Rental' : 'Properties for Sale';

?>

<style>
    .results-section {
        padding: 55px 0 70px;
        background: #f8f7f2;
        min-height: 70vh;
    }

    .results-heading {
        margin-bottom: 30px;
    }

    .results-heading h1 {
        color: #0b1f3a;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .results-heading p {
        color: #687386;
        margin: 0;
    }

    .results-topbar {
        background: #fff;
        border: 1px solid #eee9dc;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .results-count {
        color: #0b1f3a;
        font-weight: 600;
    }

    .results-change {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 15px;
        border-radius: 7px;
        background: #0b1f3a;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .results-change:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .result-card {
        height: 100%;
        background: #fff;
        border: 1px solid #eee9dc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
        transition: 0.3s;
    }

    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(11, 31, 58, 0.14);
    }

    .result-image {
        width: 100%;
        height: 225px;
        object-fit: cover;
        display: block;
    }

    .result-body {
        padding: 19px;
    }

    .result-title {
        color: #0b1f3a;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .result-price {
        color: #b9922e;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .result-location {
        color: #687386;
        font-size: 14px;
        margin-bottom: 13px;
    }

    .result-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 13px;
    }

    .result-meta span {
        background: #f5f5f2;
        color: #555;
        padding: 6px 9px;
        border-radius: 5px;
        font-size: 13px;
    }

    .result-subcategory {
        display: inline-block;
        margin-bottom: 8px;
        color: #687386;
        font-size: 13px;
    }

    .result-btn {
        display: block;
        width: 100%;
        padding: 11px;
        margin-top: 17px;
        background: #0b1f3a;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
        transition: 0.3s;
    }

    .result-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .results-empty {
        background: #fff;
        border: 1px solid #eee9dc;
        border-radius: 12px;
        padding: 55px 25px;
        text-align: center;
    }

    .results-empty i {
        font-size: 38px;
        color: #b9922e;
        margin-bottom: 15px;
    }

    .results-empty h3 {
        color: #0b1f3a;
        font-size: 22px;
        margin-bottom: 8px;
    }

    .results-empty p {
        color: #687386;
        margin-bottom: 20px;
    }

    .results-empty a {
        display: inline-block;
        padding: 10px 18px;
        background: #0b1f3a;
        color: #fff;
        border-radius: 7px;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .results-topbar {
            flex-direction: column;
            align-items: stretch;
        }

        .results-change {
            justify-content: center;
        }
    }
</style>

<section class="results-section">
    <div class="container">

        <div class="results-heading">
            <h1>Matching Properties</h1>
            <p>Properties matching your selected requirements.</p>
        </div>

        <?php
        $resultCount = ($result) ? mysqli_num_rows($result) : 0;
        ?>

        <div class="results-topbar">
            <div class="results-count">
                <?php echo $resultCount; ?>
                <?php echo ($resultCount === 1) ? 'property' : 'properties'; ?>
                found
            </div>

            <a href="requirements.php?type=<?php echo urlencode($type); ?>" class="results-change">
                <i class="fa-solid fa-sliders"></i>
                Change Requirements
            </a>
        </div>

        <div class="row g-4">

            <?php if ($result && $resultCount > 0): ?>

                <?php while ($property = mysqli_fetch_assoc($result)): ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="result-card">

                            <img
                                src="<?php echo htmlspecialchars(resultPropertyImage($property['image_path'])); ?>"
                                class="result-image"
                                alt="<?php echo htmlspecialchars($property['title']); ?>"
                            >

                            <div class="result-body">

                                <?php if (!empty($property['subcategory_name'])): ?>
                                    <span class="result-subcategory">
                                        <?php echo htmlspecialchars($property['subcategory_name']); ?>
                                    </span>
                                <?php endif; ?>

                                <h5 class="result-title">
                                    <?php echo htmlspecialchars($property['title']); ?>
                                </h5>

                                <div class="result-price">
                                    ₹<?php echo number_format((float)$property['price']); ?><?php if ($property['listing_type'] === 'For Rent') echo ' / month'; ?>
                                </div>

                                <div class="result-location">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?php echo htmlspecialchars($property['city']); ?>,
                                    <?php echo htmlspecialchars($property['state']); ?>
                                </div>

                                <hr>

                                <div class="result-meta">

                                    <?php if ($property['area_sqft'] !== null): ?>
                                        <span>
                                            <i class="fa-solid fa-ruler-combined"></i>
                                            <?php echo number_format((float)$property['area_sqft']); ?> sq.ft.
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($property['bedrooms'] !== null): ?>
                                        <span>
                                            <i class="fa-solid fa-bed"></i>
                                            <?php echo (int)$property['bedrooms']; ?> BHK
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($property['bathrooms'] !== null): ?>
                                        <span>
                                            <i class="fa-solid fa-bath"></i>
                                            <?php echo (int)$property['bathrooms']; ?> Bath
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($property['parking_spaces'] !== null): ?>
                                        <span>
                                            <i class="fa-solid fa-car"></i>
                                            <?php echo (int)$property['parking_spaces']; ?> Parking
                                        </span>
                                    <?php endif; ?>

                                </div>

                                <a
                                    href="properties/details.php?id=<?php echo (int)$property['property_id']; ?>"
                                    class="result-btn"
                                >
                                    View Details
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12">
                    <div class="results-empty">
                        <i class="fa-solid fa-house-circle-xmark"></i>

                        <h3>No Matching Properties Found</h3>

                        <p>
                            We couldn't find a property matching all of your selected requirements.
                            Try changing one or more filters.
                        </p>

                        <a href="requirements.php?type=<?php echo urlencode($type); ?>">
                            Change Requirements
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>
</section>

<?php
if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}

require_once __DIR__ . '/includes/footer.php';
?>
