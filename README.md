# Hollynice Ticket System - Geopark Wonosobo

**Hollynice Ticket System** is an E-Ticketing and tourism promotion platform designed for volcanic geosites, natural attractions, and cultural destinations across Wonosobo Regency and the Dieng Plateau, Central Java. Built on Native PHP 8.2 with a modular MVC pattern and Tailwind CSS, it offers seamless mobile-first ticket booking for visitors alongside operational tools for operators, administrators, and regional revenue (PAD) monitoring for BPPKAD & Dinas Pariwisata.

---

## 🌟 Key Features

### 📱 For Tourists & Visitors (Mobile & Desktop)
* **Ticket Catalog**: Book tour packages, volcanic geosite entrance tickets (Kawah Sikidang, Kompleks Curug Merawu, Kawah Timbang, Sumur Jalatunda, Kawah Sileri, Telaga Dringo, Telaga Merdada, Gunung Pangonan), and cultural events.
* **E-Tickets & QR Codes**: Receive dynamic QR e-tickets on device for on-site scanning and instant redemption.
* **Tourism Directory**: Explore verified Accommodations (Hotels, Homestays, Villas), Culinary spots (Mie Ongklok, Carica, Purwaceng), and Transportation services (Travel, Rental, Jeep Wisata).
* **Souvenir Shop**: Browse local Wonosobo handicrafts and delicacies (Purwaceng, Kacang Dieng, Carica) with direct WhatsApp order integration.
* **Interactive GIS Map**: Explore administrative boundaries, destination points, culinary spots, accommodations, and transportation via OpenLayers v8.2 and GeoJSON.
* **Bilingual Support**: Toggle between Indonesian (ID) and English (EN) at any time.
* **Progressive Web App (PWA)**: Installable as an app with offline caching support via Service Workers.

### 🎟 For Operators & Loket
* **Point of Sale (POS)**: Issue and validate tickets directly at ticket counters.
* **Integrated POS Scanner**: Validate visitor tickets in real-time inside the unified admin dashboard using built-in camera scanning or manual code entry.

### 🛠 For Administrators & BPPKAD / Dinas Pariwisata
* **Admin Dashboard**: Comprehensive management of products, accommodation listings, culinary spots, transport providers, and souvenirs.
* **Photo Galleries**: Multi-image management with secure upload validation.
* **Regional Monitoring Dashboard**: Real-time revenue and visitor volume monitoring designed for BPPKAD & Dinas Pariwisata.
* **Automated PDF Export**: Generate official PAD monitoring reports (Daily, Monthly, Yearly) via bundled TCPDF 6.10 engine.

---

## 🚀 Technology Stack

* **Backend**: Native PHP 8.2.35 (Modular MVC Pattern, Anti-CVE Patched)
* **Database**: MySQL / MariaDB (InnoDB, PDO Prepared Statements)
* **Frontend**: HTML5, Tailwind CSS, FontAwesome 6, Chart.js
* **Typography**: *Plus Jakarta Sans* (Heading) + *Poppins* / *Inter* (Body)
* **Mapping / GIS**: OpenLayers v8.2 with GeoJSON (`peta/`, `peta_desa.geojson`)
* **PDF Engine**: TCPDF 6.10.1 (Official PHP 8.2 compatible)
* **Security Subsystem**: Environment variable isolation (`.env`), anti-CSRF tokens, MIME type validation via `finfo`, session fixation mitigation, and defensive HTTP security headers.

---

## 📂 Project Structure

```
├── .docs/                  # Comprehensive technical documentation (9 specs)
├── api/                    # JSON API endpoints (e.g. scan_ticket.php)
├── config/                 # Database configuration via PDO and .env
├── controllers/            # Business logic and request handlers
├── img/                    # Destination assets, products, and media (with img/produk/)
├── includes/               # Helper library (env, csrf, upload, trans, formatting)
├── peta/                   # Spatial GeoJSON datasets (boundaries & coordinates)
├── TCPDF/                  # Bundled TCPDF PDF generation library
├── views/                  # Presentation layer
│   ├── admin/              # Admin dashboard, POS, and management views
│   ├── auth/               # Login and registration templates
│   ├── desktop/            # Responsive desktop catalog and map views
│   ├── layouts/            # Global header, footer, and navigation
│   ├── mobile/             # Mobile-first views and bottom tab navigation
│   ├── monitor/            # BPPKAD Wonosobo monitoring dashboard
│   └── payment/            # Payment simulation screens
├── .env.example            # Environment variables template
├── index.php               # Main front controller and router
├── setup.php               # Idempotent master database setup runner
└── sw.js                   # Service worker for PWA
```

---

## ⚙️ Installation & Setup

### 1. Prerequisites
* PHP 8.2 or higher (with `pdo_mysql` and `fileinfo` enabled)
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
Execute the master setup runner (creates all 12 tables and seeds initial data):
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
| **Administrator** | `admin` | `admin123` | Full access to catalog, galleries, POS, and reports |
| **Operator** | `operator` | `operator123` | On-site counter POS and ticket QR verification |
| **Customer** | `user` | `user123` | Tourist booking, cart, and payment simulation |
| **Monitoring** | `dinas` | `password` | BPPKAD & Dinas transaction and revenue analytics |

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
*Developed for Wonosobo Geopark Tourism Promotion.*
