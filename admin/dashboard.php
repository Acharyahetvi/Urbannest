<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanNest — Admin Dashboard</title>
    <?php include("./includes/links.php"); ?>
</head>

<body>

    <div class="un-wrapper">

        <?php include("./includes/sidebar.php"); ?>
        <!-- ============================================================
       MAIN CONTENT
       ============================================================= -->
        <div class="un-main">
            <?php include("./includes/header.php"); ?>
            <!-- ---------- PAGE CONTENT ---------- -->
            <main class="un-content">

                <!-- ===== Statistic Cards ===== -->
                <section class="row g-3 g-lg-4">

                    <div class="col-6 col-xl-3">
                        <div class="un-card un-stat-card">
                            <div class="un-stat-icon un-stat-icon--navy">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div class="un-stat-body">
                                <span class="un-stat-value">248</span>
                                <span class="un-stat-label">Total Properties</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="un-card un-stat-card">
                            <div class="un-stat-icon un-stat-icon--gold">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="un-stat-body">
                                <span class="un-stat-value">1,204</span>
                                <span class="un-stat-label">Active Users</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="un-card un-stat-card">
                            <div class="un-stat-icon un-stat-icon--navy">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="un-stat-body">
                                <span class="un-stat-value">12</span>
                                <span class="un-stat-label">Categories</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="un-card un-stat-card">
                            <div class="un-stat-icon un-stat-icon--gold">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div class="un-stat-body">
                                <span class="un-stat-value">18</span>
                                <span class="un-stat-label">Pending Properties</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ===== Recent Properties + Quick Actions ===== -->
                <section class="row g-3 g-lg-4 mt-0">

                    <!-- Recent Properties Table -->
                    <div class="col-12 col-lg-8">
                        <div class="un-card un-panel">
                            <div class="un-panel-header">
                                <div>
                                    <span class="un-eyebrow">Latest Listings</span>
                                    <h2 class="un-panel-title">Recent Properties</h2>
                                </div>
                                <a href="#" class="un-link-btn">View All <i class="fa-solid fa-arrow-right"></i></a>
                            </div>

                            <div class="table-responsive un-table-wrap">
                                <table class="table un-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Category</th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="un-property-cell">
                                                    <img src="https://placehold.co/64x64/0E1F3D/E4CE8C?text=PGV" alt="Palm Grove Villa" class="un-property-thumb">
                                                    <div>
                                                        <span class="un-property-name">Palm Grove Villa</span>
                                                        <span class="un-property-loc"><i class="fa-solid fa-location-dot"></i> Alibaug, Maharashtra</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Villa</td>
                                            <td>Rajesh Mehta</td>
                                            <td><span class="un-status un-status--active">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="un-property-cell">
                                                    <img src="https://placehold.co/64x64/14284D/E4CE8C?text=SR" alt="Skyline Residency" class="un-property-thumb">
                                                    <div>
                                                        <span class="un-property-name">Skyline Residency, Apt 402</span>
                                                        <span class="un-property-loc"><i class="fa-solid fa-location-dot"></i> Andheri West, Mumbai</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Apartment</td>
                                            <td>Ananya Kapoor</td>
                                            <td><span class="un-status un-status--pending">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="un-property-cell">
                                                    <img src="https://placehold.co/64x64/0E1F3D/E4CE8C?text=EBT" alt="Emerald Business Tower" class="un-property-thumb">
                                                    <div>
                                                        <span class="un-property-name">Emerald Business Tower</span>
                                                        <span class="un-property-loc"><i class="fa-solid fa-location-dot"></i> Sector 62, Noida</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Commercial</td>
                                            <td>Vikram Suri</td>
                                            <td><span class="un-status un-status--active">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="un-property-cell">
                                                    <img src="https://placehold.co/64x64/14284D/E4CE8C?text=SF" alt="Sunset Farmhouse" class="un-property-thumb">
                                                    <div>
                                                        <span class="un-property-name">Sunset Farmhouse</span>
                                                        <span class="un-property-loc"><i class="fa-solid fa-location-dot"></i> Lonavala, Maharashtra</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Farmhouse</td>
                                            <td>Meera Nair</td>
                                            <td><span class="un-status un-status--sold">Sold</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="un-property-cell">
                                                    <img src="https://placehold.co/64x64/0E1F3D/E4CE8C?text=LB" alt="Lakeview Bungalow" class="un-property-thumb">
                                                    <div>
                                                        <span class="un-property-name">Lakeview Bungalow</span>
                                                        <span class="un-property-loc"><i class="fa-solid fa-location-dot"></i> Powai, Mumbai</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Bungalow</td>
                                            <td>Arjun Deshmukh</td>
                                            <td><span class="un-status un-status--pending">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-12 col-lg-4">
                        <div class="un-card un-panel h-100">
                            <div class="un-panel-header">
                                <div>
                                    <span class="un-eyebrow">Shortcuts</span>
                                    <h2 class="un-panel-title">Quick Actions</h2>
                                </div>
                            </div>

                            <div class="un-quick-actions">
                                <a href="#" class="un-action-btn">
                                    <span class="un-action-icon"><i class="fa-solid fa-plus"></i></span>
                                    <span class="un-action-label">Add Property</span>
                                </a>
                                <a href="#" class="un-action-btn">
                                    <span class="un-action-icon"><i class="fa-solid fa-folder-plus"></i></span>
                                    <span class="un-action-label">Add Category</span>
                                </a>
                                <a href="#" class="un-action-btn">
                                    <span class="un-action-icon"><i class="fa-solid fa-user-plus"></i></span>
                                    <span class="un-action-label">Add User</span>
                                </a>
                                <a href="#" class="un-action-btn">
                                    <span class="un-action-icon"><i class="fa-solid fa-chart-simple"></i></span>
                                    <span class="un-action-label">View Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ===== Latest Users + Recent Inquiries + Recent Activity ===== -->
                <section class="row g-3 g-lg-4 mt-0">

                    <!-- Latest Users -->
                    <div class="col-12 col-lg-4">
                        <div class="un-card un-panel h-100">
                            <div class="un-panel-header">
                                <div>
                                    <span class="un-eyebrow">Community</span>
                                    <h2 class="un-panel-title">Latest Users</h2>
                                </div>
                            </div>

                            <ul class="un-user-list">
                                <li class="un-user-item">
                                    <img src="https://ui-avatars.com/api/?name=Priya+Sharma&amp;background=E4CE8C&amp;color=0E1F3D&amp;bold=true" alt="Priya Sharma" class="un-avatar">
                                    <div class="un-user-info">
                                        <span class="un-user-name">Priya Sharma</span>
                                        <span class="un-user-meta">Joined 2 days ago</span>
                                    </div>
                                    <span class="un-status un-status--active un-status--sm">Active</span>
                                </li>
                                <li class="un-user-item">
                                    <img src="https://ui-avatars.com/api/?name=Karan+Malhotra&amp;background=E4CE8C&amp;color=0E1F3D&amp;bold=true" alt="Karan Malhotra" class="un-avatar">
                                    <div class="un-user-info">
                                        <span class="un-user-name">Karan Malhotra</span>
                                        <span class="un-user-meta">Joined 3 days ago</span>
                                    </div>
                                    <span class="un-status un-status--active un-status--sm">Active</span>
                                </li>
                                <li class="un-user-item">
                                    <img src="https://ui-avatars.com/api/?name=Fatima+Sheikh&amp;background=E4CE8C&amp;color=0E1F3D&amp;bold=true" alt="Fatima Sheikh" class="un-avatar">
                                    <div class="un-user-info">
                                        <span class="un-user-name">Fatima Sheikh</span>
                                        <span class="un-user-meta">Joined 5 days ago</span>
                                    </div>
                                    <span class="un-status un-status--pending un-status--sm">Pending</span>
                                </li>
                                <li class="un-user-item">
                                    <img src="https://ui-avatars.com/api/?name=Rohan+Gupta&amp;background=E4CE8C&amp;color=0E1F3D&amp;bold=true" alt="Rohan Gupta" class="un-avatar">
                                    <div class="un-user-info">
                                        <span class="un-user-name">Rohan Gupta</span>
                                        <span class="un-user-meta">Joined 1 week ago</span>
                                    </div>
                                    <span class="un-status un-status--active un-status--sm">Active</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Inquiries -->
                    <div class="col-12 col-lg-4">
                        <div class="un-card un-panel h-100">
                            <div class="un-panel-header">
                                <div>
                                    <span class="un-eyebrow">Client Interest</span>
                                    <h2 class="un-panel-title">Recent Inquiries</h2>
                                </div>
                            </div>

                            <ul class="un-inquiry-list">
                                <li class="un-inquiry-item">
                                    <div class="un-inquiry-top">
                                        <span class="un-inquiry-name"><i class="fa-regular fa-user"></i> Neha Verma</span>
                                        <span class="un-inquiry-date">Aug 4, 2026</span>
                                    </div>
                                    <span class="un-inquiry-property"><i class="fa-solid fa-house"></i> Palm Grove Villa</span>
                                </li>
                                <li class="un-inquiry-item">
                                    <div class="un-inquiry-top">
                                        <span class="un-inquiry-name"><i class="fa-regular fa-user"></i> Aditya Rao</span>
                                        <span class="un-inquiry-date">Aug 3, 2026</span>
                                    </div>
                                    <span class="un-inquiry-property"><i class="fa-solid fa-house"></i> Skyline Residency, Apt 402</span>
                                </li>
                                <li class="un-inquiry-item">
                                    <div class="un-inquiry-top">
                                        <span class="un-inquiry-name"><i class="fa-regular fa-user"></i> Sana Iqbal</span>
                                        <span class="un-inquiry-date">Aug 2, 2026</span>
                                    </div>
                                    <span class="un-inquiry-property"><i class="fa-solid fa-house"></i> Emerald Business Tower</span>
                                </li>
                                <li class="un-inquiry-item">
                                    <div class="un-inquiry-top">
                                        <span class="un-inquiry-name"><i class="fa-regular fa-user"></i> Imran Qureshi</span>
                                        <span class="un-inquiry-date">Aug 1, 2026</span>
                                    </div>
                                    <span class="un-inquiry-property"><i class="fa-solid fa-house"></i> Lakeview Bungalow</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="col-12 col-lg-4">
                        <div class="un-card un-panel h-100">
                            <div class="un-panel-header">
                                <div>
                                    <span class="un-eyebrow">Timeline</span>
                                    <h2 class="un-panel-title">Recent Activity</h2>
                                </div>
                            </div>

                            <ul class="un-timeline">
                                <li class="un-timeline-item">
                                    <span class="un-timeline-dot"><i class="fa-solid fa-house-circle-check"></i></span>
                                    <div class="un-timeline-body">
                                        <span class="un-timeline-text"><strong>Palm Grove Villa</strong> was added to Properties</span>
                                        <span class="un-timeline-time">10 minutes ago</span>
                                    </div>
                                </li>
                                <li class="un-timeline-item">
                                    <span class="un-timeline-dot"><i class="fa-solid fa-layer-group"></i></span>
                                    <div class="un-timeline-body">
                                        <span class="un-timeline-text">Category <strong>Commercial</strong> was updated</span>
                                        <span class="un-timeline-time">1 hour ago</span>
                                    </div>
                                </li>
                                <li class="un-timeline-item">
                                    <span class="un-timeline-dot"><i class="fa-solid fa-user-plus"></i></span>
                                    <div class="un-timeline-body">
                                        <span class="un-timeline-text"><strong>Priya Sharma</strong> registered as a new user</span>
                                        <span class="un-timeline-time">2 hours ago</span>
                                    </div>
                                </li>
                                <li class="un-timeline-item">
                                    <span class="un-timeline-dot"><i class="fa-solid fa-envelope-open-text"></i></span>
                                    <div class="un-timeline-body">
                                        <span class="un-timeline-text">New inquiry received for <strong>Skyline Residency</strong></span>
                                        <span class="un-timeline-time">3 hours ago</span>
                                    </div>
                                </li>
                                <li class="un-timeline-item un-timeline-item--last">
                                    <span class="un-timeline-dot"><i class="fa-solid fa-hourglass-half"></i></span>
                                    <div class="un-timeline-body">
                                        <span class="un-timeline-text"><strong>Lakeview Bungalow</strong> marked as pending</span>
                                        <span class="un-timeline-time">5 hours ago</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <?php include("./includes/footer.php"); ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle (required only for built-in offcanvas / dropdown components — no custom JS written) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>