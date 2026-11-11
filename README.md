# Hollynice Ticket System - Banjarnegara

**Hollynice Ticket System** is an E-Ticketing and tourism promotion platform designed for destinations across Banjarnegara Regency, Central Java. Built on Native PHP with a modular MVC pattern and Tailwind CSS, it offers seamless mobile-first ticket booking for visitors alongside operational tools for operators, administrators, and regional tourism monitoring.

---

## 🌟 Key Features

### 📱 For Tourists & Visitors (Mobile & Desktop)
* **Ticket Catalog**: Book tour packages (Arung Jeram Serayu, Rafting), entrance tickets (Candi Arjuna, Kawah Sikidang, Desa Wisata Blambangan), and cultural events.
* **E-Tickets & QR Codes**: Receive dynamic QR e-tickets on device for on-site scanning and redemption.
* **Tourism Directory**: Explore verified Accommodations (Hotels, Homestays, Villas), Legendary Culinary spots (Dawet Ayu, Mie Ongklok), and Transportation services (Travel, Rental, Ojek Wisata).
* **Souvenir Shop**: Browse local Banjarnegara handicrafts and delicacies with direct WhatsApp order integration.
* **Interactive GIS Map**: Explore village borders and tourist destinations via Leaflet and GeoJSON maps.
* **Bilingual Support**: Toggle between Indonesian (ID) and English (EN) at any time.
* **Progressive Web App (PWA)**: Installable as an app with offline caching support via Service Workers.

### 🎟 For Operators & Loket
* **Point of Sale (POS)**: Issue and print tickets directly at ticket counters.
* **Ticket Scanner**: Validate visitor tickets in real-time using built-in camera scanning or barcode reader.

### 🛠 For Administrators & Dinas Pariwisata
* **Admin Dashboard**: Comprehensive management of products, accommodation listings, culinary spots, transport providers, and souvenirs.
* **Photo Galleries**: Multi-image management with secure upload validation.
* **Regional Monitoring Dashboard**: Revenue and visitor volume monitoring designed for Dinas Pariwisata & DPPKAD.

---

## 🚀 Technology Stack

* **Backend**: Native PHP 8.1+ (Modular MVC Pattern)
* **Database**: MySQL / MariaDB (PDO Prepared Statements)
* **Frontend**: HTML5, Tailwind CSS, FontAwesome 6, Chart.js
* **Typography**: *Plus Jakarta Sans* (Heading) + *Poppins* / *Inter* (Body)
* **Mapping**: Leaflet GIS with GeoJSON (`peta_desa.geojson`)
* **Security Subsystem**: Environment variable isolation (`.env`), anti-CSRF tokens, MIME type validation, session fixation mitigation, and defensive HTTP security headers.

---

## 📂 Project Structure

```
├── .docs/                  # Comprehensive technical documentation (9 specs)
├── api/                    # JSON API endpoints (e.g. scan_ticket.php)
├── config/                 # Database configuration via PDO and .env
├── controllers/            # Business logic and request handlers
├── img/                    # Destination assets, products, and media
├── includes/               # Helper library (env, csrf, upload, trans, formatting)
├── views/                  # Presentation layer
│   ├── admin/              # Admin dashboard, POS, and management views
│   ├── auth/               # Login and registration templates
│   ├── desktop/            # Responsive desktop catalog views
│   ├── layouts/            # Global header, footer, and navigation
│   ├── mobile/             # Mobile-first views and bottom tab navigation
│   ├── monitor/            # Dinas Pariwisata monitoring dashboard
│   └── payment/            # Payment simulation screens
├── .env.example            # Environment variables template
├── index.php               # Main front controller and router
├── setup.php               # Idempotent master database setup runner
└── sw.js                   # Service worker for PWA
```

---

## ⚙️ Installation & Setup

### 1. Prerequisites
* PHP 8.1 or higher (with `pdo_mysql` and `fileinfo` enabled)
* MySQL / MariaDB Server
* Apache, Nginx, or PHP CLI Development Server

### 2. Configuration
Copy the sample environment file to `.env`:
```bash
cp .env.example .env
```
Update `.env` with your database credentials and secret keys:
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

### 3. Initialize Database
Execute the master setup runner (creates all 10 tables and seeds initial data):
```bash
php setup.php
```

### 4. Run Development Server
```bash
php -S localhost:8080
```
Open `http://localhost:8080` in your web browser.

---

## 🔐 Default Demo Roles

The `setup.php` runner creates the following roles for local testing:

| Role | Username | Password | Purpose |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Full access to catalog, galleries, and reports |
| **Operator** | `operator` | `operator123` | On-site counter POS and ticket QR verification |
| **Customer** | `user` | `user123` | Tourist booking, cart, and payment simulation |
| **Monitoring** | `dinas` | `password` | Dinas Pariwisata transaction and revenue analytics |

> **Security Notice**: Change all default passwords and secret keys immediately before deploying to a production server.

---

## 📖 Technical Documentation

For detailed technical specifications, refer to the [`.docs/`](./.docs/) directory:
* [Architecture & Design Pattern](./.docs/architecture.md)
* [API Specification](./.docs/api-spec.md)
* [Database Schema & ERD](./.docs/database.md)
* [Routes & Controller Mapping](./.docs/routes.md)
* [Dependency Graph](./.docs/dependency-graph.md)
* [Issues & Bug Resolution Log](./.docs/issues.md)
* [Quality & Standards Review](./.docs/quality_review.md)
* [Deployment Guide](./.docs/deployment-guide.md)
* [Security & Anti-CVE Audit Report](./.docs/security-audit.md)

---
*Developed for Banjarnegara Tourism Promotion.*
