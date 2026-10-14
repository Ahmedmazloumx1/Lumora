<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions | Lumora</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
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
            padding-top: 100px;
        }

        .corp-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 4rem 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            max-width: 800px;
            margin: 0 auto;
        }

        .corp-card h1 {
            font-family: var(--font-headings);
            font-size: 3rem;
            color: var(--accent-gold);
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding-bottom: 1rem;
        }

        /* Accordion Custom Styling */
        .accordion-item {
            background-color: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 6px !important;
            overflow: hidden;
        }

        .accordion-button {
            background-color: transparent !important;
            color: var(--text-white) !important;
            font-family: var(--font-headings);
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            box-shadow: none !important;
            border: none !important;
        }

        .accordion-button:not(.collapsed) {
            color: var(--accent-gold) !important;
        }

        .accordion-button::after {
            filter: invert(1);
        }

        .accordion-body {
            color: var(--text-gray);
            font-size: 0.95rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
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

    <div class="container py-5">
        <div class="corp-card">
            <h1 class="mb-5 text-center">FREQUENTLY ASKED QUESTIONS</h1>
            
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faq_items as $index => $item) { ?>
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?php echo $index; ?>">
                                <?php echo htmlspecialchars($item['question']); ?>
                            </button>
                        </h2>
                        <div id="faq-<?php echo $index; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo htmlspecialchars($item['answer']); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
