<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

$appName = env('APP_NAME', 'Toko Online Sederhana');
$error = '';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: /index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        try {
            $usersCollection = getCollection('users');
            
            // Find user by email
            $user = $usersCollection->findOne(['email' => $email]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                setUserSession($user);
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /admin/dashboard.php');
                } else {
                    header('Location: /user/dashboard.php');
                }
                exit();
            } else {
                $error = 'Email atau password salah';
            }
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $appName; ?></title>
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
                <h2 style="text-align: center;">Login</h2>

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

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Login
                        </button>
                    </div>

                    <p style="text-align: center; margin-top: 1rem;">
                        Belum punya akun? <a href="/auth/register.php">Daftar di sini</a>
                    </p>
                </form>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #ddd;">
                    <h4 style="text-align: center; margin-bottom: 1rem;">Demo Account</h4>
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; font-size: 0.9rem;">
                        <p><strong>Admin:</strong><br>
                        Email: admin@toko.com<br>
                        Password: admin123</p>
                        
                        <p style="margin-top: 0.5rem;"><strong>User:</strong><br>
                        Email: user@toko.com<br>
                        Password: user123</p>
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
