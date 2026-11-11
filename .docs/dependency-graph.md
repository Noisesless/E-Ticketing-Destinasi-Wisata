# Dependency Graph & Component Topology
**Project:** Hollynice Ticket System - Banjarnegara  
**Updated:** 2026-11-11  

---

## 1. System Dependency Topology

```mermaid
graph TD
    subgraph Environment
        ENV[.env Configuration]
    end

    subgraph Core Layer
        Functions[includes/functions.php]
        Config[config/db.php]
        Functions -.->|Load .env & Helpers| Config
        ENV -.->|Read DB & Keys| Functions
    end

    subgraph Entry Points
        Router[index.php Router]
        APIScanner[api/scan_ticket.php]
        SetupRunner[setup.php Runner]
    end

    Config --> Router
    Config --> APIScanner
    Config --> SetupRunner

    subgraph Controllers
        AuthCtrl[auth_controller.php]
        CartCtrl[cart_controller.php]
        CheckCtrl[checkout_controller.php]
        ProdCtrl[product_controller.php]
        SouvCtrl[souvenir_controller.php]
        AdminProd[admin_product_controller.php]
        AdminAccomm[admin_accommodation_controller.php]
        AdminCulinary[admin_culinary_controller.php]
        AdminSouv[admin_souvenir_controller.php]
        AdminTrans[admin_transportation_controller.php]
    end

    Router --> Controllers

    subgraph Presentation & Layouts
        Header[views/layouts/header.php]
        Footer[views/layouts/footer.php]
        BottomNav[views/mobile/bottom_nav.php]
        AdminLayout[views/layouts/admin_layout.php]
        DesktopViews[views/desktop/*.php]
        AdminViews[views/admin/*.php]
    end

    Controllers --> DesktopViews
    Controllers --> AdminViews
    DesktopViews --> Header
    DesktopViews --> Footer
    Footer --> BottomNav
    AdminViews --> AdminLayout

    subgraph External Client Libraries CDN
        Tailwind[Tailwind CSS CDN + Theme Config]
        FontAwesome[FontAwesome 6 Icons]
        GoogleFonts[Google Fonts: Plus Jakarta Sans & Poppins]
        QRCode[QRCode.js]
        ChartJS[Chart.js]
        Leaflet[Leaflet GIS & GeoJSON]
    end

    Header --> Tailwind
    Header --> FontAwesome
    Header --> GoogleFonts
    Header --> QRCode
    AdminLayout --> ChartJS
    DesktopViews --> Leaflet
```

---

## 2. External Dependencies Breakdown

| Library / Service | Version / Source | Purpose |
|---|---|---|
| **Tailwind CSS** | CDN Runtime v3.x | Utility-first styling with custom `tailwind.config` extending brand tokens |
| **Plus Jakarta Sans** | Google Fonts | Primary font for headings, badges, and prominent callouts |
| **Poppins** | Google Fonts | Body font for user interfaces, descriptions, and forms |
| **FontAwesome** | 6.0.0 CDN | Uniform iconography throughout user and administration interfaces |
| **QRCode.js** | 1.0.0 CDN | Client-side QR generation for customer e-tickets |
| **Chart.js** | CDN Latest | Graphical visualization for Admin & Dinas Monitoring dashboards |
| **Leaflet.js** | OpenStreetMap / GeoJSON | Geographic visualization of Banjarnegara destinations and village borders |
