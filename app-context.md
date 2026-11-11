# APP CONTEXT & SYSTEM SNAPSHOT
**Project:** Hollynice Ticket System - Banjarnegara  
**Stack:** Native PHP 8.1 MVC, MySQL/MariaDB (PDO), Tailwind CSS CDN, FontAwesome, PWA  
**Updated:** 2026-11-11  

---

## §APP
- Architecture: Native PHP lightweight MVC
- Entry Point: `index.php` (Routing query `?page=...`)
- Database: `hollynice_ticket_db`
- Server: PHP Development Server on port 8080 (`http://localhost:8080`)
- Security: .env configured, CSRF protection, secure file uploads, session fixation mitigation, protected ticket scanner API

## §SECURITY
- SP-001 (CSRF): Active (`csrf_field()`, `verify_csrf()`)
- SP-002 (Session Fixation): Active (`session_regenerate_id(true)`)
- SP-003 (Secure Upload): Active (`secure_upload_image()` with MIME/ext whitelist)
- SP-006 (API Authorization): Active (Operator session + Bearer API Key)
- SP-032 (Environment Isolation): Active (`.env` + `load_env()`)

## §USERS_DEFAULT
- Admin: `admin` / `admin123`
- Operator: `operator` / `operator123`
- Customer: `user` / `user123`
- Monitor: `dinas` / `password`

## §NEXT
- Evaluasi opsi migrasi ke Laravel vs Hardening Native PHP
- Refactoring frontend CDN ke asset bundling/tokens

