<?php

require_once __DIR__ . '/db.php';

$pageTitle = $pageTitle ?? 'UrbanNest — Luxury Real Estate';
$pageDesc  = $pageDesc  ?? 'Discover premium homes, penthouses & investment properties curated by UrbanNest.';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($pageDesc) ?>">
    <title><?= e($pageTitle) ?></title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="http://localhost/xampp/UrbanNest/assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Playfair + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">


</head>

<body>
    <div class="un-topbar d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="small">
                <span class="me-3"><i class="fa-regular fa-envelope me-2"></i>hello@urbannest.com</span>
                <span><i class="fa-solid fa-phone me-2"></i>+1 (212) 555‑0117</span>
            </div>
            <div class="small d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center gap-2"><i class="fa-solid fa-location-dot"></i> 5th Ave, New York</span>
                <span class="vr opacity-25"></span>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>