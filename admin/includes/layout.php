<?php
/*
|--------------------------------------------------------------------------
| UrbanNest Admin Common Layout
|--------------------------------------------------------------------------
| This file loads:
| - Bootstrap CSS
| - Font Awesome
| - Admin CSS
| - Sidebar
| - Header
| - Page content
| - Footer
|
| Usage from an admin page:
|
| $pageTitle = "Dashboard";
| $pageContent = "dashboard-content.php";
| include __DIR__ . "/includes/layout.php";
|
|--------------------------------------------------------------------------
*/

$pageTitle = $pageTitle ?? 'UrbanNest Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?> | UrbanNest Admin</title>


    <!-- =========================================================
         GOOGLE FONT
    ========================================================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =========================================================
         BOOTSTRAP CSS
    ========================================================== -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =========================================================
         FONT AWESOME
    ========================================================== -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =========================================================
         URBANNEST ADMIN CSS
    ========================================================== -->

    <link
        rel="stylesheet"
        href="/urbannest/admin/assets/css/includes.css"
    >

</head>


<body>


    <!-- =========================================================
         MAIN ADMIN WRAPPER
    ========================================================== -->

    <div class="un-wrapper">


        <!-- =====================================================
             SIDEBAR
        ====================================================== -->

        <?php include __DIR__ . '/sidebar.php'; ?>


        <!-- =====================================================
             MAIN AREA
        ====================================================== -->

        <div class="un-main">


            <!-- =================================================
                 TOP HEADER / NAVBAR
            ================================================== -->

            <?php include __DIR__ . '/header.php'; ?>


            <!-- =================================================
                 PAGE CONTENT
            ================================================== -->

            <main class="un-content">

                <?php

                /*
                 * $pageContent should contain the name/path
                 * of the page-specific content file.
                 */

                if (!empty($pageContent)) {

                    include $pageContent;

                } else {

                    echo '<div class="alert alert-warning">
                            No page content specified.
                          </div>';

                }

                ?>

            </main>


            <!-- =================================================
                 FOOTER
            ================================================== -->

            <?php include __DIR__ . '/footer.php'; ?>


        </div>

    </div>


    <!-- =========================================================
         BOOTSTRAP JS
    ========================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


</body>

</html>