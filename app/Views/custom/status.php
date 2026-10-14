<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bespoke Orders | Lumora</title>
    
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

        .status-card {
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

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #fbbf24 !important;
            border: 1px solid rgba(245, 158, 11, 0.3) !important;
        }

        .badge-accepted {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #34d399 !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
        }

        .badge-rejected {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #f87171 !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
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
                <a href="/custom-order" class="btn btn-outline-light btn-sm fw-bold">New Custom Request</a>
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-5">
        <h2 class="fw-bold mb-4" style="font-family: var(--font-headings); font-size: 2.25rem; letter-spacing: 0.5px;">
            <i class="fa-solid fa-wand-magic-sparkles me-2" style="color: var(--accent-gold);"></i>MY BESPOKE REQUESTS
        </h2>

        <div class="status-card">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Request ID</th>
                        <th>Subject / Brand</th>
                        <th>Artwork Specifications</th>
                        <th>Custom Text</th>
                        <th>Photo</th>
                        <th class="text-center">Design Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (count($orders) > 0) { 
                        foreach ($orders as $order) { 
                            $status_class = 'badge-pending';
                            $status_text = 'DRAFT UNDER REVIEW';
                            if ($order['status'] == 'Accepted') {
                                $status_class = 'badge-accepted';
                                $status_text = 'APPROVED - PREPARING RENDER';
                            } elseif ($order['status'] == 'Rejected') {
                                $status_class = 'badge-rejected';
                                $status_text = 'DECLINED - INVALID DETAILS';
                            }
                    ?>
                    <tr>
                        <td class="ps-4 text-white fw-bold">#SPEC-<?php echo $order['id']; ?></td>
                        <td>
                            <strong class="text-white text-uppercase"><?php echo htmlspecialchars($order['brand']); ?></strong>
                            <div class="small text-muted mt-1"><?php echo htmlspecialchars($order['model']); ?></div>
                        </td>
                        <td>
                            <div class="text-white"><?php echo htmlspecialchars($order['style']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($order['size']); ?> Size</small>
                        </td>
                        <td>
                            <?php echo !empty($order['custom_text']) ? "<code>" . htmlspecialchars($order['custom_text']) . "</code>" : "<span class='text-muted small'>None</span>"; ?>
                        </td>
                        <td>
                            <?php if ($order['image']) { ?>
                                <a href="/public/img/<?php echo htmlspecialchars($order['image']); ?>" target="_blank" class="text-warning small fw-bold" style="color: var(--accent-gold) !important;">View Image</a>
                            <?php } else { ?>
                                <span class="text-muted small">No Image</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <span class="badge <?php echo $status_class; ?> px-3 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <?php echo $status_text; ?>
                            </span>
                        </td>
                    </tr>
                    <?php } } else { ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <h4 class="text-white">You have not submitted any custom requests yet.</h4>
                            <a href="/custom-order" class="btn btn-gold mt-3">Configure Bespoke Art</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
