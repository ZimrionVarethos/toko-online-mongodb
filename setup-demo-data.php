<?php
/**
 * Setup Demo Data
 * Jalankan file ini sekali untuk membuat demo users dan products
 * 
 * Cara menjalankan:
 * php setup-demo-data.php
 */

require_once __DIR__ . '/config/database.php';

echo "=== Setup Demo Data untuk Toko Online ===\n\n";

try {
    $usersCollection = getCollection('users');
    $productsCollection = getCollection('products');
    
    // Check if demo data already exists
    $existingAdmin = $usersCollection->findOne(['email' => 'admin@toko.com']);
    
    if ($existingAdmin) {
        echo "⚠️  Demo data sudah ada. Menghapus data lama...\n";
        $usersCollection->deleteMany(['email' => ['$in' => ['admin@toko.com', 'user@toko.com']]]);
        $productsCollection->deleteMany([]);
    }
    
    echo "📝 Membuat demo users...\n";
    
    // Create admin user
    $usersCollection->insertOne([
        'name' => 'Admin Toko',
        'email' => 'admin@toko.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "   ✅ Admin user created\n";
    
    // Create normal user
    $usersCollection->insertOne([
        'name' => 'User Demo',
        'email' => 'user@toko.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'role' => 'user',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "   ✅ Normal user created\n";
    
    // Create unique index on email
    try {
        $usersCollection->createIndex(['email' => 1], ['unique' => true]);
        echo "   ✅ Unique index on email created\n";
    } catch (Exception $e) {
        echo "   ℹ️  Index already exists\n";
    }
    
    echo "\n📦 Membuat demo products...\n";
    
    // Create sample products
    $products = [
        [
            'name' => 'Laptop Gaming ROG',
            'price' => 25000000,
            'stock' => 5,
            'description' => 'Laptop gaming ROG dengan processor Intel Core i9, RAM 32GB, RTX 4080, dan layar 165Hz. Performa maksimal untuk gaming dan content creation.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Mouse Logitech MX Master 3',
            'price' => 1500000,
            'stock' => 20,
            'description' => 'Mouse wireless premium dengan desain ergonomis, multi-device connectivity, dan battery yang tahan hingga 70 hari.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Keyboard Mechanical Keychron K8',
            'price' => 1800000,
            'stock' => 15,
            'description' => 'Keyboard mechanical wireless dengan hot-swappable switch, RGB backlighting, dan kompatibel dengan Mac dan Windows.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Monitor LG UltraGear 27"',
            'price' => 5500000,
            'stock' => 8,
            'description' => 'Monitor gaming 27 inch dengan refresh rate 240Hz, response time 1ms, dan teknologi IPS Nano Color untuk warna akurat.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Headset SteelSeries Arctis 7',
            'price' => 2200000,
            'stock' => 12,
            'description' => 'Gaming headset wireless dengan surround sound DTS 7.1, microphone ClearCast, dan battery life 24 jam.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Webcam Logitech C920',
            'price' => 1200000,
            'stock' => 25,
            'description' => 'Webcam Full HD 1080p dengan autofocus, dual microphone, dan kompatibel untuk streaming dan video call.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'SSD Samsung 970 EVO Plus 1TB',
            'price' => 2500000,
            'stock' => 30,
            'description' => 'SSD NVMe M.2 dengan kecepatan baca 3500 MB/s dan tulis 3300 MB/s. Ideal untuk gaming dan aplikasi berat.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'RAM Corsair Vengeance 32GB',
            'price' => 3200000,
            'stock' => 18,
            'description' => 'RAM DDR4 32GB (2x16GB) dengan speed 3200MHz dan heatspreader aluminum untuk performa stabil.',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ]
    ];
    
    $result = $productsCollection->insertMany($products);
    echo "   ✅ " . count($products) . " products created\n";
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ Demo data berhasil dibuat!\n\n";
    
    echo "📋 Login Credentials:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Admin Account:\n";
    echo "  Email    : admin@toko.com\n";
    echo "  Password : admin123\n";
    echo "  Role     : admin\n\n";
    
    echo "User Account:\n";
    echo "  Email    : user@toko.com\n";
    echo "  Password : user123\n";
    echo "  Role     : user\n\n";
    
    echo str_repeat("=", 50) . "\n";
    echo "🚀 Silakan jalankan server dan akses aplikasi!\n";
    echo "   php -S localhost:8000\n";
    echo "   http://localhost:8000\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "\nPastikan:\n";
    echo "1. File .env sudah dibuat dan diisi dengan benar\n";
    echo "2. MongoDB Atlas connection string valid\n";
    echo "3. Composer dependencies sudah diinstall (composer install)\n";
}
