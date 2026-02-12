<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAdmin();

$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    
    // Validation
    if (empty($name) || $price <= 0) {
        $error = 'Nama produk dan harga harus diisi dengan benar';
    } else {
        try {
            $productsCollection = getCollection('products');
            
            $result = $productsCollection->insertOne([
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'description' => $description,
                'created_at' => new MongoDB\BSON\UTCDateTime(),
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]);
            
            if ($result->getInsertedCount() > 0) {
                setFlashMessage('success', 'Produk berhasil ditambahkan');
                header('Location: /admin/products.php');
                exit();
            } else {
                $error = 'Gagal menambahkan produk';
            }
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - <?php echo $appName; ?></title>
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
            <div class="card">
                <h2>Tambah Produk Baru</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Nama Produk *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="price">Harga (Rp) *</label>
                        <input type="number" id="price" name="price" required min="0" step="0.01"
                               value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        <a href="/admin/products.php" class="btn" style="background: #95a5a6; color: white; margin-left: 0.5rem;">Batal</a>
                    </div>
                </form>
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
