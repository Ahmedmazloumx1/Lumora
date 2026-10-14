<?php
    // Ensure session is started.
    if(session_status() == PHP_SESSION_NONE){ 
        session_start(); 
    }
?>
<!-- Modern Header with Offcanvas Sidebar -->
<nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: #111111 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; height: 70px;">
    <div class="container-fluid"> 

        <!-- Sidebar Toggle -->
        <button class="btn btn-link text-white me-2 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" aria-controls="mainSidebar" aria-label="Open Navigation Menu">
            <i class="fa fa-bars fa-lg" style="color: #FFFFFF !important;"></i>
        </button>

        <!-- Brand Logo/Text - Custom vector SVG replica of your Lumora crescent moon logo [1.3.1] -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <?php $siteLogo = \App\Helpers\SettingsHelper::get('site_logo'); if ($siteLogo) { ?>
                <img src="/public/img/<?php echo htmlspecialchars(basename($siteLogo)); ?>" alt="Lumora" class="me-2" style="width:42px;height:42px;object-fit:contain;">
            <?php } else { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 42px; height: 42px; filter: drop-shadow(0 0 5px rgba(212, 175, 55, 0.6));" class="me-2">
                <!-- Thin Gold Crescent Ring -->
                <path d="M 25,50 A 25,25 0 1,1 75,50 A 24,24 0 1,0 25,50" fill="#D4AF37" />
                <!-- Serif L -->
                <text x="35" y="62" font-family="'Times New Roman', serif" font-size="40" fill="#D4AF37" font-weight="normal">L</text>
                <!-- Sparkle Star -->
                <path d="M 68,52 Q 68,56 72,56 Q 68,56 68,60 Q 68,56 64,56 Q 68,56 68,52 Z" fill="#FFFFFF" />
            </svg>
            <?php } ?>
            <span class="brand-text" style="letter-spacing: 2px !important; color: #FFFFFF !important; font-family: 'Bebas Neue', sans-serif; font-size: 1.7rem; line-height: 1;">LUMORA</span>
        </a>

        <!-- Global Search Bar (Desktop only) -->
        <form action="/products" method="GET" class="d-none d-md-flex align-items-center ms-3 me-auto" style="max-width: 280px; width: 100%;">
            <div class="input-group input-group-sm">
                <input 
                    type="text" 
                    name="search" 
                    id="headerSearchInput"
                    class="form-control text-white" 
                    placeholder="Search catalog..." 
                    style="background-color: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.1); border-radius: 4px 0 0 4px;"
                    value="<?php echo is_string($_GET['search'] ?? null) ? htmlspecialchars($_GET['search']) : ''; ?>"
                >
                <button class="btn px-3" type="submit" style="background-color: #D4AF37 !important; border-color: #D4AF37 !important; color: #111111 !important; border-radius: 0 4px 4px 0;" aria-label="Search">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Top Right Actions Area -->
        <div class="d-flex align-items-center ms-auto">
            <?php if(\App\Core\Session::get('user_id')){ ?>
                <!-- Welcoming Active Client Session -->
                <span class="text-white-50 small me-3 d-none d-lg-inline">
                   Welcome, <span class="fw-bold" style="color: #D4AF37 !important;"><?php echo htmlspecialchars((string)\App\Core\Session::get('user_name')); ?></span>
                </span>
                
                <!-- Wishlist Quick Redirection Icon -->
                <a href="/wishlist" class="btn btn-link text-white me-3 p-0" aria-label="View Saved Items" title="My Wishlist">
                    <i class="fa-regular fa-heart fa-lg text-white-50"></i>
                </a>

                <!-- Shopping Cart link -->
                <a href="/cart" class="btn btn-link text-white me-3 p-0 position-relative" aria-label="View Shopping Cart">
                    <i class="fa fa-shopping-cart fa-lg"></i>
                </a>
                
                <form action="/logout" method="post" class="d-inline"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>"><button type="submit" class="btn btn-outline-light btn-sm rounded px-3 fw-bold">Logout</button></form>
            <?php } else { ?>
                <a href="/register" class="btn btn-link text-white me-3 small fw-bold d-none d-md-inline-block" style="text-decoration: none !important;">Register</a>
                <a href="/login" class="btn btn-sm rounded px-4 fw-bold shadow-sm" style="background-color: #D4AF37 !important; border-color: #D4AF37 !important; color: #111111 !important;">Login</a>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Rebranded Sidebar Menu (Offcanvas Sidebar) -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mainSidebar" style="width: 280px;" aria-labelledby="mainSidebarLabel">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title fw-bold text-white d-flex align-items-center" id="mainSidebarLabel" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 1px;">
            <?php if ($siteLogo) { ?>
                <img src="/public/img/<?php echo htmlspecialchars(basename($siteLogo)); ?>" alt="" class="me-2" style="width:32px;height:32px;object-fit:contain;">
            <?php } else { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 32px; height: 32px; filter: drop-shadow(0 0 3px rgba(212, 175, 55, 0.45));" class="me-2">
                <path d="M 25,50 A 25,25 0 1,1 75,50 A 24,24 0 1,0 25,50" fill="#D4AF37" />
                <text x="35" y="62" font-family="'Times New Roman', serif" font-size="40" fill="#D4AF37" font-weight="normal">L</text>
                <path d="M 68,52 Q 68,56 72,56 Q 68,56 68,60 Q 68,56 64,56 Q 68,56 68,52 Z" fill="#FFFFFF" />
            </svg>
            <?php } ?>
            LUMORA
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        <ul class="nav flex-column gap-1">
            <!-- Mobile Navigation Search Area -->
            <li class="px-3 py-2 d-md-none">
                <form action="/products" method="GET">
                    <div class="input-group input-group-sm">
                        <input 
                            type="text" 
                            name="search" 
                            id="headerSearchInputMobile"
                            class="form-control text-white" 
                            placeholder="Search catalog..." 
                            style="background-color: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.1);"
                            value="<?php echo is_string($_GET['search'] ?? null) ? htmlspecialchars($_GET['search']) : ''; ?>"
                        >
                        <button class="btn" type="submit" style="background-color: #D4AF37 !important; border-color: #D4AF37 !important; color: #111111 !important;" aria-label="Search"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </li>

            <li>
                <a href="/" class="nav-link text-white p-3">
                    <i class="fa fa-home me-2"></i> Home
                </a>
            </li>
            <li>
                <a href="/products" class="nav-link text-white p-3">
                    <i class="fa fa-store me-2"></i> Shop All Artwork
                </a>
            </li>
            
            <!-- Category Collection Section Headers -->
            <li class="mt-2 px-3 py-2 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.75rem; letter-spacing: 1px;">
                Collections
            </li>
            <?php
                try {
                    $db = \App\Core\Database::getConnection();
                    $stmt = $db->query("SELECT * FROM categories ORDER BY id ASC");
                    while ($cat_row = $stmt->fetch()) {
            ?>
                    <li>
                        <a href="/products?category=<?php echo urlencode($cat_row['slug']); ?>" class="nav-link text-white p-3 py-2 fs-6">
                            <i class="fa-solid fa-car-side me-2"></i> <?php echo htmlspecialchars($cat_row['name']); ?>
                        </a>
                    </li>
            <?php 
                    }
                } catch (\Exception $e) {
                    error_log("Header category retrieval failed: " . $e->getMessage());
                }
            ?>

            <!-- Client Area Sections -->
            <li class="mt-2 px-3 py-2 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.75rem; letter-spacing: 1px;">
                Client Center
            </li>
            <?php if(\App\Core\Session::get('user_id')){ ?>
                <li>
                    <a href="/wishlist" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa-solid fa-heart me-2"></i> My Wishlist
                    </a>
                </li>
                <li>
                    <a href="/custom-order/status" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Bespoke Requests
                    </a>
                </li>
                <li>
                    <a href="/cart" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa fa-shopping-cart me-2"></i> My Cart
                    </a>
                </li>
                <li>
                    <a href="/account" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li>
                    <form action="/logout" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                        <button type="submit" class="btn nav-link text-white p-3 py-2 fs-6"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</button>
                    </form>
                </li>
            <?php } else { ?>
                <li>
                    <a href="/login" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa-solid fa-sign-in-alt me-2"></i> Login
                    </a>
                </li>
                <li>
                    <a href="/register" class="nav-link text-white p-3 py-2 fs-6">
                        <i class="fa-solid fa-user-plus me-2"></i> Register
                    </a>
                </li>
            <?php } ?>
        </ul>

        <!-- Admin Shortcut -->
        <div class="pt-3 border-top border-secondary">
            <a href="/admin" class="nav-link text-danger small px-3">
                <i class="fa-solid fa-user-shield me-2"></i> Admin Control Panel
            </a>
        </div>
    </div>
</div>
