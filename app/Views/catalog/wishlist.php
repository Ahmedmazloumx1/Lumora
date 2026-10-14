<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Saved Designs | Lumora</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg-primary: #111111;
            --bg-secondary: #1C1C1F;
            --accent-gold: #D4AF37;
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

        .wish-wrapper {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .table {
            background-color: transparent !important;
            color: var(--text-white) !important;
        }

        .table-dark th {
            font-family: var(--font-headings);
            letter-spacing: 0.5px;
            font-size: 1.1rem;
            color: var(--text-white);
            background-color: #0b0b0c !important;
            padding: 15px 20px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.2) !important;
        }

        .table td {
            background-color: var(--bg-secondary) !important;
            color: var(--text-gray) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            padding: 20px;
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
            color: #000000;
        }

        .btn-remove {
            color: #ef4444;
            transition: color 0.2s ease;
        }

        .btn-remove:hover {
            color: #dc2626;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: #111111; border-bottom: 1px solid rgba(255, 255, 255, 0.05); height: 70px;">
            <div class="container-fluid d-flex justify-content-between">
                <a class="navbar-brand fw-bold text-white ms-3" href="/">
                    <i class="fa-solid fa-gem me-2" style="color: var(--accent-gold);"></i>LUMORA
                </a>
                <a href="/products" class="btn btn-outline-light btn-sm fw-bold">Back to Store</a>
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-5">
        <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">
            <i class="fa-solid fa-heart me-2" style="color: var(--accent-gold);"></i>MY SAVED DESIGNS
        </h2>

        <div class="wish-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Product Detail</th>
                        <th>Price</th>
                        <th class="text-center">Action Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (count($items) > 0) { 
                        foreach ($items as $item) { 
                            $img = !empty($item['main_image']) ? $item['main_image'] : 'placeholder.svg';
                    ?>
                    <tr id="wish-<?php echo $item['product_id']; ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="/public/img/<?php echo htmlspecialchars($img); ?>" style="width: 50px; height: 50px; object-fit: contain;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div>
                                    <strong class="text-white"><?php echo htmlspecialchars($item['name']); ?></strong>
                                </div>
                            </div>
                        </td>
                        <td class="text-white"><?php echo number_format((float)$item['price'], 2); ?> EGP</td>
                        <td class="text-center">
                            <a href="/product?slug=<?php echo htmlspecialchars($item['slug']); ?>" class="btn btn-gold me-2">VIEW SPECIFICATIONS</a>
                            <button type="button" class="btn btn-link btn-remove" onclick="removeFavorite(<?php echo $item['product_id']; ?>)" aria-label="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } } else { ?>
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <h4 class="text-white">You have no saved designs yet.</h4>
                            <a href="/products" class="btn btn-gold mt-3 px-5">Continue Browsing</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function removeFavorite(productId) {
            const csrfToken = '<?php echo \App\Core\Session::get('csrf_token'); ?>';

            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && !data.is_wishlisted) {
                    const row = document.getElementById(`wish-${productId}`);
                    row.remove();
                    
                    // Reload if all items deleted to show empty state
                    if (document.querySelectorAll('tbody tr[id^="wish-"]').length === 0) {
                        location.reload();
                    }
                }
            });
        }
    </script>
</body>
</html>

