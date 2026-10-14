<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bespoke Art Configurator | Lumora</title>
    
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

        .configurator-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        .configurator-header {
            background: linear-gradient(135deg, #222225 0%, #111112 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .configurator-header h2 {
            font-family: var(--font-headings);
            letter-spacing: 1.5px;
            font-size: 2rem;
            color: var(--accent-gold);
        }

        .form-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: bold;
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

        .option-select-card {
            background-color: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 6px;
            padding: 1.25rem;
            cursor: pointer;
            text-align: center;
            transition: all 0.25s ease;
        }

        .option-select-card:hover {
            border-color: rgba(212, 175, 55, 0.4);
            background-color: rgba(212, 175, 55, 0.02);
        }

        .option-select-card.active {
            border-color: var(--accent-gold);
            background-color: rgba(212, 175, 55, 0.05);
        }

        .option-select-card i {
            color: var(--accent-gold);
            font-size: 1.85rem;
            margin-bottom: 0.75rem;
        }

        .option-select-card h6 {
            font-family: var(--font-headings);
            font-size: 1.15rem;
            margin: 0;
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
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
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
                <a href="/custom-order/status" class="btn btn-outline-light btn-sm fw-bold">Track Status</a>
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="configurator-card shadow">
                    <div class="configurator-header">
                        <h2>BESPOKE ART CONFIGURATOR</h2>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger border-0 small text-center mb-4" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php } ?>

                        <form method="POST" action="/custom-order" enctype="multipart/form-data" id="configForm">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                            <!-- Step 1: Brand/Subject Group -->
                            <div class="mb-3">
                                <label class="form-label">1. SUBJECT CATEGORY / BRAND</label>
                                <input type="text" class="form-control" name="brand" placeholder="E.g., Porsche, Gaming Setup, Abstract Geometry" required>
                            </div>

                            <!-- Step 2: Specific Model / Spec Details -->
                            <div class="mb-4">
                                <label class="form-label">2. MODEL NAME / SPECIFIC DETAILS</label>
                                <input type="text" class="form-control" name="model" placeholder="E.g., 911 GT3 RS 2026 or Cyberpunk Neon Panel" required>
                            </div>

                            <!-- Step 3: Artwork Style -->
                            <div class="mb-4">
                                <label class="form-label d-block mb-3">3. CHOOSE DESIGN STYLE</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="option-select-card active" onclick="selectOption(this, 'style', 'Silhouette')">
                                            <i class="fa-solid fa-car-side"></i>
                                            <h6>Silhouette Art</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="option-select-card" onclick="selectOption(this, 'style', 'LED Neon')">
                                            <i class="fa-regular fa-lightbulb"></i>
                                            <h6>LED Glowing Neon</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="option-select-card" onclick="selectOption(this, 'style', 'Blueprint')">
                                            <i class="fa-solid fa-drafting-compass"></i>
                                            <h6>Laser Blueprint</h6>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="art_style" id="selectedStyleVal" value="Silhouette">
                            </div>

                            <!-- Step 4: Dimensions Size -->
                            <div class="mb-4">
                                <label class="form-label d-block mb-3">4. SELECT ARTWORK DIMENSIONS</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="option-select-card active" onclick="selectOption(this, 'size', 'Medium')">
                                            <i class="fa-solid fa-maximize"></i>
                                            <h6>Medium (40x20cm)</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="option-select-card" onclick="selectOption(this, 'size', 'Large')">
                                            <i class="fa-solid fa-expand"></i>
                                            <h6>Large (60x30cm)</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="option-select-card" onclick="selectOption(this, 'size', 'Exhibition')">
                                            <i class="fa-solid fa-images"></i>
                                            <h6>Exhibition (90x45cm)</h6>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="art_size" id="selectedSizeVal" value="Medium">
                            </div>

                            <!-- Step 5: Custom text -->
                            <div class="mb-4">
                                <label class="form-label">5. CUSTOM NOTE / TEXT ENGRAVING (Optional)</label>
                                <input type="text" class="form-control" name="custom_text" placeholder="E.g., Engrave text: 'MY GT3'">
                            </div>

                            <!-- Step 6: Reference file upload -->
                            <div class="mb-4">
                                <label class="form-label">6. REFERENCE PHOTO / DESIGN DRAFT (Max 5MB)</label>
                                <input type="file" class="form-control" name="reference_photo" accept="image/*">
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-gold w-100 mt-3">SUBMIT SPEC REQUEST</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function selectOption(element, type, val) {
            const container = element.closest('.row');
            container.querySelectorAll('.option-select-card').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            if (type === 'style') {
                document.getElementById('selectedStyleVal').value = val;
            } else if (type === 'size') {
                document.getElementById('selectedSizeVal').value = val;
            }
        }

        document.getElementById('configForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerText = "SUBMITTING AND UPLOADING SPECS...";
        });
    </script>
</body>
</html>
