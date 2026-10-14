<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections Gallery | Lumora</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Global Rebranded Stylesheet -->
    <link rel="stylesheet" href="/public/css/style.css?v=<?php echo time(); ?>">
    
    <style>
        body {
            background-color: var(--bg-primary) !important;
            color: var(--text-white) !important;
            font-family: var(--font-body);
            padding-top: 85px;
        }

        /* LED Edge-Lit Acrylic Sign Design with thin gold borders and ambient glow [1.3.1] */
        .gallery-banner {
            background: linear-gradient(135deg, rgba(28,28,31,0.95) 0%, rgba(10,10,12,0.98) 100%),
                        radial-gradient(circle at 85% 20%, rgba(212,175,55,0.18) 0%, transparent 55%),
                        repeating-linear-gradient(45deg, rgba(255,255,255,0.01) 0px, rgba(255,255,255,0.01) 2px, transparent 2px, transparent 10px);
            border: 1px solid rgba(212, 175, 55, 0.25); /* Thin gold border */
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.15), 0 15px 35px rgba(0,0,0,0.6); /* LED Edge-lit Glow [1.3.1] */
            border-radius: 12px;
            padding: 5rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .gallery-banner h1 {
            font-family: var(--font-headings);
            font-size: clamp(3rem, 6vw, 4.5rem);
            color: var(--accent-gold);
            letter-spacing: 3px;
            margin: 0;
            text-shadow: 0 4px 15px rgba(0,0,0,0.6);
        }
        
        .gallery-banner .lead-text {
            color: #D1D5DB !important;
            font-size: clamp(0.95rem, 1.5vw, 1.15rem);
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            letter-spacing: 1px;
        }

        /* Premium Motorsports Card Styling */
        .product-card {
            background: linear-gradient(180deg, #1C1C1F 0%, #121214 100%) !important;
            border: 1px solid rgba(212, 175, 55, 0.08) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--accent-gold) !important;
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15) !important;
        }

        /* Studio Image Containment - Forced dark contrast to pop transparent art */
        .card-img-container {
            background-color: #050506 !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.05) !important;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .product-card:hover .card-img-container {
            background-color: #000000 !important;
        }
        .card-img-container img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            transition: transform 0.4s ease;
        }

        .product-card:hover .card-img-container img {
            transform: scale(1.05);
        }

        /* Glassmorphic Absolute Wishlist Button */
        .wishlist-float-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            background: rgba(17, 17, 17, 0.6) !important;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-white);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.25s ease;
        }
        .wishlist-float-btn:hover {
            background-color: var(--accent-gold) !important;
            border-color: var(--accent-gold) !important;
            color: #000000 !important;
            transform: scale(1.1);
        }

        .card-title {
            font-family: var(--font-headings);
            font-size: 1.4rem;
            letter-spacing: 0.5px;
            color: var(--text-white);
            text-transform: uppercase;
        }

        .product-price {
            color: var(--accent-gold);
            font-family: var(--font-headings);
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
        }

        /* Dynamic Crimson Badge shadow */
        .badge.bg-danger {
            background-color: var(--accent-gold) !important;
            color: #000000 !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
            letter-spacing: 0.5px;
            font-weight: bold;
        }

        /* Global style overrides to force standard warning buttons to map to Gold */
        .btn-warning {
            background-color: var(--accent-gold) !important;
            border-color: var(--accent-gold) !important;
            color: #111111 !important; /* Bold high-contrast black text [1.1.2] */
            font-weight: bold !important;
            transition: all 0.25s ease;
        }
        .btn-warning:hover {
            background-color: var(--accent-gold-hover) !important;
            border-color: var(--accent-gold-hover) !important;
        }
    </style>
</head>
<body>

    <!-- Dynamic Header Include [1.1.1] -->
    <?php include APP_PATH . '/Views/layouts/header.php'; ?>

    <div class="container py-5 mt-4">
        <!-- Rebranded Cinematic Banner -->
        <div class="gallery-banner mb-5 text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>THE GALLERY</h1>
                    <p class="lead-text mb-0">LIGHT UP YOUR STORY</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-danger px-3 py-2 text-uppercase">Egypt Delivery Only</span>
                </div>
            </div>
        </div>

        <!-- No Products Found Alert Placeholder -->
        <div id="noProductsAlert" class="text-center py-5 d-none border border-secondary rounded p-4" style="background-color: rgba(212, 175, 55, 0.02); border-color: rgba(212, 175, 55, 0.15) !important;">
            <i class="fa-solid fa-triangle-exclamation text-warning fa-3x mb-3" style="color: var(--accent-gold) !important;"></i>
            <h4 class="text-white">No Artworks Found</h4>
            <p class="text-muted small">We couldn't find any acrylic prints matching that query.</p>
            <button class="btn btn-gold btn-sm mt-3 px-4" onclick="clearLiveSearch()"><i class="fa-solid fa-rotate-left"></i> Reset Filter</button>
        </div>

        <!-- Dynamic full-width 4-column grid (No left bar!) [1.1.1] -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="productsGrid">
            <?php if (count($products) > 0) { 
                foreach ($products as $prod) { 
                    $img = $prod['main_image'] ?: 'placeholder.svg';
                    
                    // Safe array lookup protects against TypeError crashes
                    $is_wishlisted = is_array($wishlist_items) && in_array((int)$prod['id'], $wishlist_items, true);
            ?>
                <div class="col d-flex align-items-stretch product-card-col" data-product-name="<?php echo htmlspecialchars(strtolower($prod['name'])); ?>">
                    <div class="card product-card w-100 d-flex flex-column">
                        <div class="card-img-container">
                            <!-- Absolute Positioned Wishlist Heart Button -->
                            <button type="button" class="wishlist-float-btn" onclick="toggleWishlist(<?php echo $prod['id']; ?>, this)" title="<?php echo $is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                                <?php if ($is_wishlisted) { ?>
                                    <i class="fa-solid fa-heart text-danger"></i>
                                <?php } else { ?>
                                    <i class="fa-regular fa-heart"></i>
                                <?php } ?>
                            </button>
                            
                            <img src="/public/img/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                        </div>
                        <div class="card-body text-center d-flex flex-column p-4">
                            <h5 class="card-title mb-2"><?php echo htmlspecialchars($prod['name']); ?></h5>
                            <h4 class="product-price mb-3"><?php echo number_format((float)$prod['price'], 2); ?> EGP</h4>
                            <div class="mt-auto">
                                <a href="/product?slug=<?php echo htmlspecialchars($prod['slug']); ?>" class="btn btn-gold w-100">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } } else { ?>
                <div class="col-12 text-center py-5 border border-secondary rounded p-4 w-100" style="background-color: rgba(255,255,255,0.01); border-color: rgba(255, 255, 255, 0.05) !important;">
                    <i class="fa-solid fa-triangle-exclamation text-warning fa-3x mb-3" style="color: var(--accent-gold) !important;"></i>
                    <h4 class="text-white">No products matched your parameters.</h4>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Safe dynamic footer include [1.1.1] -->
    <?php 
        if (file_exists(APP_PATH . '/Views/layouts/footer.php')) {
            include APP_PATH . '/Views/layouts/footer.php';
        }
    ?>

    <!-- Bootstrap 5.3 JS Bundle (CRITICAL: THIS ENABLES THE SIDEBAR TO SLIDE OUT ON PRODUCTS!) [1.1.1] -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Live Search bound directly to the header search input elements [1.1.1]
            const headerSearchInput = document.getElementById('headerSearchInput');
            const headerSearchInputMobile = document.getElementById('headerSearchInputMobile');
            const productCols = document.querySelectorAll('.product-card-col');
            const noProductsAlert = document.getElementById('noProductsAlert');

            function filterCatalog(query) {
                query = query.toLowerCase().trim();
                let visibleCount = 0;

                productCols.forEach(col => {
                    const productName = col.dataset.productName;
                    if (productName.includes(query)) {
                        col.classList.remove('d-none');
                        col.classList.add('d-flex');
                        visibleCount++;
                    } else {
                        col.classList.remove('d-flex');
                        col.classList.add('d-none');
                    }
                });

                if (visibleCount === 0 && productCols.length > 0) {
                    noProductsAlert.classList.remove('d-none');
                } else {
                    noProductsAlert.classList.add('d-none');
                }
            }

            if (headerSearchInput) {
                headerSearchInput.addEventListener('input', () => {
                    filterCatalog(headerSearchInput.value);
                });
                if (headerSearchInput.value.length > 0) {
                    filterCatalog(headerSearchInput.value);
                }
            }

            if (headerSearchInputMobile) {
                headerSearchInputMobile.addEventListener('input', () => {
                    filterCatalog(headerSearchInputMobile.value);
                });
            }

            window.clearLiveSearch = function() {
                if (headerSearchInput) headerSearchInput.value = '';
                if (headerSearchInputMobile) headerSearchInputMobile.value = '';
                filterCatalog('');
            }
        });

        function toggleWishlist(productId, button) {
            const csrfToken = '<?php echo \App\Core\Session::get('csrf_token'); ?>';

            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `product_id=${productId}`
            })
            .then(response => {
                if (response.status === 401 || response.redirected) {
                    window.location.href = '/login';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    const icon = button.querySelector('i');
                    if (data.is_wishlisted) {
                        icon.className = 'fa-solid fa-heart text-danger';
                    } else {
                        icon.className = 'fa-regular fa-heart';
                    }
                }
            });
        }
    </script>
</body>
</html>
