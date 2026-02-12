<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAdmin();

$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();
$error = '';
$success = '';

// Handle Delete
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    try {
        $productsCollection = getCollection('products');
        $result = $productsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete'])]);
        
        if ($result->getDeletedCount() > 0) {
            setFlashMessage('success', 'Produk berhasil dihapus');
        } else {
            setFlashMessage('error', 'Produk tidak ditemukan');
        }
        header('Location: /admin/products.php');
        exit();
    } catch (Exception $e) {
        $error = 'Error menghapus produk: ' . $e->getMessage();
    }
}

// Get all products
try {
    $productsCollection = getCollection('products');
    $products = $productsCollection->find([], ['sort' => ['created_at' => -1]]);
} catch (Exception $e) {
    $error = 'Error mengambil data produk: ' . $e->getMessage();
    $products = [];
}

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - <?php echo $appName; ?></title>
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
                <a href="/auth/logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2>Kelola Produk</h2>
                <a href="/admin/product-add.php" class="btn btn-success">+ Tambah Produk</a>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stock</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hasProducts = false;
                        foreach ($products as $product): 
                            $hasProducts = true;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $product['stock'] ?? 0; ?></td>
                                <td><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/product-edit.php?id=<?php echo $product['_id']; ?>" 
                                           class="btn btn-warning btn-small">Edit</a>
                                        <a href="/admin/products.php?delete=<?php echo $product['_id']; ?>" 
                                           class="btn btn-danger btn-small"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (!$hasProducts): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #666;">
                                    Belum ada produk. <a href="/admin/product-add.php">Tambah produk pertama</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
