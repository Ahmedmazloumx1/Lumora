<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Command Center | Lumora Admin</title>
    
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

        .control-box {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2rem;
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
            padding: 18px 20px;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            padding: 0.5rem 1rem;
            border-radius: 6px;
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
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
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

    <!-- Unified Sidebar Navigation featuring all 7 sections [1.1.1] -->
    <aside class="admin-sidebar d-none d-md-block">
        <ul class="nav flex-column">
            <li><a href="/admin" class="nav-link"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>
            <li><a href="/admin/products" class="nav-link active"><i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Control</a></li>
            <li><a href="/admin/custom-orders" class="nav-link"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Bespoke Requests</a></li>
            <li><a href="/admin/categories" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Manage Collections</a></li>
            <li><a href="/admin/users" class="nav-link"><i class="fa-solid fa-users me-2"></i> Manage Users</a></li>
            <li><a href="/admin/orders" class="nav-link"><i class="fa-solid fa-receipt me-2"></i> Manage Orders</a></li>
            <li><a href="/admin/content" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Web Content CMS</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">INVENTORY COMMAND CENTER</h2>

            <?php if (!empty($success)) { ?>
                <div class="alert alert-success border-0 small text-center mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: #34d399;">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php } ?>

            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger border-0 small text-center mb-4" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <!-- Advanced Bulk Pricing Tool Container -->
            <div class="control-box mb-5">
                <h4 class="text-white fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;"><i class="fa-solid fa-calculator text-warning me-2" style="color: var(--accent-gold) !important;"></i> GLOBAL CATALOG PRICING ENGINE</h4>
                
                <form method="POST" action="/admin/products/bulk-edit">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label mb-2">ADJUSTMENT TYPE</label>
                            <select class="form-select" name="type">
                                <option value="flat">Flat Rate Adjustment (EGP)</option>
                                <option value="percent">Percentage Margin Adjustment (%)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-2">ACTION DIRECTION</label>
                            <select class="form-select" name="action">
                                <option value="add">Increase Prices (+)</option>
                                <option value="subtract">Decrease / Discount Prices (-)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-2">ADJUSTMENT VALUE</label>
                            <input type="number" class="form-control" name="amount" required step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-gold w-100 py-2.5">EXECUTE GLOBAL ENGINE</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Complete Catalog Inventory Table Wrapper -->
            <div class="control-box">
                <h4 class="text-white fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;"><i class="fa-solid fa-boxes-stacked text-warning me-2" style="color: var(--accent-gold) !important;"></i> INVENTORY RECORDS</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th>Stock Status</th>
                                <th class="text-center">Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $prod) { ?>
                            <tr>
                                <td class="ps-4 text-white fw-bold">#PROD-<?php echo $prod['id']; ?></td>
                                <td class="text-white fw-bold"><?php echo htmlspecialchars($prod['name']); ?></td>
                                <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                                <td class="price-text font-monospace"><?php echo number_format((float)$prod['price'], 2); ?> EGP</td>
                                <td>
                                    <?php if ((int)$prod['stock_qty'] > 0) { ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">IN STOCK (<?php echo $prod['stock_qty']; ?>)</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">OUT OF STOCK</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <!-- Dynamic Duplicator Form -->
                                    <form method="POST" action="/admin/products/duplicate" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-warning px-2.5 me-1" title="Duplicate product as Draft"><i class="fa-solid fa-copy"></i></button>
                                    </form>

                                    <!-- Soft Delete Form -->
                                    <form method="POST" action="/admin/products/delete" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5" onclick="return confirm('Soft-delete this catalog entry? This preserves order database histories.')"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
