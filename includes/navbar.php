<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';

?>

<nav class="navbar navbar-expand-lg un-navbar sticky-top">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand un-brand" href="/urbannest/index.php">

            <img src="/urbannest/assets/images/img1.png"
                class="un-brand-mark"
                alt="UrbanNest Logo">

            <span class="un-brand-text">
                Urban<span>Nest</span>
            </span>

        </a>

        <!-- Mobile Menu Button -->
        <button class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#unMenu"
            aria-controls="unMenu"
            aria-label="Toggle navigation">

            <i class="fa-solid fa-bars fs-4"></i>

        </button>

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

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle"
                                href="#"
                                id="profileDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i class="fa-solid fa-user"></i>
                                <?= htmlspecialchars($userName) ?>

                            </a>


                        <ul class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="profileDropdown">

                            <li>
                                <a class="dropdown-item"
                                    href="/urbannest/add-property.php">

                                    <i class="fa-solid fa-house"></i>
                                    Sell Property

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
                <a class="dropdown-item"
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