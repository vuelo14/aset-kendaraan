<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aset Kendaraan</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .split-screen {
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        /* Left Pane - Branding */
        .left-pane {
            background: var(--primary-gradient);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 2rem;
            z-index: 1;
        }
        .left-pane::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: -1;
        }
        .brand-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        .brand-title {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.8;
            font-weight: 300;
            text-align: center;
            max-width: 80%;
        }

        /* Right Pane - Form */
        .right-pane {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            position: relative;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
        }
        
        .form-floating > label {
            padding-left: 1rem;
        }
        .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
        .btn-primary {
            background: #4F46E5;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #4338ca;
        }
        
        /* Mobile logic */
        @media (max-width: 767.98px) {
            .left-pane {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 split-screen">
            <!-- Left Side -->
            <div class="col-md-6 col-lg-7 left-pane">
                <i class="bi bi-car-front-fill brand-icon"></i>
                <h1 class="brand-title">Aset Kendaraan</h1>
                <p class="brand-subtitle">Sistem Manajemen & Monitoring Aset Operasional</p>
                <div class="mt-5 small opacity-50">
                    &copy; <?= date('Y') ?> Vuelo14 Integration
                </div>
            </div>
            
            <!-- Right Side -->
            <div class="col-md-6 col-lg-5 right-pane">
                <div class="login-wrapper">
                    <?php include $viewFile; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
