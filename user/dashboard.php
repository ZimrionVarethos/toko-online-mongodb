<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireLogin();

$appName = env('APP_NAME', 'Toko Online Sederhana');
$user = getSessionUser();

// Get user details from database
try {
    $usersCollection = getCollection('users');
    $userDoc = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($user['id'])]);
} catch (Exception $e) {
    $error = 'Error mengambil data user: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - <?php echo $appName; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo $appName; ?></h1>
            <nav>
                <a href="/">Home</a>
                <a href="/products.php">Produk</a>
                <a href="/user/dashboard.php">Dashboard</a>
                <a href="/auth/logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Dashboard User</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h3>Profil Saya</h3>
                
                <?php if (isset($userDoc)): ?>
                    <table>
                        <tr>
                            <th style="width: 200px;">Nama</th>
                            <td><?php echo htmlspecialchars($userDoc['name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($userDoc['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span style="background: #3498db; color: white; padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 0.9rem;">
                                    <?php echo htmlspecialchars($userDoc['role']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>
                                <?php 
                                if (isset($userDoc['created_at'])) {
                                    $date = $userDoc['created_at']->toDateTime();
                                    echo $date->format('d F Y H:i');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Informasi</h3>
                <p>Selamat datang di dashboard user. Anda dapat melihat produk yang tersedia melalui menu Produk.</p>
                
                <div style="margin-top: 1.5rem;">
                    <a href="/products.php" class="btn btn-primary">Lihat Semua Produk</a>
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
