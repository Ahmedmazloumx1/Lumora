<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Apex Acrylics</title>
    
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 0;
        }

        .auth-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            max-width: 550px;
            width: 100%;
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #222225 0%, #111112 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .auth-header h3 {
            font-family: var(--font-headings);
            letter-spacing: 1.5px;
            font-size: 2rem;
            color: var(--accent-gold);
            margin: 0;
        }

        .form-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.04) !important;
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
            padding: 0.75rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #000000;
            transform: translateY(-1px);
        }

        .link-gold {
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .link-gold:hover {
            color: var(--accent-gold-hover);
        }
    </style>
</head>
<body>

    <div class="auth-card mx-3 shadow-lg">
        <div class="auth-header">
            <h3>CREATE NEW ACCOUNT</h3>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger border-0 small text-center" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="POST" action="/register" id="regForm">
                <!-- CSRF Token verification field [1.1.2] -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">FULL NAME</label>
                    <input type="text" class="form-control" name="name" required placeholder="Ahmed Ali" pattern="^[a-zA-Z\s]+$" title="Only characters are allowed.">
                </div>

                <!-- Email address -->
                <div class="mb-3">
                    <label class="form-label">EMAIL ADDRESS</label>
                    <input type="email" class="form-control" name="email" required placeholder="name@domain.com">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">PASSWORD (minimum 8 characters)</label>
                    <input type="password" class="form-control" name="password" required placeholder="••••••••" pattern=".{8,}" title="Requires 8 characters minimum.">
                </div>

                <!-- Local Egypt Phone Validation -->
                <div class="mb-3">
                    <label class="form-label">CONTACT NUMBER (Egyptian Mobile)</label>
                    <input type="tel" class="form-control" name="contact" required placeholder="01012345678" pattern="^01[0125][0-9]{8}$" title="Requires standard 11 digit mobile number beginning with 010, 011, 012, or 015">
                </div>

                <!-- Egyptian Governorates drop down selector -->
                <div class="mb-3">
                    <label class="form-label">GOVERNORATE</label>
                    <select class="form-select" name="city" required>
                        <option value="" disabled selected>Select your region</option>
                        <option value="Cairo">Cairo (القاهرة)</option>
                        <option value="Giza">Giza (الجيزة)</option>
                        <option value="Alexandria">Alexandria (الإسكندرية)</option>
                        <option value="Qalyubia">Qalyubia (القليوبية)</option>
                        <option value="Dakahlia">Dakahlia (الدقهلية)</option>
                        <option value="Sharqia">Sharqia (الشرقية)</option>
                        <option value="Gharbia">Gharbia (الغربية)</option>
                        <option value="Monufia">Monufia (المنوفية)</option>
                        <option value="Beheira">Beheira (البحيرة)</option>
                        <option value="Kafr El Sheikh">Kafr El Sheikh (كفر الشيخ)</option>
                        <option value="Damietta">Damietta (دمياط)</option>
                        <option value="Port Said">Port Said (بورسعيد)</option>
                        <option value="Ismailia">Ismailia (الإسماعيلية)</option>
                        <option value="Suez">Suez (السويس)</option>
                        <option value="Fayoum">Fayoum (الفيوم)</option>
                        <option value="Beni Suef">Beni Suef (بني سويف)</option>
                        <option value="Minya">Minya (المنيا)</option>
                        <option value="Asyut">Asyut (أسيوط)</option>
                        <option value="Sohag">Sohag (سوهاج)</option>
                        <option value="Qena">Qena (قنا)</option>
                        <option value="Luxor">Luxor (الأقصر)</option>
                        <option value="Aswan">Aswan (أسوان)</option>
                    </select>
                </div>

                <!-- Detailed Address -->
                <div class="mb-4">
                    <label class="form-label">DETAILED DELIVERY ADDRESS</label>
                    <input type="text" class="form-control" name="address" required placeholder="Street Name, Building No, Apartment No.">
                </div>

                <button type="submit" id="submitBtn" class="btn btn-gold w-100 mb-3 d-flex align-items-center justify-content-center">
                    <span id="btnText">CREATE SYSTEM ACCOUNT</span>
                    <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Already registered?</span> 
                <a href="/login" class="link-gold small ms-1">Login here</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('regForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('btnText').innerText = "GENERATING IDENTITY ENCRYPTION...";
            document.getElementById('spinner').classList.remove('d-none');
        });
    </script>
</body>
</html>
