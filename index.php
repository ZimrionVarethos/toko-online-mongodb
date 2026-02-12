<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/env.php';

loadEnv(__DIR__ . '/.env');
$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $appName; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo $appName; ?></h1>
            <nav>
                <a href="/">Home</a>
                <a href="/products.php">Produk</a>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/dashboard.php">Admin Dashboard</a>
                    <?php else: ?>
                        <a href="/user/dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    <a href="/auth/logout.php">Logout (<?php echo htmlspecialchars($user['name']); ?>)</a>
                <?php else: ?>
                    <a href="/auth/login.php">Login</a>
                    <a href="/auth/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php 
            $flash = getFlashMessage();
            if ($flash): 
            ?>
                <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>

            <div class="card" style="text-align: center;">
                <h2>Selamat Datang di <?php echo $appName; ?></h2>
                <p style="font-size: 1.2rem; color: #666; margin: 1.5rem 0;">
                    Platform belanja online sederhana dengan teknologi MongoDB
                </p>
                
                <div style="margin-top: 2rem;">
                    <a href="/products.php" class="btn btn-primary" style="margin: 0 0.5rem;">Lihat Produk</a>
                    
                    <?php if (!isLoggedIn()): ?>
                        <a href="/auth/register.php" class="btn btn-success" style="margin: 0 0.5rem;">Daftar Sekarang</a>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #ddd;">
                    <h3>Fitur Aplikasi</h3>
                    <div class="stats-grid" style="margin-top: 1.5rem;">
                        <div class="stat-card">
                            <h3>📦</h3>
                            <p>Katalog Produk</p>
                        </div>
                        <div class="stat-card">
                            <h3>🔐</h3>
                            <p>Autentikasi Aman</p>
                        </div>
                        <div class="stat-card">
                            <h3>⚡</h3>
                            <p>MongoDB Database</p>
                        </div>
                        <div class="stat-card">
                            <h3>👨‍💼</h3>
                            <p>Admin Panel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 <?php echo $appName; ?>. Built with PHP Native & MongoDB.</p>
        </div>
    </footer>
</body>
</html>
