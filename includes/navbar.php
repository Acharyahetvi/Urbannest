<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$userRole = $_SESSION['user_role'] ?? null;
$roleLabels = [
    1 => 'Admin',
    2 => 'Buyer',
    3 => 'Agent',
    4 => 'Seller',
    5 => 'Owner'
];
$userRoleLabel = $roleLabels[(int)$userRole] ?? '';
$nameParts = preg_split('/\s+/', trim($userName), -1, PREG_SPLIT_NO_EMPTY);
$userInitials = '';

foreach (array_slice($nameParts ?: [], 0, 2) as $namePart) {
    $userInitials .= strtoupper(function_exists('mb_substr')
        ? mb_substr($namePart, 0, 1, 'UTF-8')
        : substr($namePart, 0, 1));
}

?>

<style>
    .un-account-menu {
        width: 248px;
        max-width: calc(100vw - 24px);
        padding: 8px;
        border: 1px solid #e4ddd0;
        border-radius: 8px;
        box-shadow: 0 8px 22px rgba(11, 31, 58, 0.12);
    }

    .un-account-toggle {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .un-account-toggle>i {
        color: #a88727;
        font-size: 13px;
    }

    .un-account-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 9px 13px;
    }

    .un-account-profile-link {
        color: inherit;
        text-decoration: none;
    }

    .un-account-header>div {
        min-width: 0;
    }

    .un-account-avatar {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        place-items: center;
        border-radius: 50%;
        background: #0b1f3a;
        color: #d4af37;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 15px;
        font-weight: 600;
    }

    .un-account-name {
        overflow: hidden;
        color: #0b1f3a;
        font-size: 14px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .un-account-role {
        margin-top: 2px;
        color: #8a7440;
        font-size: 11px;
    }

    .un-account-menu .dropdown-divider {
        margin: 4px 0;
        border-color: #edf0f4;
    }

    .un-account-menu .dropdown-item {
        display: flex;
        min-height: 38px;
        align-items: center;
        gap: 10px;
        border-radius: 5px;
        color: #26364d;
        font-size: 13px;
        transition: background-color 0.16s ease, color 0.16s ease;
    }

    .un-account-menu .dropdown-item:hover,
    .un-account-menu .dropdown-item:focus {
        background: #f8f5ea;
        color: #0b1f3a;
    }

    .un-account-menu .dropdown-item i {
        width: 16px;
        color: #a88727;
        text-align: center;
    }

    .un-account-menu .un-logout-item {
        color: #566174;
    }

    @media (max-width: 991.98px) {

        /* Prevent closed offcanvas from causing horizontal overflow */
        html {
            overflow-x: clip;
        }

        /* Ensure the mobile offcanvas is fixed, constrained to viewport width,
           and slid fully onscreen when shown. Use a safe max width of 390px. */
        .un-offcanvas {
            position: fixed !important;
            top: 0;
            left: 0;
            right: auto;
            height: 100vh;
            width: min(390px, 100vw);
            max-width: 100vw;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            will-change: transform;
            overflow-y: auto;
        }

        /* When Bootstrap toggles the show class, make sure panel is visible */
        .un-offcanvas.show,
        .un-offcanvas.showing {
            transform: none !important;
        }
    }
</style>

<nav class="navbar navbar-expand-lg un-navbar sticky-top">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand un-brand" href="/urbannest/index.php">

            <img src="/urbannest/assets/images/img1.png"
                class="un-brand-mark"
                alt="UrbanNest Logo">

            <span class="un-brand-text">
                <h2>Urban<span>Nest</span></h2>
            </span>

        </a>

        <!-- Mobile Menu Button
        <button class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#unMenu"
            aria-controls="unMenu"
            aria-label="Toggle navigation">

            <i class="fa-solid fa-bars fs-4"></i>

        </button> -->

        <!-- Navbar Menu -->
        <div class="offcanvas offcanvas-end un-offcanvas"
            tabindex="-1"
            id="unMenu">

            <!-- Offcanvas Header -->
            <div class="offcanvas-header">

                <span class="un-brand-text fs-4">
                    Urban<span>Nest</span>
                </span>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
                </button>

            </div>

            <!-- Offcanvas Body -->
            <div class="offcanvas-body">

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/urbannest/index.php">
                            Home
                        </a>
                    </li>

                    <!-- Properties -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/urbannest/properties/list.php">
                            Properties
                        </a>
                    </li>

                    <!-- Agents -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/urbannest/agents.php">
                            Agents
                        </a>
                    </li>

                    <!-- Owners -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/urbannest/owners.php">
                            Owners
                        </a>
                    </li>

                    <!-- About -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/urbannest/about.php">
                            About Us
                        </a>
                    </li>

                    <?php if (!$isLoggedIn) { ?>

                        <!-- Login -->

                        <li class="nav-item">
                            <a class="nav-link"
                                href="/urbannest/auth/login.php">
                                Login
                            </a>
                        </li>


                        <!-- Register -->

                        <li class="nav-item">
                            <a class="nav-link"
                                href="/urbannest/auth/register.php">
                                Register
                            </a>
                        </li>

                    <?php } else { ?>

                        <!-- Profile -->

                        <li class="nav-item dropdown d-flex align-items-center">

                            <a class="nav-link dropdown-toggle un-account-toggle"
                                href=""
                                id="profileDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i class="fa-solid fa-user"></i>
                                <?= htmlspecialchars($userName) ?>

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end un-account-menu"
                                aria-labelledby="profileDropdown">

                                <li>
                                    <a class="un-account-header un-account-profile-link"
                                        href="/urbannest/auth/profile.php">
                                        <span class="un-account-avatar" aria-hidden="true">
                                            <?= htmlspecialchars($userInitials !== '' ? $userInitials : 'U') ?>
                                        </span>
                                        <div>
                                            <div class="un-account-name"><?= htmlspecialchars($userName) ?></div>
                                            <?php if ($userRoleLabel !== ''): ?>
                                                <div class="un-account-role"><?= htmlspecialchars($userRoleLabel) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                            <li>
                                <a class="dropdown-item"
                                    href="/urbannest/add-property.php">
                                    <i class="fa-solid fa-house"></i>
                                    Add Property
                                </a>
                            </li>

                                <li>
                                    <a class="dropdown-item"
                                        href="/urbannest/wishlist.php">
                                        <i class="fa-solid fa-heart"></i>
                                        Wishlist
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item un-logout-item"
                                        href="/urbannest/auth/logout.php">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php } ?>

                </ul>

            </div>

        </div>

    </div>

</nav>