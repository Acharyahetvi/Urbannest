<?php
/**
 * UrbanNest — demo dataset
 *
 * These arrays mirror the shape of the eventual MySQL rows so pages can be
 * wired to real queries by swapping `require`s. Keys align loosely with the
 * documented tables: properties, property_images, categories, cities, states,
 * agents, owners, amenities.
 */

$UN_STATES = [
    ['id'=>1,'name'=>'New York'], ['id'=>2,'name'=>'California'],
    ['id'=>3,'name'=>'Florida'], ['id'=>4,'name'=>'Texas'],
    ['id'=>5,'name'=>'Illinois'], ['id'=>6,'name'=>'Massachusetts'],
];

$UN_CITIES = [
    ['id'=>1,'state_id'=>1,'name'=>'Manhattan',  'img'=>'https://images.unsplash.com/photo-1522083165195-3424ed129620?auto=format&fit=crop&w=900&q=80',  'count'=>128],
    ['id'=>2,'state_id'=>1,'name'=>'Brooklyn',   'img'=>'https://images.unsplash.com/photo-1503572327579-b5c6afe5c5c5?auto=format&fit=crop&w=900&q=80',  'count'=>94],
    ['id'=>3,'state_id'=>2,'name'=>'Los Angeles','img'=>'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=900&q=80',  'count'=>176],
    ['id'=>4,'state_id'=>2,'name'=>'San Francisco','img'=>'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=900&q=80','count'=>81],
    ['id'=>5,'state_id'=>3,'name'=>'Miami',      'img'=>'https://images.unsplash.com/photo-1514214246283-d427a95c5d2f?auto=format&fit=crop&w=900&q=80',  'count'=>112],
    ['id'=>6,'state_id'=>4,'name'=>'Austin',     'img'=>'https://images.unsplash.com/photo-1531218150217-54595bc2b934?auto=format&fit=crop&w=900&q=80', 'count'=>67],
];

$UN_CATEGORIES = [
    ['id'=>1,'name'=>'Luxury Villas','slug'=>'villa','img'=>'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80'],
    ['id'=>2,'name'=>'Penthouses',   'slug'=>'penthouse','img'=>'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80'],
    ['id'=>3,'name'=>'Apartments',   'slug'=>'apartment','img'=>'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80'],
    ['id'=>4,'name'=>'Waterfront',   'slug'=>'waterfront','img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80'],
    ['id'=>5,'name'=>'Commercial',   'slug'=>'commercial','img'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80'],
    ['id'=>6,'name'=>'Country Estate','slug'=>'estate','img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=900&q=80'],
];

$UN_AMENITIES = [
    ['id'=>1,'name'=>'Infinity Pool','icon'=>'fa-solid fa-water-ladder'],
    ['id'=>2,'name'=>'Home Cinema','icon'=>'fa-solid fa-film'],
    ['id'=>3,'name'=>'Wine Cellar','icon'=>'fa-solid fa-wine-glass'],
    ['id'=>4,'name'=>'Private Gym','icon'=>'fa-solid fa-dumbbell'],
    ['id'=>5,'name'=>'Smart Home','icon'=>'fa-solid fa-house-signal'],
    ['id'=>6,'name'=>'Concierge','icon'=>'fa-solid fa-bell-concierge'],
    ['id'=>7,'name'=>'Rooftop Terrace','icon'=>'fa-solid fa-mountain-city'],
    ['id'=>8,'name'=>'EV Charging','icon'=>'fa-solid fa-charging-station'],
    ['id'=>9,'name'=>'Ocean View','icon'=>'fa-solid fa-water'],
    ['id'=>10,'name'=>'Private Elevator','icon'=>'fa-solid fa-elevator'],
    ['id'=>11,'name'=>'Chef\'s Kitchen','icon'=>'fa-solid fa-utensils'],
    ['id'=>12,'name'=>'Guard House','icon'=>'fa-solid fa-shield-halved'],
];

$UN_AGENTS = [
    ['id'=>1,'name'=>'Isabella Cortez','role'=>'Senior Advisor','avatar'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80','listings'=>34,'sold'=>128,'phone'=>'+1 212 555 0139','email'=>'isabella@urbannest.com'],
    ['id'=>2,'name'=>'Marcus Ashford','role'=>'Luxury Broker','avatar'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80','listings'=>21,'sold'=>96,'phone'=>'+1 212 555 0141','email'=>'marcus@urbannest.com'],
    ['id'=>3,'name'=>'Naomi Sato','role'=>'Investment Specialist','avatar'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80','listings'=>28,'sold'=>112,'phone'=>'+1 212 555 0155','email'=>'naomi@urbannest.com'],
    ['id'=>4,'name'=>'Elias Bergmann','role'=>'International Sales','avatar'=>'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80','listings'=>19,'sold'=>74,'phone'=>'+1 212 555 0162','email'=>'elias@urbannest.com'],
];

$UN_OWNERS = [
    ['id'=>1,'name'=>'Ophelia Marchetti','role'=>'Private Owner','avatar'=>'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80','portfolio'=>4,'city'=>'Miami','since'=>'2018'],
    ['id'=>2,'name'=>'Rowan Whitfield','role'=>'Portfolio Owner','avatar'=>'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=400&q=80','portfolio'=>9,'city'=>'Manhattan','since'=>'2015'],
    ['id'=>3,'name'=>'Amara Okonkwo','role'=>'Estate Owner','avatar'=>'https://images.unsplash.com/photo-1614283233556-f35b0c801ef1?auto=format&fit=crop&w=400&q=80','portfolio'=>3,'city'=>'Los Angeles','since'=>'2020'],
    ['id'=>4,'name'=>'Henrik Solberg','role'=>'Commercial Owner','avatar'=>'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80','portfolio'=>6,'city'=>'Austin','since'=>'2019'],
];

$UN_PROPERTIES = [
    [
        'id'=>'UN-1024','title'=>'Skyline Penthouse at 432 Park',
        'address'=>'432 Park Avenue, Manhattan','city'=>'Manhattan','state'=>'New York',
        'price'=>7850000,'purpose'=>'buy','category'=>'Penthouse','category_slug'=>'penthouse',
        'status'=>'For Sale','beds'=>4,'baths'=>5,'area'=>4820,'featured'=>true,'luxury'=>true,
        'image'=>'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>1,'owner_id'=>2,
    ],
    [
        'id'=>'UN-1025','title'=>'Oceanfront Villa Palma',
        'address'=>'Star Island Drive, Miami','city'=>'Miami','state'=>'Florida',
        'price'=>12400000,'purpose'=>'buy','category'=>'Waterfront Villa','category_slug'=>'waterfront',
        'status'=>'For Sale','beds'=>6,'baths'=>7,'area'=>9200,'featured'=>true,'luxury'=>true,
        'image'=>'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>2,'owner_id'=>1,
    ],
    [
        'id'=>'UN-1026','title'=>'Hollywood Hills Modernist',
        'address'=>'Blue Jay Way, Los Angeles','city'=>'Los Angeles','state'=>'California',
        'price'=>4750000,'purpose'=>'buy','category'=>'Luxury Villa','category_slug'=>'villa',
        'status'=>'For Sale','beds'=>5,'baths'=>4,'area'=>5680,'featured'=>true,'luxury'=>false,
        'image'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>3,'owner_id'=>3,
    ],
    [
        'id'=>'UN-1027','title'=>'SoHo Loft Residence',
        'address'=>'Greene Street, Manhattan','city'=>'Manhattan','state'=>'New York',
        'price'=>9800,'purpose'=>'rent','category'=>'Loft','category_slug'=>'apartment',
        'status'=>'For Rent','beds'=>3,'baths'=>2,'area'=>2140,'featured'=>false,'luxury'=>true,
        'image'=>'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>1,'owner_id'=>2,
    ],
    [
        'id'=>'UN-1028','title'=>'Brooklyn Heights Brownstone',
        'address'=>'Willow Street, Brooklyn','city'=>'Brooklyn','state'=>'New York',
        'price'=>3250000,'purpose'=>'buy','category'=>'Townhouse','category_slug'=>'estate',
        'status'=>'For Sale','beds'=>4,'baths'=>3,'area'=>3400,'featured'=>false,'luxury'=>false,
        'image'=>'https://images.unsplash.com/photo-1600585154084-4e5fe7c39198?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>4,'owner_id'=>2,
    ],
    [
        'id'=>'UN-1029','title'=>'Downtown Executive Tower Floor',
        'address'=>'Michigan Avenue, Chicago','city'=>'Chicago','state'=>'Illinois',
        'price'=>18500,'purpose'=>'rent','category'=>'Commercial','category_slug'=>'commercial',
        'status'=>'For Rent','beds'=>0,'baths'=>4,'area'=>8500,'featured'=>false,'luxury'=>false,
        'image'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>2,'owner_id'=>4,
    ],
    [
        'id'=>'UN-1030','title'=>'Bay Area Glass House',
        'address'=>'Pacific Heights, San Francisco','city'=>'San Francisco','state'=>'California',
        'price'=>6150000,'purpose'=>'buy','category'=>'Luxury Villa','category_slug'=>'villa',
        'status'=>'For Sale','beds'=>5,'baths'=>5,'area'=>6120,'featured'=>true,'luxury'=>true,
        'image'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>3,'owner_id'=>3,
    ],
    [
        'id'=>'UN-1031','title'=>'Austin Hilltop Residence',
        'address'=>'Barton Creek, Austin','city'=>'Austin','state'=>'Texas',
        'price'=>1890000,'purpose'=>'buy','category'=>'Family Home','category_slug'=>'estate',
        'status'=>'For Sale','beds'=>4,'baths'=>3,'area'=>3980,'featured'=>false,'luxury'=>false,
        'image'=>'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>4,'owner_id'=>4,
    ],
    [
        'id'=>'UN-1032','title'=>'Central Park Corner Suite',
        'address'=>'Central Park South, Manhattan','city'=>'Manhattan','state'=>'New York',
        'price'=>14200,'purpose'=>'rent','category'=>'Apartment','category_slug'=>'apartment',
        'status'=>'For Rent','beds'=>2,'baths'=>2,'area'=>1850,'featured'=>false,'luxury'=>true,
        'image'=>'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80',
        'agent_id'=>1,'owner_id'=>2,
    ],
];

/**
 * Render a property card.
 *
 * @param array $p One entry from $UN_PROPERTIES.
 */
function un_render_card(array $p): void {
    $purposeLabel = $p['purpose'] === 'rent' ? '/mo' : '';
    $price = $p['purpose'] === 'rent'
        ? '$' . number_format((float)$p['price'])
        : '$' . number_format((float)$p['price']);
    ?>
    <article class="un-card">
        <div class="un-card-img">
            <a href="<?= url('properties/details.php?id=' . urlencode($p['id'])) ?>">
                <img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
            </a>
            <div class="un-card-badges">
                <?php if (!empty($p['featured'])): ?><span class="un-badge un-badge-featured"><i class="fa-solid fa-star"></i> Featured</span><?php endif; ?>
                <?php if (!empty($p['luxury'])):  ?><span class="un-badge un-badge-luxury"><i class="fa-solid fa-crown"></i> Luxury</span><?php endif; ?>
                <?php if ($p['purpose']==='rent'): ?><span class="un-badge un-badge-rent">For Rent</span><?php endif; ?>
            </div>
            <div class="un-card-actions">
                <button class="un-icon-btn" data-toggle="wishlist" title="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                <button class="un-icon-btn" data-toggle="compare"  title="Compare"><i class="fa-solid fa-code-compare"></i></button>
                <button class="un-icon-btn" data-toggle="share"    title="Share"><i class="fa-solid fa-share-nodes"></i></button>
            </div>
            <div class="un-card-price"><?= $price ?><?php if($purposeLabel): ?> <small><?= $purposeLabel ?></small><?php endif; ?></div>
        </div>
        <div class="un-card-body">
            <div class="un-card-cat"><?= e($p['category']) ?> · <?= e($p['id']) ?></div>
            <h3 class="un-card-title"><a href="<?= url('properties/details.php?id=' . urlencode($p['id'])) ?>"><?= e($p['title']) ?></a></h3>
            <p class="un-card-addr"><i class="fa-solid fa-location-dot"></i> <?= e($p['address']) ?></p>
            <div class="un-card-meta">
                <?php if ($p['beds']>0): ?><span><i class="fa-solid fa-bed"></i><?= (int)$p['beds'] ?> Beds</span><?php else: ?><span><i class="fa-solid fa-briefcase"></i>Office</span><?php endif; ?>
                <span><i class="fa-solid fa-bath"></i><?= (int)$p['baths'] ?> Baths</span>
                <span><i class="fa-solid fa-vector-square"></i><?= number_format((int)$p['area']) ?> sqft</span>
            </div>
        </div>
        <div class="un-card-foot">
            <a href="<?= url('properties/details.php?id=' . urlencode($p['id'])) ?>" class="btn btn-sm btn-outline-navy">View Details</a>
            <span class="text-muted small"><i class="fa-regular fa-eye"></i> <?= random_int(120, 980) ?></span>
        </div>
    </article>
    <?php
}
