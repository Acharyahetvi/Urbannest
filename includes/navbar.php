<nav class="navbar navbar-expand-lg un-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand un-brand" href="<?= url('index.php') ?>">
            <span class="un-brand-mark"><i class="fa-solid fa-building-columns"></i></span>
            <span class="un-brand-text">Urban<span>Nest</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#unMenu">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>

        <div class="offcanvas offcanvas-end un-offcanvas" id="unMenu">
            <div class="offcanvas-header">
                <span class="un-brand-text fs-4">Urban<span>Nest</span></span>
                <button class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link <?= is_active('index.php') ?>" href="<?= url('index.php') ?>">Home</a></li>

                    <li class="nav-item dropdown un-mega">
                        <a class="nav-link dropdown-toggle <?= is_active('properties') ?>" href="#" data-bs-toggle="dropdown">Properties</a>
                        <div class="dropdown-menu un-mega-menu p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <h6 class="un-mega-title">By Purpose</h6>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?purpose=buy') ?>"><i class="fa-solid fa-key"></i> Buy a Home</a>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?purpose=rent') ?>"><i class="fa-solid fa-house-chimney"></i> Rent a Home</a>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?purpose=sell') ?>"><i class="fa-solid fa-hand-holding-dollar"></i> Sell Property</a>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="un-mega-title">Categories</h6>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?cat=villa') ?>"><i class="fa-solid fa-tree-city"></i> Luxury Villas</a>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?cat=penthouse') ?>"><i class="fa-solid fa-building"></i> Penthouses</a>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?cat=apartment') ?>"><i class="fa-solid fa-city"></i> Apartments</a>
                                    <a class="un-mega-link" href="<?= url('properties/list.php?cat=commercial') ?>"><i class="fa-solid fa-warehouse"></i> Commercial</a>
                                </div>
                                <div class="col-md-4">
                                    <div class="un-mega-promo">
                                        <span class="badge bg-gold mb-2">Featured</span>
                                        <h5 class="mb-2">Curated Collection</h5>
                                        <p class="small mb-3">Hand‑picked luxury estates across four continents.</p>
                                        <a href="<?= url('properties/list.php') ?>" class="btn btn-sm btn-outline-gold">Explore All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item"><a class="nav-link <?= is_active('agents') ?>" href="<?= url('agents.php') ?>">Agents</a></li>
                    <li class="nav-item"><a class="nav-link <?= is_active('owners') ?>" href="<?= url('owners.php') ?>">Owners</a></li>
                    <li class="nav-item"><a class="nav-link <?= is_active('blog') ?>" href="<?= url('blog.php') ?>">Blog</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Pages</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= url('about.php') ?>">About</a></li>
                            <li><a class="dropdown-item" href="<?= url('faq.php') ?>">FAQ</a></li>
                            <li><a class="dropdown-item" href="<?= url('contact.php') ?>">Contact</a></li>
                        </ul>
                    </li>

                    <li class="nav-item ms-lg-3 d-flex gap-2 mt-3 mt-lg-0">
                        <a href="<?= url('auth/login.php') ?>" class="btn btn-outline-navy btn-sm px-3">Login</a>
                        <a href="<?= url('auth/register.php') ?>" class="btn btn-gold btn-sm px-3">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
