<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Lumora</title>
    
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

        .account-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .nav-tabs {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-tabs .nav-link {
            color: var(--text-gray);
            border: none;
            font-family: var(--font-headings);
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            color: var(--text-white);
        }

        .nav-tabs .nav-link.active {
            background-color: transparent;
            color: var(--accent-gold);
            border-bottom: 2px solid var(--accent-gold);
        }

        .order-history-card {
            background-color: #0b0b0c;
            border: 1px solid rgba(255,255,255,0.02);
            border-radius: 12px;
            padding: 1.75rem;
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
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: #aa820a;
            border-color: #aa820a;
            color: #000000;
        }

        /* Order tracking stepper bar [1.1.1] */
        .order-tracking-bar {
            height: 6px;
            background-color: rgba(255,255,255,0.05);
            border-radius: 6px;
            overflow: hidden;
        }
        .order-tracking-fill {
            height: 100%;
            background: linear-gradient(90deg, #fbbf24 0%, #10b981 100%);
            border-radius: 6px;
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
            <i class="fa-solid fa-circle-user text-warning me-2" style="color: var(--accent-gold) !important;"></i>CLIENT ACCOUNT HUB
        </h2>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger border-0 small text-center mb-4" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success border-0 small text-center mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: #34d399;">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php } ?>

        <div class="account-card">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">My Orders</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Shipping Profile</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Account Security</button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="accountTabsContent">
                <!-- Tab 1: Order History & Tracking [1.1.1] -->
                <div class="tab-pane fade show active" id="orders" role="tabpanel">
                    <?php if (count($orders) > 0) { 
                        foreach ($orders as $order) {
                            // Calculate active tracking bar fill width [1.1.1]
                            $fill_width = 15; // default Received (Pending)
                            $tracking_status = "Order Received - Awaiting Review";

                            if ($order['shipping_status'] === 'Processing') {
                                $fill_width = 50;
                                $tracking_status = "Processing - Laser Cutting Artwork";
                            } elseif ($order['shipping_status'] === 'Shipped') {
                                $fill_width = 80;
                                $tracking_status = "Shipped - Courier En Route";
                            } elseif ($order['shipping_status'] === 'Delivered') {
                                $fill_width = 100;
                                $tracking_status = "Delivered - Package Handed Over";
                            } elseif ($order['shipping_status'] === 'Cancelled') {
                                $fill_width = 0;
                                $tracking_status = "Cancelled - Order Cancelled";
                            }
                    ?>
                        <div class="order-history-card mb-4 shadow-sm">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom border-secondary pb-3 mb-3">
                                <div>
                                    <strong class="text-white small">ORDER ID: <?php echo htmlspecialchars($order['order_number']); ?></strong>
                                    <div class="small text-muted mt-1">Placed on: <?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                                </div>
                                <div class="text-md-end mt-2 mt-md-0">
                                    <strong class="text-warning fs-5" style="color: var(--accent-gold) !important;"><?php echo number_format((float)$order['grand_total'], 2); ?> EGP</strong>
                                    <div class="small text-muted">Paid via: <?php echo htmlspecialchars($order['payment_method']); ?></div>
                                </div>
                            </div>

                            <!-- Stepper Tracker Bar [1.1.1] -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small text-white-50 mb-2">
                                    <span>Courier Tracking: <strong class="text-white"><?php echo $tracking_status; ?></strong></span>
                                    <span><?php echo $fill_width; ?>%</span>
                                </div>
                                <div class="order-tracking-bar">
                                    <div class="order-tracking-fill" style="width: <?php echo $fill_width; ?>%;"></div>
                                </div>
                            </div>

                            <!-- Line Items sub-list -->
                            <h6 class="text-white fw-bold mb-3 small">ITEMS ORDERED:</h6>
                            <ul class="list-group list-group-flush bg-transparent">
                                <?php foreach ($order['items'] as $item) { ?>
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 border-dark">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php $item_img = !empty($item['main_image']) ? $item['main_image'] : 'placeholder.svg'; ?>
                                            <img src="/public/img/<?php echo htmlspecialchars($item_img); ?>" style="width: 45px; height: 45px; object-fit: contain;" alt="">
                                            <div>
                                                <strong class="text-white small d-block"><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                                <small class="text-muted">Quantity: <?php echo $item['quantity']; ?> &times; <?php echo number_format((float)$item['price'], 2); ?> EGP</small>
                                            </div>
                                        </div>
                                        <span class="text-white small fw-bold"><?php echo number_format((float)$item['price'] * (int)$item['quantity'], 2); ?> EGP</span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } } else { ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-box-open text-muted fa-4x mb-3"></i>
                            <h4 class="text-white">No orders recorded yet.</h4>
                            <a href="/products" class="btn btn-gold mt-3">Browse Art Gallery</a>
                        </div>
                    <?php } ?>
                </div>

                <!-- Tab 2: Default Address Manager -->
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <form method="POST" action="/account/profile">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">CONSIGNEE NAME</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CONTACT PHONE (Egyptian Mobile)</label>
                                <input type="tel" class="form-control" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>" required pattern="^01[0125][0-9]{8}$">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">GOVERNORATE</label>
                                <select class="form-select" name="city" required>
                                    <?php
                                        $govs = ['Cairo', 'Giza', 'Alexandria', 'Qalyubia', 'Sharqia', 'Dakahlia', 'Gharbia', 'Monufia', 'Beheira', 'Kafr El Sheikh', 'Damietta', 'Port Said', 'Ismailia', 'Suez', 'Fayoum', 'Beni Suef', 'Minya', 'Asyut', 'Sohag', 'Qena', 'Luxor', 'Aswan'];
                                        foreach ($govs as $gov) {
                                            $selected = (isset($user['city']) && strtolower($user['city']) === strtolower($gov)) ? 'selected' : '';
                                            echo "<option value=\"$gov\" $selected>$gov</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">DETAILED SHIPPING ADDRESS</label>
                                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold mt-4">UPDATE SHIPPING PROFILE</button>
                    </form>
                </div>

                <!-- Tab 3: Password Update -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <form method="POST" action="/account/password">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                        <div class="mb-3">
                            <label class="form-label">CURRENT PASSWORD</label>
                            <input type="password" class="form-control" name="current_password" required placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NEW PASSWORD (minimum 8 characters)</label>
                            <input type="password" class="form-control" name="new_password" required placeholder="••••••••" pattern=".{8,}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">CONFIRM NEW PASSWORD</label>
                            <input type="password" class="form-control" name="confirm_password" required placeholder="••••••••" pattern=".{8,}">
                        </div>

                        <button type="submit" class="btn btn-gold">UPDATE SECURE PASSWORD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include layout footer via path-safe MVC configuration [1.1.1] -->
    <?php include APP_PATH . '/Views/layouts/footer.php'; ?>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

