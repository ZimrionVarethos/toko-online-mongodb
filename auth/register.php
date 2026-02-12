<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

$appName = env('APP_NAME', 'Toko Online Sederhana');
$error = '';
$success = '';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: /index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $confirmPassword) {
        $error = 'Password dan konfirmasi password tidak sama';
    } else {
        try {
            $usersCollection = getCollection('users');
            
            // Check if email already exists
            $existingUser = $usersCollection->findOne(['email' => $email]);
            
            if ($existingUser) {
                $error = 'Email sudah terdaftar';
            } else {
                // Create unique index on email if not exists
                try {
                    $usersCollection->createIndex(['email' => 1], ['unique' => true]);
                } catch (Exception $e) {
                    // Index might already exist
                }
                
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $result = $usersCollection->insertOne([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => 'user', // default role
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ]);
                
                if ($result->getInsertedCount() > 0) {
                    setFlashMessage('success', 'Registrasi berhasil! Silakan login.');
                    header('Location: /auth/login.php');
                    exit();
                } else {
                    $error = 'Gagal mendaftar. Silakan coba lagi.';
                }
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
    <title>Register - <?php echo $appName; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo $appName; ?></h1>
            <nav>
                <a href="/">Home</a>
                <a href="/products.php">Produk</a>
                <a href="/auth/login.php">Login</a>
                <a href="/auth/register.php">Register</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="card">
                <h2 style="text-align: center;">Daftar Akun Baru</h2>

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

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required 
                               minlength="6">
                        <small style="color: #666;">Minimal 6 karakter</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               minlength="6">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Daftar
                        </button>
                    </div>

                    <p style="text-align: center; margin-top: 1rem;">
                        Sudah punya akun? <a href="/auth/login.php">Login di sini</a>
                    </p>
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
