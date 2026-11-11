# Issue Tracker & Resolution Log
**Project:** Hollynice Ticket System - Banjarnegara  
**Updated:** 2026-11-11  

---

## 1. Resolved Issues

### [ISS-001] Session Initialization Failure on Non-C Drives
* **Severity:** High
* **Symptom:** `session_start(): open(\xampp\tmp\sess_..., O_RDWR) failed: No such file or directory (2)` on Windows drive `D:`.
* **Root Cause:** Default `php.ini` referenced `\xampp\tmp`, which resolved to non-existent `D:\xampp\tmp`.
* **Resolution:** Added robust dynamic save path fallback in [`includes/functions.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/includes/functions.php) checking `C:/xampp/tmp` and `sys_get_temp_dir()`.
* **Status:** ✅ CLOSED

### [ISS-002] Hardcoded Database Credentials in Repository
* **Severity:** Critical (OWASP A05 / CWE-798)
* **Symptom:** Database host, username, and password hardcoded in `config/db.php`.
* **Resolution:** Implemented inline `.env` parser (`load_env()` / `env()`) in [`includes/functions.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/includes/functions.php), created `.env` and `.env.example`, and added `.env` to `.gitignore`.
* **Status:** ✅ CLOSED

### [ISS-003] Publicly Exposed Ticket Validation Endpoint
* **Severity:** Critical (OWASP A01 / CWE-285)
* **Symptom:** `api/scan_ticket.php` allowed unauthenticated users to validate and mark tickets as used.
* **Resolution:** Implemented dual-guard authorization requiring either an active Operator session or `Authorization: Bearer <API_SECRET_KEY>`. Unauthorized calls receive `HTTP 401`.
* **Status:** ✅ CLOSED

### [ISS-004] Insecure Direct File Upload in Admin Controllers
* **Severity:** Critical (OWASP A03 / CWE-434)
* **Symptom:** `uploadImage()` in admin product, accommodation, culinary, souvenir, and transportation controllers did not validate MIME types or extensions, allowing potential `.php` upload.
* **Resolution:** Created `secure_upload_image()` with PHP `finfo` MIME validation, extension whitelisting (`jpg`, `jpeg`, `png`, `webp`, `gif`), and cryptographically secure random filename hashing.
* **Status:** ✅ CLOSED

### [ISS-005] Missing CSRF Protection on Forms & State-Changing Links
* **Severity:** High (OWASP A01 / CWE-352)
* **Symptom:** Login, registration, checkout, and admin CRUD forms lacked CSRF tokens; delete actions were exposed as unprotected GET links.
* **Resolution:** Added `csrf_token()`, `csrf_field()`, `verify_csrf()`, and `verify_csrf_token()` across all forms, controllers, and delete links.
* **Status:** ✅ CLOSED

### [ISS-006] Desktop Inconsistency on Souvenir Page
* **Severity:** Medium (UI/UX Inconsistency)
* **Symptom:** `index.php?page=souvenir` looked like an isolated mobile app without navigation bar or footer on desktop.
* **Root Cause:** Controller was hardcoded to `require 'views/mobile/souvenir.php'`.
* **Resolution:** Created [`views/desktop/souvenir_list.php`](file:///d:/Project/E-Ticketing-Destinasi-Wisata-main/views/desktop/souvenir_list.php) matching the visual DNA of culinary, accommodation, and transportation pages, and updated controller to route correctly.
* **Status:** ✅ CLOSED

---

## 2. Open / Backlog Items

| ID | Title | Priority | Target |
|---|---|---|---|
| **ISS-007** | Automated Image Thumbnail Generation on Upload | Low | Future Enhancement |
| **ISS-008** | Real Payment Gateway Integration (Midtrans / Xendit) | Medium | Post-Launch |
| **ISS-009** | Export Sales Reports to PDF / Excel for Dinas | Low | Operational Feature |
| **ISS-010** | Parse Error Unmatched '}' in admin_transportation_controller.php | High | Immediate Fix |

---

## 3. Active Incident
### [ISS-010] Parse Error Unmatched '}' in admin_transportation_controller.php
* **Severity:** High (PHP Syntax Error)
* **Symptom:** `Parse error: Unmatched '}' in controllers/admin_transportation_controller.php on line 89`
* **Root Cause:** Redundant closing brace and duplicate redirect statement at lines 87-88 under `action == 'edit'`.
* **Resolution:** Removed lines 87-88 so the `elseif ($action == 'edit')` block closes cleanly. Ran syntax lint on all PHP files (0 errors).
* **Status:** ✅ CLOSED


