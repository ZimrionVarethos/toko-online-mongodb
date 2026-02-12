<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAdmin();

$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();

// Get statistics
try {
    $productsCollection = getCollection('products');
    $usersCollection = getCollection('users');
    
    $totalProducts = $productsCollection->countDocuments();
    $totalUsers = $usersCollection->countDocuments();
    $totalAdmins = $usersCollection->countDocuments(['role' => 'admin']);
    
} catch (Exception $e) {
    $error = 'Error mengambil statistik: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo $appName; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo $appName; ?> - Admin</h1>
            <nav>
                <a href="/">Home</a>
                <a href="/products.php">Produk</a>
                <a href="/admin/dashboard.php">Dashboard</a>
                <a href="/admin/products.php">Kelola Produk</a>
                <a href="/auth/logout.php">Logout (<?php echo htmlspecialchars($user['name']); ?>)</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Admin Dashboard</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $totalProducts ?? 0; ?></h3>
                    <p>Total Produk</p>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h3><?php echo $totalUsers ?? 0; ?></h3>
                    <p>Total User</p>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h3><?php echo $totalAdmins ?? 0; ?></h3>
                    <p>Total Admin</p>
                </div>
            </div>

            <div class="card">
                <h3>Menu Admin</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <a href="/admin/products.php" class="btn btn-primary" style="padding: 1.5rem; text-align: center; text-decoration: none;">
                        📦 Kelola Produk
                    </a>
                    <a href="/products.php" class="btn btn-success" style="padding: 1.5rem; text-align: center; text-decoration: none;">
                        👁️ Lihat Toko
                    </a>
                </div>
            </div>

            <div class="card">
                <h3>Informasi Admin</h3>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Role:</strong> <span style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem; border-radius: 4px;">Admin</span></p>
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
