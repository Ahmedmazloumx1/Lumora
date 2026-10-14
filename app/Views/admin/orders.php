<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customer Orders | Lumora Admin</title>
    
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

        .admin-sidebar {
            height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
            width: 240px;
            background-color: var(--bg-secondary);
            border-right: 1px solid rgba(255, 255, 255, 0.03);
            padding-top: 2rem;
        }

        .admin-sidebar .nav-link {
            color: var(--text-gray);
            font-family: var(--font-headings);
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }

        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: var(--accent-gold);
            background-color: rgba(212, 175, 55, 0.05);
        }

        .main-content {
            margin-left: 240px;
            padding: 3rem;
        }

        .admin-box {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
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

        .form-select-sm {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: #111111; border-bottom: 1px solid rgba(255, 255, 255, 0.05); height: 70px; z-index: 1030;">
            <div class="container-fluid d-flex justify-content-between">
                <a class="navbar-brand fw-bold text-white ms-3" href="/admin">
                    <i class="fa-solid fa-gem me-2" style="color: var(--accent-gold);"></i>LUMORA <span class="badge bg-warning text-black ms-2" style="font-size: 0.75rem; background-color: var(--accent-gold) !important;">ADMIN</span>
                </a>
                <form action="/logout" method="post" class="d-inline"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>"><button type="submit" class="btn btn-outline-light btn-sm fw-bold me-3">Logout</button></form>
            </div>
        </nav>
    </header>

    <!-- Sidebar -->
    <aside class="admin-sidebar d-none d-md-block">
        <ul class="nav flex-column">
            <li><a href="/admin" class="nav-link"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>
            <li><a href="/admin/products" class="nav-link"><i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Control</a></li>
            <li><a href="/admin/custom-orders" class="nav-link"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Bespoke Requests</a></li>
            <li><a href="/admin/categories" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Manage Collections</a></li>
            <li><a href="/admin/users" class="nav-link"><i class="fa-solid fa-users me-2"></i> Manage Users</a></li>
            <li><a href="/admin/orders" class="nav-link active"><i class="fa-solid fa-receipt me-2"></i> Manage Orders</a></li>
            <li><a href="/admin/content" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Web Content CMS</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">CUSTOMER ORDERS MANAGEMENT</h2>

            <?php if (!empty($success)) { ?>
                <div class="alert alert-success border-0 small text-center mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: #34d399;">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php } ?>

            <div class="admin-box">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Order ID</th>
                            <th>Customer & Address [1.1.2]</th>
                            <th>Line Items</th>
                            <th>Total Invoice</th>
                            <th>Shipping Status</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0) { 
                            foreach ($orders as $order) { ?>
                            <tr>
                                <td class="ps-4 text-white fw-bold">#ORDER-<?php echo $order['id']; ?></td>
                                <td>
                                    <div class="text-white fw-bold"><?php echo htmlspecialchars($order['shipping_name']); ?></div>
                                    <div class="small text-muted mt-1"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                                    <div class="small text-warning mt-1" style="color: var(--accent-gold) !important;"><i class="fa-solid fa-phone me-1"></i> <?php echo htmlspecialchars($order['shipping_phone']); ?></div>
                                    <div class="small text-muted mt-1"><i class="fa-solid fa-map-pin me-1"></i> <?php echo htmlspecialchars($order['shipping_city']); ?>, <?php echo htmlspecialchars($order['shipping_address']); ?></div>
                                </td>
                                <td>
                                    <ul class="list-unstyled m-0">
                                        <?php foreach ($order['items'] as $item) { ?>
                                            <li class="small text-white mb-1">• <?php echo htmlspecialchars($item['product_name']); ?> <strong class="text-warning">x<?php echo $item['quantity']; ?></strong></li>
                                        <?php } ?>
                                    </ul>
                                </td>
                                <td class="text-white fw-bold"><?php echo number_format((float)$order['grand_total'], 2); ?> EGP</td>
                                <td>
                                    <form method="POST" action="/admin/orders/update">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select class="form-select form-select-sm" name="shipping_status" onchange="this.form.submit()">
                                            <option value="Pending" <?php echo $order['shipping_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Processing" <?php echo $order['shipping_status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Shipped" <?php echo $order['shipping_status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?php echo $order['shipping_status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Cancelled" <?php echo $order['shipping_status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="/admin/orders/update">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select class="form-select form-select-sm" name="payment_status" onchange="this.form.submit()">
                                            <option value="Pending" <?php echo $order['payment_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Paid" <?php echo $order['payment_status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                            <option value="Failed" <?php echo $order['payment_status'] === 'Failed' ? 'selected' : ''; ?>>Failed</option>
                                            <option value="Refunded" <?php echo $order['payment_status'] === 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h4 class="text-white">No customer orders recorded yet.</h4>
                                </td>
                            </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
