# 🚀 Quick Start Guide

Panduan cepat untuk menjalankan project Toko Online MongoDB dalam 5 menit!

## ⚡ Setup Cepat (5 Langkah)

### 1️⃣ Install Prerequisites
```bash
# Pastikan sudah terinstall:
- PHP 7.4+ dengan MongoDB extension
- Composer
```

**Cek versi PHP:**
```bash
php -v
php -m | grep mongodb  # Harus muncul "mongodb"
```

**Install MongoDB extension jika belum ada:**
```bash
# Ubuntu/Debian
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.1/cli/php.ini

# macOS
pecl install mongodb

# Windows
# Download dari https://pecl.php.net/package/mongodb
# Copy dll ke folder ext PHP
```

---

### 2️⃣ Setup MongoDB Atlas (Gratis)

1. **Buat akun**: https://www.mongodb.com/cloud/atlas/register
2. **Buat cluster** (pilih FREE tier M0)
3. **Create Database User**:
   - Username: `admin`
   - Password: `buatPasswordKuat123`
4. **Network Access**: Klik "Allow Access from Anywhere" (untuk testing)
5. **Get Connection String**: 
   - Klik "Connect" → "Connect your application"
   - Copy connection string:
   ```
   mongodb+srv://admin:buatPasswordKuat123@cluster0.xxxxx.mongodb.net/?retryWrites=true&w=majority
   ```

---

### 3️⃣ Setup Project

```bash
# Clone atau download project
cd toko-online-mongodb

# Install dependencies
composer install

# Copy .env
cp .env.example .env

# Edit .env (ganti dengan connection string Anda)
nano .env
```

**Edit file `.env`:**
```env
MONGO_URI=mongodb+srv://admin:buatPasswordKuat123@cluster0.xxxxx.mongodb.net/toko_online?retryWrites=true&w=majority
DB_NAME=toko_online
APP_NAME="Toko Online Sederhana"
```

---

### 4️⃣ Test & Setup Data

```bash
# Test koneksi MongoDB
php test-connection.php

# Jika berhasil, setup demo data
php setup-demo-data.php
```

---

### 5️⃣ Jalankan Server

```bash
# Start PHP built-in server
php -S localhost:8000

# Buka browser
http://localhost:8000
```

---

## 🎯 Demo Login

### Admin Account
```
Email: admin@toko.com
Password: admin123
```

**Akses:** http://localhost:8000/auth/login.php

**Fitur admin:**
- Dashboard statistik
- CRUD Produk (Create, Read, Update, Delete)
- Lihat semua user

### User Account
```
Email: user@toko.com
Password: user123
```

**Fitur user:**
- Dashboard user
- Lihat profil
- Browse produk

---

## 📂 Struktur File Penting

```
toko-online-mongodb/
├── .env                    ← Konfigurasi MongoDB (EDIT INI!)
├── index.php              ← Landing page
├── products.php           ← Katalog produk
├── auth/
│   ├── login.php         ← Halaman login
│   └── register.php      ← Halaman register
├── admin/
│   ├── dashboard.php     ← Admin dashboard
│   ├── products.php      ← Kelola produk
│   ├── product-add.php   ← Tambah produk
│   └── product-edit.php  ← Edit produk
├── user/
│   └── dashboard.php     ← User dashboard
└── config/
    ├── database.php      ← Koneksi MongoDB
    └── session.php       ← Session management
```

---

## ⚙️ Common Commands

### Development
```bash
# Start server
php -S localhost:8000

# Test koneksi
php test-connection.php

# Reset demo data
php setup-demo-data.php
```

### Composer
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Install production (tanpa dev packages)
composer install --no-dev
```

---

## 🐛 Troubleshooting Cepat

### ❌ "MongoDB extension not installed"
```bash
sudo pecl install mongodb
php -m | grep mongodb  # Verify
```

### ❌ "Connection timeout"
**Solusi:**
1. Cek internet connection
2. Pastikan IP sudah di-whitelist di MongoDB Atlas → Network Access
3. Cek `.env` - pastikan MONGO_URI benar

### ❌ "Authentication failed"
**Solusi:**
1. Cek username dan password di MongoDB Atlas
2. Update connection string di `.env`
3. Pastikan password tidak ada karakter special yang perlu di-encode

### ❌ Halaman blank / error 500
**Solusi:**
```bash
# Cek PHP error log
tail -f /var/log/php_errors.log

# Atau enable error display (HANYA untuk development)
# Edit php.ini:
display_errors = On
error_reporting = E_ALL
```

---

## 📱 Navigasi Website

### Public Pages (Tanpa Login)
- `/` - Landing page
- `/products.php` - Katalog produk
- `/auth/login.php` - Login
- `/auth/register.php` - Register

### User Pages (Login Required)
- `/user/dashboard.php` - User dashboard

### Admin Pages (Admin Only)
- `/admin/dashboard.php` - Admin dashboard
- `/admin/products.php` - List & kelola produk
- `/admin/product-add.php` - Tambah produk baru
- `/admin/product-edit.php?id=xxx` - Edit produk

---

## 🎓 Next Steps

### Belajar Lebih Lanjut
1. **Modifikasi styling** - Edit `/assets/css/style.css`
2. **Tambah fitur** - Lihat TODO di README.md
3. **Deploy ke production** - Baca DEPLOYMENT.md

### Eksplorasi Kode
1. **Authentication flow** - Lihat `/auth/` dan `/config/session.php`
2. **MongoDB CRUD** - Lihat `/admin/products.php` dan product-add/edit
3. **Database connection** - Lihat `/config/database.php`

---

## 📚 Dokumentasi Lengkap

- **README.md** - Dokumentasi lengkap project
- **DEPLOYMENT.md** - Panduan deploy ke production
- **MONGODB-STRUCTURE.md** - Struktur database dan queries

---

## 💡 Tips

### Development Tips
- Gunakan `var_dump()` atau `print_r()` untuk debugging
- Cek MongoDB logs di Atlas dashboard
- Gunakan browser DevTools untuk debug frontend

### Production Tips
- Jangan gunakan "Allow from Anywhere" di MongoDB Network Access
- Enable HTTPS dengan SSL certificate
- Change default admin password
- Set `display_errors = Off` di php.ini

---

## ✅ Checklist Setup

- [ ] PHP 7.4+ terinstall
- [ ] MongoDB extension terinstall (`php -m | grep mongodb`)
- [ ] Composer terinstall
- [ ] MongoDB Atlas cluster dibuat
- [ ] Database user dibuat di Atlas
- [ ] Network Access di-setup di Atlas
- [ ] Connection string di-copy
- [ ] `.env` file dibuat dan diisi
- [ ] `composer install` berhasil
- [ ] `test-connection.php` berhasil
- [ ] `setup-demo-data.php` berhasil
- [ ] Server berjalan di `localhost:8000`
- [ ] Bisa login dengan admin atau user account

---

**Selamat mencoba! Jika ada masalah, cek troubleshooting di atas atau baca dokumentasi lengkap di README.md** 🚀
