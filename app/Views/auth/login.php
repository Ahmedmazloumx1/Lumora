<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Apex Acrylics</title>
    
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
        }

        .auth-card {
            background-color: var(--bg-secondary);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            max-width: 450px;
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

        .form-control {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-white) !important;
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
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
            <h3>SECURE GATEWAY</h3>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger border-0 small text-center" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>
            
            <?php if (!empty($success)) { ?>
                <div class="alert alert-success border-0 small text-center" style="background-color: rgba(16, 185, 129, 0.1); color: #34d399;">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php } ?>

            <form method="POST" action="/login" id="loginForm">
                <!-- CSRF Token verification field [1.1.2] -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Core\Session::get('csrf_token')); ?>">

                <!-- Email address -->
                <div class="mb-3">
                    <label class="form-label">EMAIL ADDRESS</label>
                    <input type="email" class="form-control" name="email" required placeholder="name@domain.com">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label">PASSWORD</label>
                    <input type="password" class="form-control" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" id="submitBtn" class="btn btn-gold w-100 mb-3 d-flex align-items-center justify-content-center">
                    <span id="btnText">ACCESS DASHBOARD</span>
                    <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">New to the brand?</span> 
                <a href="/register" class="link-gold small ms-1">Register here</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('btnText').innerText = "AUTHORIZING GATEWAY...";
            document.getElementById('spinner').classList.remove('d-none');
        });
    </script>
</body>
</html>
