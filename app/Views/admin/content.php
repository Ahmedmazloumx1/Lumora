<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic CMS Console | Lumora Admin</title>
    
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

        .cms-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        .nav-tabs {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-tabs .nav-link {
            color: var(--text-gray);
            border: none;
            font-family: var(--font-headings);
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            color: var(--text-white);
            border: none;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent;
            color: var(--accent-gold);
            border-bottom: 2px solid var(--accent-gold);
        }

        .form-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: bold;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
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
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border-radius: 6px;
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
            <li><a href="/admin/categories" class="nav-link"><i class="fa-solid fa-folder-open me-2"></i> Manage Collections</a></li>
            <li><a href="/admin/users" class="nav-link"><i class="fa-solid fa-users me-2"></i> Manage Users</a></li>
            <li><a href="/admin/orders" class="nav-link"><i class="fa-solid fa-receipt me-2"></i> Manage Orders</a></li>
            <li><a href="/admin/content" class="nav-link active"><i class="fa-solid fa-folder-open me-2"></i> Web Content CMS</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">WEB CONTENT CMS</h2>

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

            <div class="cms-card">
                <form method="POST" action="/admin/content/update" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                    <!-- Tabs Selector Navigation -->
                    <ul class="nav nav-tabs mb-4" id="cmsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General Settings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages" type="button" role="tab">Corporate Pages</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button" role="tab">FAQ Management</button>
                        </li>
                    </ul>

                    <!-- Tabs Contents -->
                    <div class="tab-content" id="cmsTabsContent">
                        <!-- Tab 1: General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label mb-2">UPDATE BRAND LOGO</label>
                                    <input type="file" class="form-control" name="site_logo" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-2">CONTACT EMAIL ADDRESS</label>
                                    <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('contact_email', 'consignee@domain.com')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-2">CONTACT PHONE NUMBER</label>
                                    <input type="text" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('contact_phone', '01012345678')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-2">INSTAGRAM PROFILE LINK</label>
                                    <input type="url" class="form-control" name="social_instagram" value="<?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('social_instagram', 'https://instagram.com')); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Corporate Pages Content -->
                        <div class="tab-pane fade" id="pages" role="tabpanel">
                            <div class="mb-4">
                                <label class="form-label mb-2">ABOUT US DESCRIPTION</label>
                                <textarea class="form-control" name="about_us_content" rows="4"><?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('about_us_content', '')); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label mb-2">TERMS OF SERVICE POLICY</label>
                                <textarea class="form-control" name="policy_terms_content" rows="4"><?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('policy_terms_content', '')); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label mb-2">PRIVACY POLICY</label>
                                <textarea class="form-control" name="policy_privacy_content" rows="4"><?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('policy_privacy_content', '')); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label mb-2">RETURNS & REFUND POLICY</label>
                                <textarea class="form-control" name="policy_refund_content" rows="4"><?php echo htmlspecialchars(\App\Helpers\SettingsHelper::get('policy_refund_content', '')); ?></textarea>
                            </div>
                        </div>

                        <!-- Tab 3: FAQ Management Accordion -->
                        <div class="tab-pane fade" id="faq" role="tabpanel">
                            <div id="faqItemsContainer">
                                <?php
                                $faqJson = \App\Helpers\SettingsHelper::get('faq_items', '[]');
                                $faqItems = json_decode($faqJson, true) ?: [];
                                $count = count($faqItems);
                                
                                for ($i = 0; $i < max($count, 2); $i++) {
                                    $q = $faqItems[$i]['question'] ?? '';
                                    $a = $faqItems[$i]['answer'] ?? '';
                                ?>
                                    <div class="row g-3 mb-3 align-items-end pb-3 border-bottom border-dark">
                                        <div class="col-md-5">
                                            <label class="form-label mb-2">QUESTION #<?php echo $i + 1; ?></label>
                                            <input type="text" class="form-control" name="faq_questions[]" value="<?php echo htmlspecialchars($q); ?>" placeholder="E.g., How thick is the acrylic?">
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label mb-2">ANSWER #<?php echo $i + 1; ?></label>
                                            <input type="text" class="form-control" name="faq_answers[]" value="<?php echo htmlspecialchars($a); ?>" placeholder="E.g., All custom panels are constructed using premium 3mm acrylic.">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold w-100 mt-5">SAVE ALL CHANGES</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
