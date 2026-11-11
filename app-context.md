# APP CONTEXT & SYSTEM SNAPSHOT
**Project:** Hollynice Ticket System - Banjarnegara  
**Stack:** Native PHP 8.1+ MVC, MySQL/MariaDB (PDO), Tailwind CSS CDN, FontAwesome, PWA  
**Updated:** 2026-11-11  

---

## §APP
- Architecture: Native PHP modular MVC
- Entry Point: `index.php` (Dispatches `?page=...`)
- Database: `hollynice_ticket_db` (10 tables verified via `setup.php`)
- Server: PHP Development Server on port 8080 (`http://localhost:8080`)
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
