<?php
$pageTitle = 'FAQ — UrbanNest';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';

$faqs = [
    'Buying' => [
        ['q'=>'How do I schedule a private viewing?', 'a'=>'You can request a private viewing from any property page or contact your assigned advisor directly. Viewings are typically confirmed within 24 hours.'],
        ['q'=>'Do you represent off‑market properties?', 'a'=>'Yes. A significant portion of our inventory is never publicly listed. Speak with an advisor to access our private portfolio.'],
        ['q'=>'Can I purchase as an international buyer?', 'a'=>'Absolutely. We work with international buyers daily and coordinate remote closings, escrow, and financing partners.'],
    ],
    'Selling' => [
        ['q'=>'What does your marketing include?', 'a'=>'Every listing includes cinematic photography, drone footage, a bespoke property site, targeted media, and inclusion in our private client dispatches.'],
        ['q'=>'How is my valuation determined?', 'a'=>'Our advisors combine comparable sales, current market velocity, and a physical assessment of your home to produce an evidence‑based valuation.'],
    ],
    'Renting' => [
        ['q'=>'Are rental terms flexible?', 'a'=>'Yes. Many of our owners offer six, nine, or twelve‑month terms, and some furnished residences can accommodate shorter stays.'],
        ['q'=>'What documents do you need to apply?', 'a'=>'Identification, proof of income or bank statements, and prior landlord references are standard. Your advisor will guide you.'],
    ],
    'Account' => [
        ['q'=>'How do I save properties to my wishlist?', 'a'=>'Create an account and use the heart icon on any listing. Your wishlist syncs across devices.'],
        ['q'=>'Can I compare multiple properties?', 'a'=>'Yes. Use the compare button on cards to build a side‑by‑side view of up to four homes.'],
    ],
];
?>

<section class="un-page-head">
    <div class="container">
        <nav><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li><li class="breadcrumb-item active">FAQ</li></ol></nav>
        <h1>Answers, at a glance.</h1>
        <p class="opacity-75 mb-0">Common questions about buying, selling and renting with UrbanNest.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="un-detail-box position-sticky" style="top: 110px;">
                    <span class="eyebrow">Need more help?</span>
                    <h4 class="mt-2 mb-3" style="font-family:'Playfair Display',serif;">Still have a question?</h4>
                    <p class="text-muted small mb-3">Our concierge team is available Monday to Saturday.</p>
                    <a href="<?= url('contact.php') ?>" class="btn btn-gold w-100 mb-2"><i class="fa-regular fa-envelope me-2"></i>Send a message</a>
                    <a href="tel:+12125550117" class="btn btn-outline-navy w-100"><i class="fa-solid fa-phone me-2"></i>+1 (212) 555‑0117</a>
                </div>
            </div>
            <div class="col-lg-8">
                <?php $idx = 0; foreach ($faqs as $section => $items): ?>
                    <h3 class="section-title mb-3" style="font-size:1.4rem;"><?= e($section) ?></h3>
                    <div class="accordion un-accordion mb-5" id="acc<?= md5($section) ?>">
                        <?php foreach ($items as $it): $idx++; ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $idx ?>"><?= e($it['q']) ?></button>
                                </h2>
                                <div id="faq<?= $idx ?>" class="accordion-collapse collapse">
                                    <div class="accordion-body"><?= e($it['a']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
