# Application Routes & Controller Map
**Project:** Hollynice Ticket System - Banjarnegara  
**Router:** [`index.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/index.php)  
**Updated:** 2026-11-11  

---

## 1. Route Dispatch Table

All client requests route through `index.php?page=<route>`.

| Route (`?page=`) | Controller / Action | Auth Required | Description |
|---|---|---|---|
| `home` (default) | `views/desktop/home.php` / `views/mobile/home.php` | Public | Landing page, hero, product catalog, quick booking |
| `product` | [`controllers/product_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/product_controller.php) | Public | Product detail, itinerary, facilities, and add-to-cart |
| `accommodation` | [`controllers/accommodation_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/accommodation_controller.php) | Public | List of hotels, homestays, and villas with filters |
| `accommodation_detail` | [`controllers/accommodation_detail_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/accommodation_detail_controller.php) | Public | Accommodation details and facility breakdown |
| `culinary` | [`controllers/culinary_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/culinary_controller.php) | Public | Culinary spots catalog and regional recommendations |
| `souvenir` | [`controllers/souvenir_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/souvenir_controller.php) | Public | Oleh-oleh & souvenirs catalog with WhatsApp CTA |
| `transportation` | [`controllers/transportation_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/transportation_controller.php) | Public | Travel, rental, and pickup transportation directory |
| `transportation_detail` | [`controllers/transportation_detail_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/transportation_detail_controller.php) | Public | Detailed specifications of transport service |
| `map` | [`controllers/map_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/map_controller.php) | Public | Interactive Leaflet GIS map with GeoJSON village borders |
| `destination_detail` | [`controllers/destination_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/destination_controller.php) | Public | Detailed destination guide (Kawah Sikidang, Candi Arjuna) |
| `cart` | [`controllers/cart_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/cart_controller.php) | Logged In | User shopping cart and ticket date selector |
| `checkout` | [`controllers/checkout_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/checkout_controller.php) | Logged In | Order creation and payment method selection (CSRF protected) |
| `payment` | [`controllers/payment_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/payment_controller.php) | Logged In | Payment simulation (QRIS / Bank Jateng VA) |
| `login` | [`controllers/auth_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/auth_controller.php) | Guest / Public | User authentication with CSRF & anti-fixation |
| `register` | [`controllers/auth_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/auth_controller.php) | Guest / Public | Account creation (CSRF protected) |
| `logout` | `session_destroy()` | Logged In | Session invalidation and redirect to home |
| `switch_lang` | Language switcher (`?lang=id` / `?lang=en`) | Public | Dynamic interface language switcher |

---

## 2. Administration & Operational Routes

| Route (`?page=`) | Controller / Action | Required Role | Description |
|---|---|---|---|
| `admin` | `views/admin/dashboard.php` | `admin` | Administrator analytics, metrics, and summary |
| `admin_products` | [`controllers/admin_product_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/admin_product_controller.php) | `admin` | Product CRUD & secure gallery photo upload |
| `admin_accommodation`| [`controllers/admin_accommodation_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/admin_accommodation_controller.php) | `admin` | Accommodation CRUD with secure image upload |
| `admin_culinary` | [`controllers/admin_culinary_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/admin_culinary_controller.php) | `admin` | Culinary spots CRUD with secure image upload |
| `admin_souvenir` | [`controllers/admin_souvenir_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/admin_souvenir_controller.php) | `admin` | Souvenir product CRUD with secure image upload |
| `admin_transportation`| [`controllers/admin_transportation_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/admin_transportation_controller.php) | `admin` | Transport services CRUD with secure image upload |
| `operator` | `views/admin/pos.php` | `operator` / `admin` | Operator POS terminal and live QR ticket validator |
| `ticket` | [`controllers/ticket_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/ticket_controller.php) | `operator` / `admin` | POS ticket validation endpoint (JSON) |
| `monitor` | [`controllers/monitor_controller.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/controllers/monitor_controller.php) | `monitor` / `admin` | Dinas Pariwisata revenue monitoring dashboard |

---

## 3. Informational & Legal Pages

| Route (`?page=`) | View File | Description |
|---|---|---|
| `contacts` | `views/desktop/contacts.php` | Official contacts, hotline, and office location |
| `how_to_buy` | `views/desktop/how_to_buy.php` | Step-by-step visitor booking guide |
| `disclaimer` | `views/desktop/disclaimer.php` | Legal terms and development notices |
| `privacy_policy` | `views/desktop/privacy_policy.php` | Data handling and visitor privacy statement |

