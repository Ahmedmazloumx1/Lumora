<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumora | Premium Acrylic Wall Art & Personalized Décor</title>

    <!-- Google Fonts: Bebas Neue for bold headings, Inter for body text -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- AOS (Animate On Scroll) Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom Global Styles -->
    <link rel="stylesheet" href="/public/css/style.css?v=<?php echo time(); ?>">

    <!-- Theme Inline Override (Ensures styling matches specifications before CSS refactoring) -->
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
            overflow-x: hidden;
            padding-top: 70px;
        }

        #hero {
            height: 90vh;
            min-height: 600px;
            background-color: #000000;
            position: relative;
        }

        .hero-bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(17,17,17,0.3) 0%, rgba(17,17,17,0.75) 100%);
            z-index: 1;
        }

        #hero-video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            opacity: 0.65;
        }

        #hero-video-container iframe {
            width: 100vw;
            height: 56.25vw;
            min-height: 100vh;
            min-width: 177.77vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .hero-title {
            font-family: var(--font-headings);
            font-size: clamp(3rem, 7vw, 5.5rem);
            letter-spacing: 2px;
            line-height: 0.95;
            color: var(--text-white);
        }

        .category-card {
            background-color: var(--bg-secondary) !important;
            border: 1px solid rgba(255, 255, 255, 0.03) !important;
            border-radius: 12px !important;
            padding: 3rem 2rem;
            text-align: center;
            display: block;
            text-decoration: none !important;
            transition: all 0.35s ease;
        }

        .category-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent-gold) !important;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.15) !important;
        }

        .category-card i {
            color: var(--accent-gold);
            font-size: 2.5rem;
            margin-bottom: 1.25rem;
            transition: transform 0.3s ease;
        }

        .category-card:hover i {
            transform: scale(1.15);
        }

        .category-card h3 {
            font-family: var(--font-headings);
            font-size: 1.65rem;
            letter-spacing: 0.5px;
            color: var(--text-white);
            margin-bottom: 0.75rem;
        }

        .category-card p {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.5;
        }

        .btn-apex-primary {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #000000;
            font-family: var(--font-headings);
            font-size: 1.2rem;
            letter-spacing: 1px;
            font-weight: bold;
            padding: 12px 35px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-apex-primary:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
            transform: translateY(-2px);
        }

        .btn-apex-outline {
            background-color: transparent;
            border: 2px solid var(--text-white);
            color: var(--text-white);
            font-family: var(--font-headings);
            font-size: 1.2rem;
            letter-spacing: 1px;
            padding: 12px 35px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-apex-outline:hover {
            background-color: var(--text-white);
            color: var(--bg-primary);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <!-- Preloader -->
    <div id="preloader" class="d-flex align-items-center justify-content-center" style="background-color: var(--bg-primary); position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999; transition: opacity 0.5s ease;">
        <div class="spinner-border" role="status" style="color: var(--accent-gold) !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Include layout header via path-safe MVC configuration [1.1.1] -->
    <?php include APP_PATH . '/Views/layouts/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="d-flex align-items-center justify-content-center text-center text-white position-relative overflow-hidden">
            <div class="hero-bg-overlay"></div>
            
            <div id="hero-video-container">
                <iframe src="https://player.vimeo.com/video/371333342?autoplay=1&loop=1&autopause=0&muted=1&background=1" 
                        frameborder="0" 
                        allow="autoplay; fullscreen" 
                        allowfullscreen>
                </iframe>
            </div>
            
            <div class="container position-relative z-3 py-5">
                <h1 class="hero-title mb-3">
                    Premium Acrylic Art<br><span style="color: var(--accent-gold);">For Modern Decor</span>
                </h1>
                <p class="hero-subtitle mb-5" style="color: var(--text-gray);">
                    Shatterproof, ultra-gloss acrylic prints and customized decor installations designed to elevate your living space, showroom, or workspace.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="/products" class="btn btn-apex-primary">
                        Shop Collection <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                    <a href="/custom-order" class="btn btn-apex-outline">
                        Customize Yours <i class="fas fa-tools ms-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories-section" class="py-5">
            <div class="container py-4">
                <h2 class="text-center text-white mb-5" style="font-family: var(--font-headings); letter-spacing: 1px; font-size: 3rem;">
                    Browse Categories
                </h2>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6">
                        <a href="/products?category=wall-art" class="category-card">
                            <i class="fa-solid fa-image"></i>
                            <h3>Acrylic Wall Art</h3>
                            <p>Sleek, high-gloss optical acrylic art panels tailored to fit modern walls.</p>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <a href="/products?category=led-signs" class="category-card">
                            <i class="fa-regular fa-lightbulb"></i>
                            <h3>LED Acrylic Signs</h3>
                            <p>Glowing neon-backlit acrylic plates perfect for game rooms and offices.</p>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <a href="/products?category=keychains" class="category-card">
                            <i class="fa-solid fa-key"></i>
                            <h3>Acrylic Keychains</h3>
                            <p>Premium, precision-cut miniature artwork keychains.</p>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <a href="/products?category=blueprints" class="category-card">
                            <i class="fa-solid fa-drafting-compass"></i>
                            <h3>Blueprint Prints</h3>
                            <p>Detailed technical schematics and blueprints etched on acrylic plates.</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Include layout footer safely [1.1.1] -->
    <?php 
        if (file_exists(APP_PATH . '/Views/layouts/footer.php')) {
            include APP_PATH . '/Views/layouts/footer.php';
        }
    ?>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(() => {
                    preloader.style.opacity = '0';
                    preloader.addEventListener('transitionend', () => preloader.remove());
                }, 300);
            }
        });
    </script>
</body>
</html>
