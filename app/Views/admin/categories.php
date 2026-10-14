<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Collections | Lumora Admin</title>
    
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

        .form-control {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .form-control:focus {
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

    <!-- Sidebar -->
    <aside class="admin-sidebar d-none d-md-block">
        <ul class="nav flex-column">
            <li><a href="/admin" class="nav-link"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>
            <li><a href="/admin/products" class="nav-link"><i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Control</a></li>
            <li><a href="/admin/custom-orders" class="nav-link"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Bespoke Requests</a></li>
            <li><a href="/admin/categories" class="nav-link active"><i class="fa-solid fa-folder-open me-2"></i> Manage Collections</a></li>
            <li><a href="/admin/users" class="nav-link"><i class="fa-solid fa-users me-2"></i> Manage Users</a></li>
            <li><a href="/admin/orders" class="nav-link"><i class="fa-solid fa-receipt me-2"></i> Manage Orders</a></li>
            <li><a href="/admin/content" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Web Content CMS</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">MANAGE CATEGORY COLLECTIONS</h2>

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

            <div class="row g-4">
                <!-- Add Category Form Panel -->
                <div class="col-md-4">
                    <div class="admin-box p-4">
                        <h4 class="text-white fw-bold mb-4" style="font-family: var(--font-headings); letter-spacing: 0.5px;"><i class="fa-solid fa-folder-plus text-warning me-2" style="color: var(--accent-gold) !important;"></i> ADD NEW CATEGORY</h4>
                        
                        <form method="POST" action="/admin/categories/add">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                            <div class="mb-3">
                                <label class="form-label mb-2">CATEGORY / COLLECTION NAME</label>
                                <input type="text" class="form-control" name="category_name" required placeholder="E.g., Posters, Keychains, LED Signs">
                            </div>
                            <button type="submit" class="btn btn-gold w-100 py-2.5">SAVE CATEGORY</button>
                        </form>
                    </div>
                </div>

                <!-- Existing Categories Table Panel -->
                <div class="col-md-8">
                    <div class="admin-box">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Category Name</th>
                                        <th>URL Slug</th>
                                        <th class="text-center">Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $cat) { ?>
                                    <tr>
                                        <td class="ps-4 text-white fw-bold">#CAT-<?php echo $cat['id']; ?></td>
                                        <td class="text-white fw-bold"><?php echo htmlspecialchars($cat['name']); ?></td>
                                        <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                                        <td class="text-center">
                                            <!-- Restrict deleting default category ID 1 -->
                                            <?php if ((int)$cat['id'] !== 1) { ?>
                                                <form method="POST" action="/admin/categories/delete">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">
                                                    <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Confirm deleting this category? All its items will safely default back to category ID #1.')">
                                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                                    </button>
                                                </form>
                                            <?php } else { ?>
                                                <span class="text-muted small">Default Locked</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
