<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Lumora</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Global Rebranded Stylesheet -->
    <link rel="stylesheet" href="/public/css/style.css?v=<?php echo time(); ?>">
    
    <style>
        :root {
            --bg-primary: #111111;
            --bg-secondary: #1C1C1F;
            --accent-gold: #D4AF37;
            --accent-gold-hover: #AA820A;
            --text-white: #FFFFFF;
            --text-gray: #8B8B8B;
            --font-headings: 'Bebas Neue', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-white);
            font-family: var(--font-body);
            padding-top: 70px;
        }

        .img-showcase {
            background-color: #0b0b0c;
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .img-showcase img {
            max-height: 400px;
            object-fit: contain;
        }

        .product-title {
            font-family: var(--font-headings);
            font-size: clamp(2.5rem, 5vw, 4rem);
            letter-spacing: 1.5px;
            color: var(--text-white);
        }

        .price-display {
            font-family: var(--font-headings);
            font-size: 2.5rem;
            color: var(--accent-gold);
            letter-spacing: 0.5px;
        }

        .spec-pill {
            background-color: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .spec-pill strong {
            color: var(--text-white);
        }

        .form-select-gold {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            padding: 0.75rem 1rem !important;
            border-radius: 6px !important;
        }

        .form-select-gold:focus {
            border-color: var(--accent-gold) !important;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25) !important;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
        }

        .product-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.35s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-gold);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.15);
        }

        .card-img-container {
            background-color: #0b0b0c;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .card-img-container img {
            max-height: 140px;
            max-width: 80%;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <!-- Include layout header via path-safe MVC configuration [1.1.1] -->
    <?php include APP_PATH . '/Views/layouts/header.php'; ?>

    <div class="container py-5 mt-4">
        <div class="row g-5">
            <!-- Left Column: Gallery Images -->
            <div class="col-lg-6">
                <div class="img-showcase mb-3">
                    <?php $main_img = $product['main_image'] ?: 'placeholder.svg'; ?>
                    <img id="mainImage" src="/public/img/<?php echo htmlspecialchars($main_img); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
                </div>
            </div>

            <!-- Right Column: Specs and Checkout Actions -->
            <div class="col-lg-6">
                <span class="text-warning small fw-bold text-uppercase" style="color: var(--accent-gold) !important;"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <h1 class="product-title fw-bold mt-1 mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <!-- Pricing Display -->
                <div class="price-display mb-4">
                    <span id="priceValue"><?php echo number_format((float)$product['price'], 2); ?></span> EGP
                </div>

                <p class="text-muted small mb-4">
                    <?php echo htmlspecialchars($product['description'] ?? 'Indulge in uncompromised artistic detail. Sturdy, ultra-gloss, and shatterproof optical acrylic plates designed to introduce dimensional depth and high-end aesthetics into any contemporary room decor.'); ?>
                </p>

                <!-- Technical Specification Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="spec-pill">
                            <i class="fa-solid fa-layer-group text-warning me-2" style="color: var(--accent-gold) !important;"></i> Material: <strong>Optical Cast Acrylic</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="spec-pill">
                            <i class="fa-solid fa-arrows-up-down text-warning me-2" style="color: var(--accent-gold) !important;"></i> Thickness: <strong>3mm (Exhibition Grade)</strong>
                        </div>
                    </div>
                </div>

                <!-- Interactive Variant Selector Form -->
                <?php if (!empty($product['variants'])) { ?>
                    <div class="mb-4">
                        <label class="form-label mb-2">SELECT VARIATION (SIZE / FINISH)</label>
                        <select class="form-select form-select-gold" id="variantSelector" onchange="calculateVariantPrice()">
                            <?php foreach ($product['variants'] as $var) { ?>
                                <option value="<?php echo htmlspecialchars((string)$var['variant_id']); ?>" data-price="<?php echo $var['price']; ?>">
                                    <?php echo htmlspecialchars($var['attribute_values'] ?? $var['sku'] ?? 'Standard'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>

                <div class="d-flex gap-3">
                    <form method="POST" action="/cart/add" class="flex-grow-1">
                        <!-- CSRF security token verification [1.1.2] -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="<?php echo $product['variants'][0]['variant_id'] ?? ''; ?>">
                        <button type="submit" class="btn btn-gold w-100">
                            <i class="fa-solid fa-cart-plus me-2"></i> ADD TO BASKET
                        </button>
                    </form>

                    <!-- AJAX-driven wishlist toggle button [1.1.2] -->
                    <button type="button" class="btn btn-outline-light px-3" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" aria-label="Add to wishlist">
                        <i class="fa-regular fa-heart fs-5"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Products recommendation engine grid [1.1.1] -->
        <?php if (count($related_products) > 0) { ?>
            <div class="container py-5 border-top border-secondary mt-5 px-0">
                <h3 class="fw-bold mb-4 text-uppercase" style="font-family: var(--font-headings); color: var(--accent-gold); letter-spacing: 1px;">
                    Related Artworks
                </h3>
                <div class="row g-4">
                    <?php foreach ($related_products as $rel) { 
                        $rel_img = !empty($rel['main_image']) ? $rel['main_image'] : 'placeholder.svg';
                    ?>
                        <div class="col-lg-3 col-sm-6 d-flex align-items-stretch">
                            <div class="card product-card w-100 d-flex flex-column">
                                <div class="card-img-container">
                                    <img src="/public/img/<?php echo htmlspecialchars($rel_img); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>">
                                </div>
                                <div class="card-body text-center d-flex flex-column p-4">
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($rel['name']); ?></h5>
                                    <h4 class="product-price mb-3"><?php echo number_format((float)$rel['price'], 2); ?> EGP</h4>
                                    <div class="mt-auto">
                                        <a href="/product?slug=<?php echo htmlspecialchars($rel['slug']); ?>" class="btn btn-gold btn-sm w-100 py-2">VIEW DETAILS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Include layout footer via path-safe MVC configuration [1.1.1] -->
    <?php include APP_PATH . '/Views/layouts/footer.php'; ?>

    <!-- Interactive Price Update Calculations Script -->
    <script>
        function calculateVariantPrice() {
            const selector = document.getElementById('variantSelector');
            if (!selector) return;
            
            const selectedOption = selector.options[selector.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const variantId = selectedOption.value;

            // Update displayed price in real-time
            document.getElementById('priceValue').innerText = price.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Set hidden field value for cart submission
            document.getElementById('selectedVariantId').value = variantId;
        }

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
                        icon.className = 'fa-solid fa-heart fs-5 text-danger';
                    } else {
                        icon.className = 'fa-regular fa-heart fs-5 text-white';
                    }
                }
            });
        }
        
        // Initial Calculation Run on Page Load
        document.addEventListener('DOMContentLoaded', calculateVariantPrice);
    </script>

</body>
</html>

