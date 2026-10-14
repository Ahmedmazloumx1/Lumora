<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Lumora</title>
    
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

        .success-box {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 4rem 2rem;
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
        }

        .success-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #10b981;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
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
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-5 text-center">
        <div class="success-box shadow-lg">
            <i class="fa-solid fa-circle-check text-success fa-5x mb-4"></i>
            
            <h1 style="font-family: var(--font-headings); letter-spacing: 1px; color: var(--text-white);">ORDER CONFIRMED</h1>
            <p class="text-warning fw-bold small mb-4" style="color: var(--accent-gold) !important;">YOUR ORDER NUMBER: <?php echo htmlspecialchars($order_number); ?></p>
            
            <p class="text-muted small mb-4 px-md-4">
                Thank you for choosing **Lumora**. Your custom acrylic art piece is officially scheduled for production. Our designers will prepare your layout specifications immediately.
            </p>

            <div class="bg-dark p-3 rounded mb-4 text-start small border border-secondary" style="background-color: rgba(255,255,255,0.01) !important;">
                <h6 class="text-white fw-bold mb-2"><i class="fa-solid fa-truck-fast text-success me-2"></i> Egypt Logistics Updates [1.1.2]</h6>
                <p class="text-muted mb-1">• Standard delivery riders will contact your phone via call or WhatsApp to confirm transit timing.</p>
                <p class="text-muted mb-0">• Your package is safely crated in heavy-duty custom wood framing and will arrive at your address in 3-5 working days.</p>
            </div>

            <hr class="my-4" style="border-top-color: rgba(255,255,255,0.05);">

            <a href="/" class="btn btn-gold">RETURN TO GALLERY</a>
        </div>
    </div>

</body>
</html>
