<!-- =========================================================
     URBANNEST ADMIN HEADER
========================================================= -->

<header class="un-topbar">

    <!-- MOBILE SIDEBAR BUTTON -->
    <div class="un-topbar-left">

        <button type="button"
            class="un-mobile-menu-btn"
            data-bs-toggle="offcanvas"
            data-bs-target="#unSidebarOffcanvas"
            aria-controls="unSidebarOffcanvas"
            aria-label="Open sidebar">

            ☰

        </button>

    </div>

    <!-- SEARCH -->
    <div class="un-topbar-center">

        <div class="un-search">

            <span class="un-search-icon">
                ⌕
            </span>

            <input
                type="text"
                class="un-search-input"
                placeholder="Search..."
                aria-label="Search">

        </div>

    </div>

    <!-- PROFILE -->
    <div class="un-topbar-right">

        <div class="un-profile">

            <!-- PROFILE BUTTON -->
            <button
                type="button"
                class="un-profile-btn"
                onclick="toggleProfileMenu()"
                aria-label="Open profile menu">

                <div class="un-profile-circle">
                    AU
                </div>

            </button>
            <!-- PROFILE DROPDOWN -->
            <div class="un-profile-menu" id="unProfileMenu">

                <!-- MY PROFILE -->
                <a href="/urbannest/admin/profile.php"
                    class="un-profile-item">

                    <i class="fa-solid fa-user"></i>
                    <span>My Profile</span>

                </a>

                <div class="un-profile-divider"></div>

                <!-- LOGOUT -->
                <a href="/urbannest/admin/logout.php"
                    class="un-profile-item logout-item">

                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>

                </a>

            </div>

        </div>

    </div>

</header>


<!-- =========================================================
     PROFILE SCRIPT
========================================================= -->

<script>
    function toggleProfileMenu() {

        var menu = document.getElementById("unProfileMenu");

        if (menu) {
            menu.classList.toggle("show");
        }

    }

    document.addEventListener("click", function(event) {

        var profile = document.querySelector(".un-profile");
        var menu = document.getElementById("unProfileMenu");

        if (
            profile &&
            menu &&
            !profile.contains(event.target)
        ) {
            menu.classList.remove("show");
        }

    });
</script>