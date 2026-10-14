<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Basket | Lumora</title>
    
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

        .cart-wrapper {
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
            font-family: var(--font-heading);
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

        .qty-input-box {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            width: 70px;
            text-align: center;
            border-radius: 4px;
            padding: 0.35rem;
        }

        .price-text {
            color: var(--text-white);
        }

        .subtotal-text {
            color: var(--accent-gold);
            font-family: var(--font-headings);
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.75rem 2.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
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
            <i class="fa fa-shopping-cart me-2" style="color: var(--accent-gold);"></i>YOUR BASKET
        </h2>

        <div class="cart-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0.00;
                    if (count($items) > 0) { 
                        foreach ($items as $item) { 
                            $sub = (float)$item['unit_price'] * (int)$item['quantity'];
                            $total += $sub;
                            $img = !empty($item['main_image']) ? $item['main_image'] : 'placeholder.svg';
                    ?>
                    <tr id="row-<?php echo $item['item_id']; ?>" data-price="<?php echo $item['unit_price']; ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="/public/img/<?php echo htmlspecialchars($img); ?>" style="width: 50px; height: 50px; object-fit: contain;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div>
                                    <strong class="text-white"><?php echo htmlspecialchars($item['name']); ?></strong>
                                    <?php if (!empty($item['variant_details'])) { ?>
                                        <div class="small text-muted mt-1"><?php echo htmlspecialchars($item['variant_details']); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                        <td class="price-text"><?php echo number_format((float)$item['unit_price'], 2); ?> EGP</td>
                        <td>
                            <form method="POST" action="/cart/update" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <input type="number" name="quantity" aria-label="Quantity for <?php echo htmlspecialchars($item['name']); ?>" class="qty-input-box" value="<?php echo $item['quantity']; ?>" min="1" max="999" onchange="updateCartQuantity(<?php echo $item['item_id']; ?>, this.value)">
                                <button type="submit" class="btn btn-outline-light btn-sm">Update</button>
                            </form>
                        </td>
                        <td class="subtotal-text" id="subtotal-<?php echo $item['item_id']; ?>"><?php echo number_format($sub, 2); ?> EGP</td>
                        <td class="text-center">
                            <form method="POST" action="/cart/remove">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" class="btn btn-link btn-remove" aria-label="Remove item">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <tr class="table-dark border-top">
                        <td colspan="3" class="text-end fw-bold pe-4" style="font-size: 1.5rem; font-family: var(--font-headings); letter-spacing: 0.5px;">Total Invoice:</td>
                        <td class="subtotal-text fs-4" id="grandTotal"><?php echo number_format($total, 2); ?> EGP</td>
                        <td class="text-center">
                            <a href="/checkout" class="btn btn-gold">CHECKOUT</a>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <h4 class="text-white">Your basket is empty!</h4>
                            <a href="/products" class="btn btn-gold mt-3 px-5">Continue Shopping</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live AJAX Quantity Updates -->
    <script>
        function updateCartQuantity(itemId, quantity) {
            if (quantity < 1) return;

            const csrfToken = '<?php echo \App\Core\Session::get('csrf_token'); ?>';

            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `item_id=${itemId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`row-${itemId}`);
                    const price = parseFloat(row.getAttribute('data-price'));
                    
                    // Recalculate local item subtotal
                    const subtotal = price * parseInt(quantity);
                    document.getElementById(`subtotal-${itemId}`).innerText = subtotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + " EGP";

                    // Recalculate invoice grand total
                    recalculateGrandTotal();
                } else {
                    alert(data.error || 'Quantity update failed.');
                }
            });
        }

        function recalculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('tbody tr[id^="row-"]').forEach(row => {
                const subtotalText = row.querySelector('.subtotal-text').innerText;
                const subtotal = parseFloat(subtotalText.replace(/[^\d.-]/g, ''));
                total += subtotal;
            });

            document.getElementById('grandTotal').innerText = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + " EGP";
        }
    </script>
</body>
</html>

