# InternConnect — Production & Staging Deployment Guide

**Target Environment:** Ubuntu 22.04 / 24.04 LTS  
**Application Stack:** PHP 8.3-FPM, MySQL 8.0+ / MariaDB 10.6+, Nginx / Apache  
**Document Status:** Production Ready  

---

## 1. System Requirements & Prerequisites

### 1.1 Operating System & Hardware
- **OS:** Ubuntu 22.04 LTS or 24.04 LTS
- **CPU:** Minimum 2 vCPU (Recommended 4 vCPU for peak application matching)
- **RAM:** Minimum 4 GB (Recommended 8 GB)
- **Storage:** Minimum 40 GB NVMe SSD with automated weekly snapshots

### 1.2 Software Packages
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common curl git unzip ufw

# Add PHP 8.3 repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.3 and critical extensions
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-sqlite3 \
    php8.3-curl php8.3-gd php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath

# Install MySQL 8.0 Server
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

## 2. Environment Variables Configuration (`.env`)

Create the production `.env` configuration file in the project root:

```ini
# Application Configuration
APP_NAME=InternConnect
APP_ENV=production
APP_KEY=base64:ProductionEncryptedSecretKeyGeneratedDuringDeploy==
APP_DEBUG=false
APP_URL=https://internconnect.ng

# Database (MySQL 8.0+)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=internconnect_prod
DB_USERNAME=internconnect_app
DB_PASSWORD=SecureGeneratedPasswordChangeMe!

# Daily.co In-Platform Video Calling
DAILY_API_KEY=your_daily_co_production_api_key_here
DAILY_DOMAIN=internconnect

# AI Assistance (DeepSeek V3 / R1)
DEEPSEEK_API_KEY=sk-production-deepseek-api-key-here
DEEPSEEK_MODEL=deepseek-chat
DEEPSEEK_MAX_TOKENS=1500

# Payment Gateway (Paystack)
PAYSTACK_PUBLIC_KEY=pk_live_your_paystack_public_key
PAYSTACK_SECRET_KEY=sk_live_your_paystack_secret_key
PAYSTACK_PAYMENT_URL=https://api.paystack.co

# Google OAuth 2.0
GOOGLE_CLIENT_ID=your_google_oauth_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your_google_client_secret
GOOGLE_REDIRECT_URI=https://internconnect.ng/auth/google/callback

# Mail & Notifications (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@internconnect.ng
MAIL_PASSWORD=smtp_secret_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notifications@internconnect.ng
MAIL_FROM_NAME="InternConnect Platform"

# Session & Storage
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
STORAGE_PATH=/var/www/internconnect/storage
```

---

## 3. Database Provisioning & Migration

### 3.1 Create Database & Dedicated User
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE IF NOT EXISTS `internconnect_prod` 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'internconnect_app'@'localhost' IDENTIFIED BY 'SecureGeneratedPasswordChangeMe!';

GRANT ALL PRIVILEGES ON `internconnect_prod`.* TO 'internconnect_app'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3.2 Execute Master DDL Schema
Run the schema definitions documented in [`docs/48-mysql-backend-architecture-and-schema.md`](file:///c:/xampp/htdocs/InternConnect/docs/48-mysql-backend-architecture-and-schema.md):

```bash
mysql -u internconnect_app -p internconnect_prod < database/schema_mysql.sql
```

---

## 4. Web Server Configuration

### 4.1 Nginx Server Block (Recommended for Staging/Production)
Create `/etc/nginx/sites-available/internconnect`:

```nginx
server {
    listen 80;
    server_name internconnect.ng www.internconnect.ng;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name internconnect.ng www.internconnect.ng;

    root /var/www/internconnect/public;
    index index.php index.html;

    # SSL Configuration (Certbot)
    ssl_certificate /etc/letsencrypt/live/internconnect.ng/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/internconnect.ng/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # WebRTC / Daily.co camera & microphone permissions
    add_header Permissions-Policy "camera=(self 'https://*.daily.co'), microphone=(self 'https://*.daily.co'), display-capture=(self 'https://*.daily.co')";

    # Maximum file upload size for resumes & verification documents
    client_max_body_size 25M;

    # Static Assets Caching
    location /assets/ {
        alias /var/www/internconnect/public/assets/;
        expires 30d;
        access_log off;
        add_header Cache-Control "public, no-transform";
    }

    # Clean URL Rewriting to Front Controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM Execution
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ ^/(storage/logs|database|\.env) {
        deny all;
        return 404;
    }
}
```

Enable the site and reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/internconnect /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 5. SSL / TLS Certificate (Mandatory for In-Platform Daily.co Video)

> [!IMPORTANT]
> The browser WebRTC APIs (`navigator.mediaDevices.getUserMedia`) strictly refuse to operate on insecure origins (`http://`). A verified SSL certificate from Let's Encrypt is required for the In-Platform Video Interview Room.

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d internconnect.ng -d www.internconnect.ng
```

---

## 6. Permissions & File Storage

```bash
sudo chown -R www-data:www-data /var/www/internconnect
sudo find /var/www/internconnect -type f -exec chmod 644 {} \;
sudo find /var/www/internconnect -type d -exec chmod 755 {} \;

# Writable directories for document uploads, session caching, and logs
sudo chmod -R 775 /var/www/internconnect/storage
sudo chmod -R 775 /var/www/internconnect/public/assets/uploads
```

---

## 7. Production Automated Testing & Health Check

Run the regression test suite on the staging server before enabling live DNS traffic:

```bash
cd /var/www/internconnect
php tests/run_tests.php
```

All 53 unit and integration tests must report `[PASS]` before marking deployment as verified.
