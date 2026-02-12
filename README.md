# Toko Online Sederhana - PHP Native & MongoDB

Project toko online sederhana menggunakan PHP Native (tanpa framework) dan MongoDB sebagai database. Project ini dibuat untuk testing logika CRUD MongoDB dan dapat dideploy di hosting/VPS yang mendukung ekstensi MongoDB PHP.

## 🚀 Fitur

- ✅ Autentikasi (Register & Login) dengan password hashing
- ✅ Session management untuk user authentication
- ✅ Role-based access control (Admin & User)
- ✅ CRUD Produk (Create, Read, Update, Delete) menggunakan MongoDB
- ✅ Admin Dashboard untuk kelola produk
- ✅ User Dashboard untuk melihat profil
- ✅ Halaman katalog produk
- ✅ Responsive design dengan CSS sederhana

## 📁 Struktur Folder

```
toko-online-mongodb/
├── admin/                  # Halaman khusus admin
│   ├── dashboard.php      # Dashboard admin
│   ├── products.php       # Kelola produk (list, delete)
│   ├── product-add.php    # Tambah produk
│   └── product-edit.php   # Edit produk
├── auth/                   # Autentikasi
│   ├── login.php          # Halaman login
│   ├── register.php       # Halaman registrasi
│   └── logout.php         # Proses logout
├── config/                 # Konfigurasi
│   ├── database.php       # Koneksi MongoDB
│   ├── session.php        # Session helpers
│   └── env.php            # Environment loader
├── user/                   # Halaman khusus user
│   └── dashboard.php      # Dashboard user
├── assets/                 # Asset statis
│   └── css/
│       └── style.css      # CSS styling
├── vendor/                 # Composer dependencies (auto-generated)
├── .env                    # Environment variables (buat manual)
├── .env.example           # Template environment variables
├── .gitignore             # Git ignore file
├── composer.json          # Composer configuration
├── index.php              # Landing page
├── products.php           # Halaman katalog produk
└── README.md              # Dokumentasi
```

## 🛠️ Teknologi

- **PHP 7.4+** - Bahasa pemrograman
- **MongoDB Atlas** - Database cloud
- **MongoDB PHP Library** - Driver MongoDB untuk PHP
- **Composer** - Dependency manager
- **CSS Native** - Styling sederhana

## 📋 Prasyarat

1. PHP 7.4 atau lebih tinggi
2. Composer (untuk install MongoDB library)
3. Ekstensi PHP MongoDB (`mongodb` extension)
4. Akun MongoDB Atlas (gratis)

### Install Ekstensi MongoDB di PHP

**Linux/Ubuntu:**
```bash
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/7.4/cli/php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/7.4/apache2/php.ini
```

**macOS:**
```bash
pecl install mongodb
echo "extension=mongodb.so" >> /usr/local/etc/php/7.4/php.ini
```

**Windows:**
1. Download DLL dari https://pecl.php.net/package/mongodb
2. Extract dan copy `php_mongodb.dll` ke folder `ext` PHP
3. Edit `php.ini` dan tambahkan: `extension=php_mongodb.dll`

Verifikasi instalasi:
```bash
php -m | grep mongodb
```

## 🔧 Setup MongoDB Atlas

### 1. Buat Akun MongoDB Atlas
1. Kunjungi https://www.mongodb.com/cloud/atlas/register
2. Daftar dengan email (gratis)

### 2. Buat Cluster
1. Pilih "Create a Cluster"
2. Pilih "Shared" (Free tier - M0)
3. Pilih region terdekat (Singapore untuk Indonesia)
4. Klik "Create Cluster"

### 3. Setup Database Access
1. Klik "Database Access" di sidebar
2. Klik "Add New Database User"
3. Buat username dan password (catat!)
4. Pilih role: "Read and write to any database"
5. Klik "Add User"

### 4. Setup Network Access
1. Klik "Network Access" di sidebar
2. Klik "Add IP Address"
3. Pilih "Allow Access from Anywhere" (untuk testing)
4. Atau masukkan IP spesifik untuk production
5. Klik "Confirm"

### 5. Get Connection String
1. Klik "Clusters" di sidebar
2. Klik tombol "Connect" pada cluster Anda
3. Pilih "Connect your application"
4. Copy connection string yang mirip:
   ```
   mongodb+srv://<username>:<password>@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
   ```

### 6. Buat Database & Collections
MongoDB akan otomatis membuat database dan collections saat pertama kali insert data. Namun Anda bisa buat manual:

1. Klik "Browse Collections"
2. Klik "Create Database"
3. Database name: `toko_online`
4. Collection name: `users`
5. Klik "Create"
6. Buat collection `products` dengan cara yang sama

## 🚀 Instalasi & Setup Project

### 1. Clone atau Download Project
```bash
git clone <repository-url>
cd toko-online-mongodb
```

### 2. Install Dependencies
```bash
composer install
```

Jika belum punya Composer, install dari https://getcomposer.org/

### 3. Setup Environment Variables
Copy file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Edit file `.env` dan isi dengan data MongoDB Atlas Anda:
```env
# MongoDB Atlas Connection
MONGO_URI=mongodb+srv://your-username:your-password@cluster.xxxxx.mongodb.net/toko_online?retryWrites=true&w=majority
DB_NAME=toko_online

# Application Settings
APP_NAME="Toko Online Sederhana"
APP_URL=http://localhost:8000
```

**Penting:** Ganti `your-username` dan `your-password` dengan credentials MongoDB Atlas Anda!

### 4. Setup Demo Data (Opsional)

Buat file `setup-demo-data.php` di root folder:

```php
<?php
require_once __DIR__ . '/config/database.php';

try {
    $usersCollection = getCollection('users');
    $productsCollection = getCollection('products');
    
    // Create admin user
    $usersCollection->insertOne([
        'name' => 'Admin',
        'email' => 'admin@toko.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    
    // Create normal user
    $usersCollection->insertOne([
        'name' => 'User Demo',
        'email' => 'user@toko.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'role' => 'user',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    
    // Create sample products
    $products = [
        [
            'name' => 'Laptop Gaming',
            'price' => 15000000,
            'stock' => 5,
            'description' => 'Laptop gaming high-end dengan spesifikasi terbaik untuk gaming dan produktivitas',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Mouse Wireless',
            'price' => 250000,
            'stock' => 20,
            'description' => 'Mouse wireless ergonomis dengan battery tahan lama',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ],
        [
            'name' => 'Keyboard Mechanical',
            'price' => 800000,
            'stock' => 10,
            'description' => 'Keyboard mechanical RGB dengan switch premium',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]
    ];
    
    $productsCollection->insertMany($products);
    
    echo "Demo data berhasil ditambahkan!\n";
    echo "Admin: admin@toko.com / admin123\n";
    echo "User: user@toko.com / user123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Jalankan:
```bash
php setup-demo-data.php
```

### 5. Jalankan Server

**Menggunakan PHP Built-in Server:**
```bash
php -S localhost:8000
```

**Menggunakan Apache/Nginx:**
Configure virtual host ke folder project

**Akses aplikasi:**
```
http://localhost:8000
```

## 👤 Demo Accounts

Jika sudah menjalankan setup demo data:

**Admin:**
- Email: admin@toko.com
- Password: admin123

**User:**
- Email: user@toko.com
- Password: user123

## 📖 Cara Penggunaan

### User Biasa
1. **Register**: Buat akun baru di `/auth/register.php`
2. **Login**: Login dengan email dan password
3. **Dashboard**: Lihat profil di `/user/dashboard.php`
4. **Browse Produk**: Lihat katalog produk di `/products.php`

### Admin
1. **Login** sebagai admin
2. **Dashboard Admin**: Lihat statistik di `/admin/dashboard.php`
3. **Kelola Produk**: 
   - List produk: `/admin/products.php`
   - Tambah produk: `/admin/product-add.php`
   - Edit produk: klik tombol "Edit" di list produk
   - Hapus produk: klik tombol "Hapus" di list produk

## 🗄️ Struktur Database MongoDB

### Collection: `users`
```javascript
{
  "_id": ObjectId("..."),
  "name": "John Doe",
  "email": "john@example.com",
  "password": "$2y$10$...", // hashed password
  "role": "user", // or "admin"
  "created_at": ISODate("2026-02-12T...")
}
```

### Collection: `products`
```javascript
{
  "_id": ObjectId("..."),
  "name": "Laptop Gaming",
  "price": 15000000,
  "stock": 5,
  "description": "Laptop gaming high-end...",
  "created_at": ISODate("2026-02-12T..."),
  "updated_at": ISODate("2026-02-12T...")
}
```

## 🔐 Keamanan

- ✅ Password di-hash menggunakan `password_hash()` dan `password_verify()`
- ✅ Session-based authentication
- ✅ Role-based access control (RBAC)
- ✅ Input validation dan sanitization
- ✅ Prepared statements (MongoDB library handles this)
- ✅ XSS protection dengan `htmlspecialchars()`

## 🚀 Deploy ke Hosting/VPS

### Prasyarat Hosting
- PHP 7.4+ dengan ekstensi MongoDB
- Composer support
- HTTPS recommended

### Langkah Deploy

1. **Upload Files**
   - Upload semua file ke hosting via FTP/SSH
   - Pastikan folder `vendor/` juga terupload

2. **Install Dependencies** (jika belum)
   ```bash
   composer install --no-dev
   ```

3. **Setup Environment**
   - Copy `.env.example` ke `.env`
   - Edit `.env` dengan MongoDB Atlas credentials production

4. **Set Permissions**
   ```bash
   chmod 644 .env
   chmod 755 admin/ auth/ user/ config/ assets/
   ```

5. **Configure Web Server**
   
   **Apache (.htaccess):**
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php [L]
   ```

6. **Security**
   - Pastikan `.env` tidak bisa diakses publik
   - Gunakan HTTPS
   - Update MongoDB Network Access dengan IP server production

### Hosting Gratis yang Mendukung MongoDB

1. **Railway.app** - Support MongoDB & PHP
2. **Render.com** - Support PHP & external MongoDB
3. **Vercel** (dengan custom PHP runtime)
4. **Heroku** (dengan buildpack PHP)

## 🧪 Testing

### Test Koneksi MongoDB
Buat file `test-connection.php`:
```php
<?php
require_once 'config/database.php';

try {
    $db = getDB();
    echo "✅ Koneksi MongoDB berhasil!\n";
    echo "Database: " . $db->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
```

Jalankan:
```bash
php test-connection.php
```

## 🐛 Troubleshooting

### Error: "MongoDB extension not installed"
**Solusi:** Install ekstensi MongoDB PHP (lihat bagian Prasyarat)

### Error: "Connection timeout"
**Solusi:** 
- Cek koneksi internet
- Pastikan IP sudah ditambahkan di MongoDB Atlas Network Access
- Pastikan credentials di `.env` benar

### Error: "Authentication failed"
**Solusi:** 
- Periksa username dan password di connection string
- Pastikan user sudah dibuat di MongoDB Atlas Database Access

### Halaman admin bisa diakses tanpa login
**Solusi:** 
- Pastikan `requireAdmin()` dipanggil di awal file
- Cek apakah session sudah berjalan

## 📝 To-Do / Future Improvements

- [ ] Upload gambar produk
- [ ] Shopping cart functionality
- [ ] Order management
- [ ] Payment gateway integration
- [ ] Email verification
- [ ] Password reset
- [ ] Search & filter produk
- [ ] Pagination untuk list produk

## 📄 License

MIT License - Free to use for learning and commercial projects

## 👨‍💻 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository ini.

## 🙏 Credits

- PHP Native
- MongoDB Atlas
- MongoDB PHP Library
- Composer

---

**Happy Coding! 🚀**
