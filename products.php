<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();

// Get all products from MongoDB
try {
    $productsCollection = getCollection('products');
    $products = $productsCollection->find([], ['sort' => ['created_at' => -1]]);
} catch (Exception $e) {
    $error = "Error mengambil data produk: " . $e->getMessage();
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - <?php echo $appName; ?></title>
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
            <h2>Katalog Produk</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="products-grid">
                <?php 
                $hasProducts = false;
                foreach ($products as $product): 
                    $hasProducts = true;
                ?>
                    <div class="product-card">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        <p class="product-description">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>
                        <?php if (isset($product['stock'])): ?>
                            <p style="margin-top: 1rem; color: <?php echo $product['stock'] > 0 ? '#27ae60' : '#e74c3c'; ?>;">
                                Stock: <?php echo $product['stock']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (!$hasProducts): ?>
                    <div class="card" style="grid-column: 1 / -1; text-align: center;">
                        <p style="color: #666; font-size: 1.1rem;">Belum ada produk tersedia.</p>
                        <?php if (isAdmin()): ?>
                            <a href="/admin/products.php" class="btn btn-primary" style="margin-top: 1rem;">Tambah Produk</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
