# 🚀 Panduan Deployment

Panduan lengkap untuk deploy project Toko Online MongoDB ke berbagai platform.

## 📋 Prasyarat Deployment

- ✅ MongoDB Atlas account (sudah setup)
- ✅ Hosting/VPS dengan PHP 7.4+ dan MongoDB extension
- ✅ Composer terinstall di server
- ✅ SSL Certificate (recommended untuk production)

---

## 🌐 Deploy ke Shared Hosting

### Platform yang Mendukung
- Hostinger
- Niagahoster  
- Rumahweb
- IDCloudHost

### Langkah Deployment

#### 1. Persiapan File
```bash
# Compress project folder
zip -r toko-online.zip . -x "vendor/*" -x ".git/*"
```

#### 2. Upload via cPanel/FTP
1. Login ke cPanel
2. Buka File Manager
3. Upload `toko-online.zip` ke folder `public_html`
4. Extract file

#### 3. Install Dependencies
Via Terminal SSH atau Terminal di cPanel:
```bash
cd public_html
composer install --no-dev --optimize-autoloader
```

#### 4. Setup Environment
```bash
cp .env.example .env
nano .env  # Edit dengan MongoDB Atlas credentials
```

#### 5. Set File Permissions
```bash
chmod 644 .env
chmod 755 admin/ auth/ user/ config/ assets/
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

#### 6. Setup Demo Data
```bash
php setup-demo-data.php
```

#### 7. Test
Akses domain Anda: `https://yourdomain.com`

---

## ☁️ Deploy ke VPS (DigitalOcean, AWS, Linode)

### Setup Server Ubuntu 22.04

#### 1. Install Prerequisites
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1 and extensions
sudo apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-mbstring php8.1-xml php8.1-curl

# Install MongoDB extension
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.1/cli/php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.1/fpm/php.ini

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Nginx
sudo apt install -y nginx

# Install Certbot for SSL
sudo apt install -y certbot python3-certbot-nginx
```

#### 2. Clone Project
```bash
cd /var/www
sudo git clone <your-repo-url> toko-online
cd toko-online
```

#### 3. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

#### 4. Setup Environment
```bash
cp .env.example .env
nano .env  # Edit MongoDB Atlas credentials
```

#### 5. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/toko-online
sudo chmod -R 755 /var/www/toko-online
sudo chmod 644 /var/www/toko-online/.env
```

#### 6. Configure Nginx

Create file: `/etc/nginx/sites-available/toko-online`

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/toko-online;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Block access to sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /composer\.(json|lock) {
        deny all;
    }

    location ~ /vendor/ {
        deny all;
    }

    location ~ /config/ {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/toko-online /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 7. Setup SSL with Let's Encrypt
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### 8. Setup Demo Data
```bash
cd /var/www/toko-online
php setup-demo-data.php
```

---

## 🐳 Deploy dengan Docker

### Dockerfile
Create `Dockerfile`:

```dockerfile
FROM php:8.1-fpm

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 9000

CMD ["php-fpm"]
```

### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    volumes:
      - .:/var/www
    networks:
      - toko-network

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - .:/var/www
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - toko-network

networks:
  toko-network:
    driver: bridge
```

### Deploy
```bash
docker-compose up -d
```

---

## 🆓 Deploy ke Platform Gratis

### 1. Railway.app

#### Setup
1. Buat akun di https://railway.app
2. Connect GitHub repository
3. Railway akan auto-detect PHP
4. Tambahkan environment variables di Railway dashboard:
   ```
   MONGO_URI=your-mongodb-atlas-uri
   DB_NAME=toko_online
   ```
5. Deploy otomatis setiap push ke GitHub

### 2. Render.com

#### Setup
1. Buat akun di https://render.com
2. Create new Web Service
3. Connect GitHub repository
4. Build command: `composer install`
5. Start command: `php -S 0.0.0.0:$PORT`
6. Tambahkan environment variables
7. Deploy

### 3. Heroku (dengan Buildpack)

```bash
# Install Heroku CLI
heroku login

# Create app
heroku create toko-online-app

# Add PHP buildpack
heroku buildpacks:add heroku/php

# Add MongoDB buildpack for extension
heroku buildpacks:add https://github.com/mongodb/heroku-buildpack-php-mongodb

# Set environment variables
heroku config:set MONGO_URI="your-mongodb-uri"
heroku config:set DB_NAME="toko_online"

# Deploy
git push heroku main

# Run setup
heroku run php setup-demo-data.php
```

---

## 🔒 Security Checklist untuk Production

- [ ] Change default admin password
- [ ] Use HTTPS (SSL Certificate)
- [ ] Set strong MongoDB passwords
- [ ] Restrict MongoDB Network Access to production IP only
- [ ] Set `display_errors = Off` in php.ini
- [ ] Enable error logging
- [ ] Restrict file permissions (644 for files, 755 for directories)
- [ ] Block access to `.env`, `composer.json`, `/vendor/`, `/config/`
- [ ] Enable firewall on VPS
- [ ] Regular backups of MongoDB data
- [ ] Keep PHP and dependencies updated
- [ ] Use rate limiting for login attempts
- [ ] Implement CSRF protection (future improvement)

---

## 📊 MongoDB Atlas Production Settings

### 1. Update Network Access
- Hapus "Allow from Anywhere"
- Tambahkan IP spesifik server production

### 2. Database User
- Gunakan password yang kuat
- Buat user terpisah untuk production dengan permission minimal

### 3. Enable Backup
- Di MongoDB Atlas, enable automated backups
- Set retention period sesuai kebutuhan

### 4. Monitoring
- Enable MongoDB monitoring alerts
- Set alerts untuk high CPU, memory usage

---

## 🔄 Update & Maintenance

### Update Code
```bash
# Pull latest changes
git pull origin main

# Install new dependencies
composer install --no-dev

# Clear any cache if added
php artisan cache:clear  # jika pakai cache

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Backup Database
```bash
# MongoDB Atlas has automatic backup
# Or use mongodump manually:
mongodump --uri="your-mongodb-uri" --out=/backup/$(date +%Y%m%d)
```

---

## 📞 Support & Troubleshooting

### Common Issues

**Issue: "Class 'MongoDB\Client' not found"**
```bash
# Install MongoDB extension
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.1/fpm/php.ini
sudo systemctl restart php8.1-fpm
```

**Issue: "Permission denied on .env"**
```bash
sudo chmod 644 .env
sudo chown www-data:www-data .env
```

**Issue: "Connection timeout to MongoDB"**
- Check MongoDB Atlas Network Access whitelist
- Verify connection string in .env
- Test connection: `php test-connection.php`

---

**Good luck with your deployment! 🚀**
