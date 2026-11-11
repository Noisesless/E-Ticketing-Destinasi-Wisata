# Deployment & Server Administration Guide
**Project:** Hollynice Ticket System - Banjarnegara  
**Updated:** 2026-11-11  

---

## 1. Prerequisites

* **PHP:** Version 8.1, 8.2, 8.3, or 8.4 with extensions: `pdo_mysql`, `fileinfo`, `mbstring`, `openssl`, `json`.
* **Database:** MySQL 5.7+ or MariaDB 10.3+.
* **Web Server:** Apache (with `mod_rewrite`), Nginx, or PHP CLI Built-in Server.

---

## 2. Local Development Setup (Quick Start)

1. **Clone or Extract Project:**
   ```bash
   cd /path/to/E-Ticketing-Destinasi-Wisata-main
   ```

2. **Configure Environment:**
   Copy the example environment configuration:
   ```bash
   cp .env.example .env
   ```
   Adjust database credentials in `.env` if different from standard defaults:
   ```ini
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=hollynice_ticket_db
   DB_USER=root
   DB_PASS=
   APP_ENV=development
   APP_KEY=your_secure_random_key_here
   API_SECRET_KEY=your_scanner_secret_key_here
   ```

3. **Initialize Database:**
   Run the idempotent master setup script via command line:
   ```bash
   php setup.php
   ```

4. **Launch Development Server:**
   ```bash
   php -S localhost:8080
   ```
   Open `http://localhost:8080` in your web browser.

---

## 3. Production Deployment (cPanel / Shared Hosting)

1. **Upload Files:**
   * Upload all project files to `public_html` (or subfolder/subdomain root).
   * Ensure `.env` is uploaded and contains the hosting database credentials.

2. **Database Configuration in cPanel:**
   * Create a MySQL Database (e.g. `u12345_hollynice`).
   * Create a MySQL User and associate it with the database granting `ALL PRIVILEGES`.
   * Fill `.env` with the cPanel database details:
     ```ini
     DB_HOST=localhost
     DB_NAME=u12345_hollynice
     DB_USER=u12345_dbuser
     DB_PASS=StrongPassword123!
     APP_ENV=production
     ```

3. **Execute Setup via CLI or Browser:**
   * Via SSH: `php setup.php`
   * Or access `https://yourdomain.com/setup.php` once, then delete or protect `setup.php`.

4. **Directory Permissions & Upload Security:**
   * Ensure `img/` and `img/produk/` have write permissions (`755`).
   * Place an `.htaccess` inside `img/` to disable PHP script execution:
     ```apache
     <FilesMatch "\.(php|phtml|php3|php4|php5|php7|phps)$">
         Order Allow,Deny
         Deny from all
     </FilesMatch>
     ```

---

## 4. Production Deployment (VPS / Ubuntu / Nginx)

### Example Nginx Server Block
```nginx
server {
    listen 80;
    server_name ticketing.banjarnegarakab.go.id;
    root /var/www/hollynice-ticket;
    index index.php index.html;

    # Protect .env and hidden files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Prevent script execution in upload directory
    location ~* /img/.*\.php$ {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
```
