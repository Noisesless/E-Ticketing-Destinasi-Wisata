# API Specification & Endpoints
**Project:** Hollynice Ticket System - Banjarnegara  
**Updated:** 2026-11-11  

---

## 1. Authentication & Security Headers

All JSON API endpoints enforce strict authentication and defense against unauthorized access:

* **Session Auth:** Operator or Admin session cookie (Cookie: `PHPSESSID=...`).
* **Token Auth (Bearer):** `Authorization: Bearer <API_SECRET_KEY>` where the key is defined in `.env`.

---

## 2. Endpoints

### 2.1 Validate & Scan Ticket
Validates an e-ticket barcode/QR code and marks it as used.

* **URL:** `/api/scan_ticket.php`
* **Method:** `POST`
* **Content-Type:** `application/json`
* **Authorization:** Required (Session or Bearer token)

#### Request Headers
```http
POST /api/scan_ticket.php HTTP/1.1
Host: localhost:8080
Content-Type: application/json
Authorization: Bearer <API_SECRET_KEY>
```

#### Request Body
```json
{
  "ticket_code": "TIX-6789ABCD1234"
}
```

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Tiket valid! Berhasil diverifikasi.",
  "data": {
    "ticket_code": "TIX-6789ABCD1234",
    "product_name": "Paket Arung Jeram Serayu",
    "visit_date": "2026-11-15",
    "used_at": "2026-11-11 10:00:00"
  }
}
```

#### Error Responses

* **`401 Unauthorized`** (Missing or invalid authorization):
```json
{
  "success": false,
  "message": "Unauthorized: Akses ditolak. Hanya operator atau API resmi yang diizinkan."
}
```

* **`200 OK` with Failure Reason** (Ticket not found, unpaid, or already used):
```json
{
  "success": false,
  "message": "Tiket sudah digunakan pada 10 Nov 2026 14:20."
}
```
```json
{
  "success": false,
  "message": "Tiket belum dibayar (Status Pesanan: pending)."
}
```

---

### 2.2 Operator POS Ticket Verification (Internal)
Internal ticket validation used by the point-of-sale terminal.

* **URL:** `/index.php?page=ticket`
* **Method:** `POST`
* **Content-Type:** `application/json`
* **Access Control:** Operator or Admin role required via session.
