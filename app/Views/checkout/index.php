<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Lumora</title>
    
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

        .checkout-box {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .summary-box {
            background-color: #0b0b0c;
            border: 1px solid rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            padding: 2rem;
        }

        .form-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-gold) !important;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25) !important;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.3rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.85rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: #aa820a;
            border-color: #aa820a;
        }

        .payment-option-card {
            background-color: rgba(255, 255, 255, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
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
                <a href="/cart" class="btn btn-outline-light btn-sm fw-bold">Back to Basket</a>
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-5">
        <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">
            <i class="fa-solid fa-lock text-warning me-2" style="color: var(--accent-gold) !important;"></i>SECURE CHECKOUT
        </h2>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger border-0 small text-center mb-4" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <div class="row g-4">
            <!-- Left Column: Shipping Form -->
            <div class="col-lg-7">
                <div class="checkout-box">
                    <h4 class="text-white fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;">1. SHIPPING INFORMATION</h4>
                    
                    <form method="POST" action="/checkout" id="checkoutForm">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label">CONSIGNEE FULL NAME</label>
                            <input type="text" class="form-control" name="shipping_name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required placeholder="E.g., Ahmed Ali">
                        </div>

                        <!-- Phone Validation -->
                        <div class="mb-3">
                            <label class="form-label">CONTACT PHONE NUMBER (Egyptian Mobile)</label>
                            <input type="tel" class="form-control" name="shipping_phone" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>" required placeholder="01012345678" pattern="^01[0125][0-9]{8}$" title="Requires standard 11 digit mobile number beginning with 010, 011, 012, or 015">
                        </div>

                        <!-- Governorate Selector -->
                        <div class="mb-3">
                            <label class="form-label">GOVERNORATE</label>
                            <select class="form-select" name="shipping_city" id="citySelect" required onchange="calculateShippingAndGrandTotal()">
                                <option value="" disabled selected>Select your region</option>
                                <?php
                                    $govs = ['Cairo', 'Giza', 'Alexandria', 'Qalyubia', 'Sharqia', 'Dakahlia', 'Gharbia', 'Monufia', 'Beheira', 'Kafr El Sheikh', 'Damietta', 'Port Said', 'Ismailia', 'Suez', 'Fayoum', 'Beni Suef', 'Minya', 'Asyut', 'Sohag', 'Qena', 'Luxor', 'Aswan'];
                                    foreach ($govs as $gov) {
                                        $selected = (isset($user['city']) && strtolower($user['city']) === strtolower($gov)) ? 'selected' : '';
                                        echo "<option value=\"$gov\" $selected>$gov</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Detailed Shipping Address -->
                        <div class="mb-4">
                            <label class="form-label">DETAILED DELIVERY ADDRESS</label>
                            <input type="text" class="form-control" name="shipping_address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required placeholder="Street Name, Building No, Apartment No.">
                        </div>

                        <h4 class="text-white fw-bold mb-3 mt-5" style="font-family: var(--font-headings); letter-spacing: 0.5px;">2. PAYMENT OPTIONS</h4>
                        <div class="payment-option-card">
                            <div class="form-check w-100 m-0 d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="codMethod" value="COD" checked>
                                <label class="form-check-label text-white fw-bold m-0" for="codMethod">
                                    <i class="fa-solid fa-money-bill-wave text-success me-2"></i> CASH ON DELIVERY (COD)
                                </label>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-gold w-100 mt-5">PLACE ORDER (CASH ON DELIVERY)</button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Order Review -->
            <div class="col-lg-5">
                <div class="summary-box">
                    <h4 class="text-white fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;">ORDER REVIEW</h4>
                    
                    <ul class="list-group list-group-flush bg-transparent">
                        <?php 
                        $subtotal = 0.00;
                        foreach ($items as $item) { 
                            $item_sub = (float)$item['unit_price'] * (int)$item['quantity'];
                            $subtotal += $item_sub;
                        ?>
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 border-secondary">
                                <div>
                                    <h6 class="text-white mb-0 small fw-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">Qty: <?php echo $item['quantity']; ?> &times; <?php echo number_format((float)$item['unit_price'], 2); ?> EGP</small>
                                </div>
                                <span class="text-white small fw-bold"><?php echo number_format($item_sub, 2); ?> EGP</span>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="border-top border-secondary pt-3 mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal:</span>
                            <span class="text-white small fw-bold" id="subtotalVal" data-subtotal="<?php echo $subtotal; ?>"><?php echo number_format($subtotal, 2); ?> EGP</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Shipping Fee:</span>
                            <span class="text-warning small fw-bold" id="shippingVal" style="color: var(--accent-gold) !important;">Calculating...</span>
                        </div>
                        <div class="d-flex justify-content-between border-top border-secondary pt-3 mt-2">
                            <span class="text-white fw-bold">Grand Total:</span>
                            <span class="text-warning fw-bold fs-4" id="grandTotal" style="color: var(--accent-gold) !important;">Calculating...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateShippingAndGrandTotal() {
            const citySelect = document.getElementById('citySelect');
            const subtotalValEl = document.getElementById('subtotalVal');
            const shippingValEl = document.getElementById('shippingVal');
            const grandTotalEl = document.getElementById('grandTotal');

            if (!citySelect.value) {
                shippingValEl.innerText = "Select Governorate";
                grandTotalEl.innerText = "Select Governorate";
                return;
            }

            const subtotal = parseFloat(subtotalValEl.dataset.subtotal);
            const city = citySelect.value.toLowerCase().trim();
            
            // Apply official Egypt shipping rate definitions [1.1.2]
            let shippingFee = 90.00; // Tier 3 rate: Upper & Frontier Egypt
            
            const tier1 = ['cairo', 'giza'];
            const tier2 = [
                'alexandria', 'qalyubia', 'sharqia', 'dakahlia', 'gharbia', 
                'monufia', 'beheira', 'kafr el sheikh', 'damietta', 'port said', 
                'ismailia', 'suez'
            ];

            if (tier1.includes(city)) {
                shippingFee = 50.00;
            } else if (tier2.includes(city)) {
                shippingFee = 70.00;
            }

            const grandTotal = subtotal + shippingFee;

            shippingValEl.innerText = shippingFee.toFixed(2) + " EGP";
            grandTotalEl.innerText = grandTotal.toFixed(2) + " EGP";
        }

        document.getElementById('checkoutForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerText = "PROCESSING TRANSACTION...";
        });

        // Initial run on page load
        document.addEventListener('DOMContentLoaded', calculateShippingAndGrandTotal);
    </script>
</body>
</html>
