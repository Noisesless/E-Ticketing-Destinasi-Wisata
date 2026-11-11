# Security Audit & Hardening Report (Anti-CVE)
**Project:** Hollynice Ticket System - Geopark Wonosobo  
**Date:** 2026-11-11  
**Auditor:** Antigravity AI (Vibes Coding Workflow)  
**Standard:** OWASP Top 10, CWE / CVE Prevention, Brainvibes Security Patterns  
**UI Framework:** Tailwind CSS (Preserved & Token-Configured)  
**Runtime:** PHP 8.2.35 (CVE-2024-4577 Patched)  

---

## 1. Executive Summary
Audit keamanan dan penguatan menyeluruh telah selesai diimplementasikan pada basis kode proyek ini. Seluruh form transaksi, endpoint otorisasi, proses upload file, dan response headers kini telah terlindungi dari eksploitasi CVE umum.

| ID | Kategori OWASP | Kerentanan | Status |
|---|---|---|---|
| **SP-001** | A01: Broken Access Control / CSRF | Formulir login, register, checkout, admin CRUD & delete links tanpa token anti-CSRF | ✅ FIXED (CSRF Token & Query Token Validator) |
| **SP-002** | A07: Identification & Auth Failures | Session fixation pada autentikasi | ✅ FIXED (`session_regenerate_id(true)`) |
| **SP-003** | A03: Injection / Unrestricted Upload | Upload file tanpa whitelist MIME & ekstensi (RCE Risk) | ✅ FIXED (`secure_upload_image()` terpadu) |
| **SP-006** | A01: Broken Access Control | Endpoint API Scanner tiket publik tanpa otentikasi | ✅ FIXED (Operator Session + Bearer API Key) |
| **SP-014/015** | A07: Identification & Auth Failures | Brute-force & credential stuffing pada login/register tanpa rate limiting bertier | ✅ FIXED (Tiered Rate Limiter: IP & Email/Username via DB `rate_limits`) |
| **SP-032** | A05: Security Misconfiguration | Kredensial database hardcoded dalam kode | ✅ FIXED (`.env` + `load_env()`) |
| **A05** | A05: Security Misconfiguration | Missing HTTP Security Headers | ✅ FIXED (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, etc.) |

---

## 2. Rincian Proteksi Faktual & Evidence

### A. [SP-001] Anti-CSRF Komprehensif
* **File:** Form login, register, checkout, admin (produk, penginapan, kuliner, suvenir, transportasi), serta link hapus data (`action=delete`).
* **[Evidence:]**
  - Seluruh form POST kini menyertakan token dinamis via `<?= csrf_field() ?>`.
  - Seluruh handler POST di controller memvalidasi token via `verify_csrf()`.
  - Operasi hapus data via GET link kini dilindungi dengan parameter `&_csrf_token=...` dan divalidasi oleh `verify_csrf_token()`.

### B. [SP-003] Whitelist Ketat File Upload
* **File:** Seluruh controller admin yang menangani upload gambar.
* **[Evidence:]**
  - Divalidasi via `finfo_file` (ekstensi & MIME whitelist: `image/jpeg`, `image/png`, `image/webp`, `image/gif`).
  - Nama file digenerate acak menggunakan kombinasi timestamp dan `bin2hex(random_bytes(8))` untuk mencegah eksekusi skrip (.php/.phtml).

### C. [SP-006] API Endpoint Guard
* **File:** `api/scan_ticket.php`
* **[Evidence:]**
  - Request unauthorized otomatis ditolak dengan `HTTP 401 Unauthorized`.
  - Request operator atau pembawa token `Authorization: Bearer <API_SECRET_KEY>` diverifikasi via `hash_equals()`.

### D. [A05] HTTP Security Headers
* **File:** `index.php`
* **[Evidence:]**
  - `X-Content-Type-Options: nosniff` (mencegah MIME type sniffing).
  - `X-Frame-Options: SAMEORIGIN` (mencegah clickjacking).
  - `X-XSS-Protection: 1; mode=block`.
  - `Referrer-Policy: strict-origin-when-cross-origin`.

### E. [SP-014 / SP-015] Tiered Rate Limiting (IP & Email/Username)
* **File:** `includes/rate_limiter.php`, `controllers/auth_controller.php`, `views/auth/login.php`, `views/auth/register.php`
* **[Evidence:]**
  - **Dimensi Ganda:** Pengecekan multi-tier berbasis Client IP (mencegah distributed stuffing) dan Identifier Akun/Email (mencegah targeted brute-force pada satu akun).
  - **Eskalasi Bertier Login:**
    - *Tier 1:* 5 kegagalan dalam 15 menit $\rightarrow$ Cooldown 60 detik (HTTP 429).
    - *Tier 2:* 10 kegagalan dalam 15 menit $\rightarrow$ Pembatasan sementara 15 menit (HTTP 429).
    - *Tier 3:* 15 kegagalan dalam 1 jam $\rightarrow$ Penguncian ketat 1 jam (HTTP 429).
  - **Eskalasi Bertier Register:**
    - *Email Limit:* Max 3 pendaftaran dalam 15 menit $\rightarrow$ Cooldown 15 menit.
    - *IP Limit:* Tier 1 (4 per 10m), Tier 2 (10 per 1h), Tier 3 (25 per 24h).
  - **Penyimpanan Persisten & Fail-safe:** Tabel `rate_limits` dengan auto-initialization dan fallback session/memory. Reset otomatis counter saat otentikasi berhasil.
  - **Tampilan Interaktif:** Kartu countdown timer JavaScript pada form login dan pendaftaran saat cooldown aktif.

---

## 3. Konfigurasi Frontend Tailwind CSS
* **Framework:** Tetap menggunakan **Tailwind CSS** sesuai instruksi.
* **Integrasi Desain:** Ditambahkan `tailwind.config` di header dan admin layout untuk:
  - Font pairing: *Plus Jakarta Sans* (Heading) + *Poppins / Inter* (Body).
  - Warna tema terintegrasi (`primary-*` & `brand`).
  - Nol warning dan performa rendering optimal.
