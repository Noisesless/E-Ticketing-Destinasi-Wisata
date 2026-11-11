# Hollynice Ticket System - Banjarnegara

**Hollynice Ticket System** is a comprehensive E-Ticketing solution designed for tourism destinations in Banjarnegara. It supports tour packages, entrance tickets, and event bookings with a mobile-first approach for customers and robust management tools for administrators and operators.

## 🌟 Key Features

### 📱 For Customers (Mobile-First)
*   **Easy Booking**: Purchase tickets for tours, attractions, and events seamlessly.
*   **E-Tickets**: Receive QR code tickets directly on the device.
*   **Souvenir Shop**: Browse and order local souvenirs (Oleh-oleh) via WhatsApp integration.
*   **Multi-language Support**: Toggle between Indonesian (ID) and English (EN).
*   **PWA Support**: Installable as a web app for quick access.

### 🛠 For Administrators
*   **Dashboard**: Overview of sales and performance.
*   **Product Management**: Create and edit tours, events, and entrance tickets with rich details (itinerary, facilities, etc.).
*   **Sales Reports**: Track revenue and order history.

### 🎟 For Operators
*   **POS (Point of Sale)**: Sell tickets on-site.
*   **Ticket Scanning**: Validate visitor tickets using QR code scanning.

## 🚀 Technology Stack

*   **Backend**: Native PHP (MVC Structure)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, Tailwind CSS (via CDN), JavaScript
*   **Icons**: FontAwesome
*   **Other**: QRCode.js, Service Workers for PWA

## 📂 Project Structure

```
jan/
├── config/             # Database configuration
├── controllers/        # Business logic for Products, Cart, Auth, etc.
├── views/              # UI Templates
│   ├── admin/          # Admin dashboard and management views
│   ├── mobile/         # Mobile-optimized views (Home, Souvenir)
│   ├── desktop/        # Desktop views (Fallback/Full experience)
│   └── layouts/        # Header, Footer, etc.
├── img/                # Product images and assets
├── includes/           # Helper functions
├── index.php           # Main Router
├── install.php         # Database setup/seeder script
└── sw.js               # Service Worker for PWA
```

## ⚙️ Installation & Setup

1.  **Prerequisites**:
    *   Web Server (Apache/Nginx) - e.g., XAMPP, Laragon.
    *   PHP 7.4 or higher.
    *   MySQL Database.

2.  **Setup**:
    *   Clone or extract this project into your web root directory (e.g., `d:\xampp\htdocs\jan`).
    *   Open `config/db.php` and configure your database credentials if they differ from the defaults:
        ```php
        $host = 'localhost';
        $dbname = 'jan_tour'; // Ensure this matches your DB name
        $username = 'root';
        $password = '';
        ```

3.  **Initialize Database**:
    *   Open your browser and navigate to the installation script:
        ```
        http://localhost/jan/install.php
        ```
    *   This script will create the necessary tables (`users`, `products`, `orders`, etc.) and seed default data.

4.  **Ready to Run**:
    *   Access the main application:
        ```
        http://localhost/jan/
        ```

## 🔐 Default Credentials

The `install.php` script creates the following default users:

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `admin123` |
| **Operator** | `operator` | `operator123` |
| **Customer** | `user` | `user123` |

> **Note**: Please change these passwords immediately after deployment in a production environment.

## 📱 Mobile Simulation

To view the mobile interface on a desktop browser:
1.  Open Developer Tools (F12).
2.  Toggle the Device Toolbar (Ctrl+Shift+M).
3.  Select a mobile device (e.g., iPhone 12, Pixel 5).
4.  Refresh the page.

---
*Developed for Banjarnegara Tourism Promotion.*
