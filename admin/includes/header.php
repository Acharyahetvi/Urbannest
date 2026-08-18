 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


 </head>
 <body>
     <!-- ---------- TOP NAVBAR ---------- -->
 <header class="un-topbar">

     <div class="un-topbar-left">
         <!-- Sidebar toggle — visible on tablet/mobile only -->
         <button class="un-burger d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#unSidebarOffcanvas" aria-controls="unSidebarOffcanvas" aria-label="Toggle sidebar">
             <i class="fa-solid fa-bars"></i>
         </button>
         <div class="un-topbar-heading">
             <span class="un-eyebrow d-none d-md-inline">Overview</span>
             <h1 class="un-page-title">Dashboard</h1>
         </div>
     </div>

     <div class="un-topbar-center d-none d-md-flex">
         <div class="un-search">
             <i class="fa-solid fa-magnifying-glass"></i>
             <input type="text" class="un-search-input" placeholder="Search properties, users, inquiries…" aria-label="Search">
         </div>
     </div>

     <div class="un-topbar-right">

         <!-- Mobile search trigger -->
         <button class="un-icon-btn d-md-none" type="button" aria-label="Search">
             <i class="fa-solid fa-magnifying-glass"></i>
         </button>

         <!-- Notifications -->
         <div class="dropdown">
             <button class="un-icon-btn" type="button" id="unNotifDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                 <i class="fa-regular fa-bell"></i>
                 <span class="un-badge-dot">3</span>
             </button>
             <ul class="dropdown-menu dropdown-menu-end un-dropdown-menu" aria-labelledby="unNotifDropdown">
                 <li><span class="un-dropdown-header">Notifications</span></li>
                 <li>
                     <a class="dropdown-item un-dropdown-item" href="#">
                         <i class="fa-solid fa-house-circle-check un-dd-icon"></i>
                         <span>New property submitted for review</span>
                     </a>
                 </li>
                 <li>
                     <a class="dropdown-item un-dropdown-item" href="#">
                         <i class="fa-solid fa-envelope-open-text un-dd-icon"></i>
                         <span>New inquiry on Palm Grove Villa</span>
                     </a>
                 </li>
                 <li>
                     <a class="dropdown-item un-dropdown-item" href="#">
                         <i class="fa-solid fa-user-plus un-dd-icon"></i>
                         <span>New user registration</span>
                     </a>
                 </li>
                 <li>
                     <hr class="dropdown-divider">
                 </li>
                 <li><a class="dropdown-item un-dropdown-item un-dropdown-item--muted text-center" href="#">View all notifications</a></li>
             </ul>
         </div>

         <!-- Admin Profile -->
         <div class="dropdown">
             <button class="un-profile-btn" type="button" id="unProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                 <img src="https://ui-avatars.com/api/?name=Admin+User&amp;background=14284D&amp;color=E4CE8C&amp;bold=true" alt="Admin avatar" class="un-avatar">
                 <span class="un-profile-info d-none d-md-flex">
                     <span class="un-profile-name">Admin User</span>
                     <span class="un-profile-role">Super Admin</span>
                 </span>
                 <i class="fa-solid fa-chevron-down un-profile-caret d-none d-md-inline"></i>
             </button>
             <ul class="dropdown-menu dropdown-menu-end un-dropdown-menu" aria-labelledby="unProfileDropdown">
                 <li><a class="dropdown-item un-dropdown-item" href="#"><i class="fa-regular fa-user un-dd-icon"></i><span>My Profile</span></a></li>
                 <li><a class="dropdown-item un-dropdown-item" href="#"><i class="fa-solid fa-gear un-dd-icon"></i><span>Account Settings</span></a></li>
                 <li>
                     <hr class="dropdown-divider">
                 </li>
                 <li><a class="dropdown-item un-dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket un-dd-icon"></i><span>Logout</span></a></li>
             </ul>
         </div>
     </div>
 </header>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
 </body>
 </html>
 
