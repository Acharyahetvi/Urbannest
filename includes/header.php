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

    <link rel="stylesheet" href="/urbannest/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/urbannest/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts: Playfair + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">


</head>

<body>
    <div class="un-topbar d-none d-lg-block">
    </div>