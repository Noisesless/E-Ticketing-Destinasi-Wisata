# APP CONTEXT & SYSTEM SNAPSHOT
**Project:** Hollynice Ticket System - Geopark Wonosobo  
**Stack:** Native PHP 8.2.35 (Anti-CVE Patched), MySQL/MariaDB (PDO), Tailwind CSS CDN, FontAwesome, PWA, TCPDF 6.10  
**Updated:** 2026-11-11  

---

## §APP
- Architecture: Native PHP modular MVC
- PHP Runtime: PHP 8.2.35 (C:\xampp\php) - Fully patched against CVE-2024-4577
- Entry Point: `index.php` (Dispatches `?page=...`)
- Database: `hollynice_ticket_db` (12 tables verified via `setup.php` & synchronized with `dump_geopark.sql`)
- Server: PHP Development Server on port 8080 (`http://localhost:8080`) & Apache2.4 on port 80
- PDF Engine: TCPDF 6.10.1 (BPPKAD Regional Revenue / PAD monitoring reports)
- Spatial / GIS: OpenLayers v8.2 + GeoJSON (boundaries & multi-category pins)
- Security: .env configured, CSRF protection on all forms & delete actions, secure uploads with MIME whitelisting, session fixation mitigation, protected ticket scanner API, defensive HTTP security headers

## §SECURITY
- SP-001 (CSRF): Active (`csrf_field()`, `verify_csrf()`, `verify_csrf_token()`)
- SP-002 (Session Fixation): Active (`session_regenerate_id(true)`)
- SP-003 (Secure Upload): Active (`secure_upload_image()` with finfo MIME/ext whitelist)
- SP-006 (API Authorization): Active (Operator session + Bearer API Key)
- SP-032 (Environment Isolation): Active (`.env` + `load_env()`)
- A05 (Security Headers): Active (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`)

## §USERS_DEFAULT
- Admin: `admin` / `admin123`
- Operator: `operator` / `operator123`
- Customer: `user` / `user123`
- Monitor: `dinas` / `password`

## §DOCUMENTATION_STATUS
- All 9 core specs generated in `/.docs/`:
  - `architecture.md`, `api-spec.md`, `database.md`, `routes.md`, `dependency-graph.md`, `issues.md`, `quality_review.md`, `deployment-guide.md`, `security-audit.md`
- Public-safe `README.md` synchronized.
- Git repository tracking active.
- Admin Panel: POS Scanner (`page=operator`) integrated cleanly into `admin_layout.php` with sidebar, active tab indicator, and manual code input.
