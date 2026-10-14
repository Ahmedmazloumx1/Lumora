<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview | Lumora Command Console</title>
    
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

        /* METRIC LINK CARDS: Interactive clicks [1.1.1] */
        .metric-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2.25rem 1.5rem;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
            display: block;
            text-decoration: none !important;
        }
        .metric-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent-gold);
            box-shadow: 0 15px 30px rgba(212,175,55,0.15);
        }

        .metric-title {
            font-family: var(--font-headings);
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            color: var(--text-gray);
        }

        .metric-value {
            font-family: var(--font-headings);
            font-size: 3rem;
            color: var(--text-white);
            margin: 0;
            line-height: 1;
        }

        .chart-box {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
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
            <li><a href="/admin" class="nav-link active"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>
            <li><a href="/admin/products" class="nav-link"><i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Control</a></li>
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
            <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">MANAGEMENT TERMINAL</h2>
            
            <!-- Clickable Interactive KPIs Grid [1.1.1] -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-sm-6">
                    <a href="/admin/products" class="metric-card">
                        <i class="fa-solid fa-images fa-2x mb-3" style="color: var(--accent-gold);"></i>
                        <div class="metric-title">TOTAL PRODUCTS</div>
                        <h2 class="metric-value"><?php echo $product_count; ?></h2>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="/admin/users" class="metric-card">
                        <i class="fa-solid fa-users-gear fa-2x mb-3" style="color: var(--accent-gold);"></i>
                        <div class="metric-title">REGISTERED CUSTOMERS</div>
                        <h2 class="metric-value"><?php echo $user_count; ?></h2>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <a href="/admin/orders" class="metric-card">
                        <i class="fa-solid fa-receipt fa-2x mb-3" style="color: var(--accent-gold);"></i>
                        <div class="metric-title">TOTAL ORDERS</div>
                        <h2 class="metric-value"><?php echo $order_count; ?></h2>
                    </a>
                </div>
            </div>

            <!-- Advanced Financial Metrics Grid -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="metric-card" style="border-color: rgba(16, 185, 129, 0.2);">
                        <i class="fa-solid fa-coins fa-2x mb-3 text-success"></i>
                        <div class="metric-title">GROSS REVENUE</div>
                        <h2 class="metric-value text-success"><?php echo number_format($revenue, 2); ?> EGP</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <i class="fa-solid fa-calculator fa-2x mb-3 text-warning"></i>
                        <div class="metric-title">AVERAGE ORDER VALUE (AOV)</div>
                        <h2 class="metric-value text-warning"><?php echo number_format($aov, 2); ?> EGP</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card" style="border-color: rgba(99, 102, 241, 0.2);">
                        <i class="fa-solid fa-basket-shopping fa-2x mb-3 text-info"></i>
                        <div class="metric-title">ACTIVE LIVE BASKETS</div>
                        <h2 class="metric-value text-info"><?php echo $active_carts; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Sales Charts Visualizer -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-box">
                        <h4 class="fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;"><i class="fa-solid fa-chart-simple text-warning me-2" style="color: var(--accent-gold) !important;"></i> TOP 5 SELLING PRODUCTS (UNITS SOLD)</h4>
                        <div style="position: relative; height: 320px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const labels = <?php echo json_encode($chart_labels); ?>;
            const dataValues = <?php echo json_encode($chart_data); ?>;

            if (labels.length === 0) {
                ctx.canvas.parentNode.innerHTML = '<div class="text-center py-5 text-muted small"><i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>No sales transaction logs recorded yet. Once checkout orders occur, your bestseller analytics will map dynamically here.</div>';
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantity Sold',
                        data: dataValues,
                        backgroundColor: 'rgba(212, 175, 55, 0.15)',
                        borderColor: '#D4AF37',
                        borderWidth: 2,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(212, 175, 55, 0.45)',
                        hoverBorderColor: '#D4AF37'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.04)' },
                            ticks: { color: '#8B8B8B', stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#8B8B8B', font: { family: 'Inter' } }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
