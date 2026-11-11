<?php
// HTTP Security Headers (Anti-CVE / OWASP A05)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

require_once 'config/db.php';
require_once 'includes/functions.php';

$page = $_GET['page'] ?? 'home';

// Language Switcher
if ($page == 'switch_lang') {
    $_SESSION['lang'] = $_GET['lang'] ?? 'id';
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// Mobile Detection (Simple)
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// Router Logic
switch ($page) {
    case 'home':
        if (isMobileDevice() && !isset($_GET['desktop_mode'])) {
            require 'views/mobile/home.php';
        } else {
            require 'views/desktop/home.php';
        }
        break;
    
    case 'product':
        require 'controllers/product_controller.php';
        break;

    case 'cart':
        require 'controllers/cart_controller.php';
        break;

    case 'checkout':
        require 'controllers/checkout_controller.php';
        break;

    case 'payment':
        require 'controllers/payment_controller.php';
        break;

    case 'login':
        require 'controllers/auth_controller.php';
        break;
        
    case 'register':
        require 'controllers/auth_controller.php';
        break;

    case 'admin_transportation':
        require 'controllers/admin_transportation_controller.php';
        break;

    case 'transportation_detail':
        require 'controllers/transportation_detail_controller.php';
        break;

    case 'logout':
        session_destroy();
        redirect('index.php');
        break;

    case 'admin':
        if (!isAdmin()) redirect('index.php?page=login');
        require 'views/admin/dashboard.php';
        break;

    case 'admin_accommodation':
        require 'controllers/admin_accommodation_controller.php';
        break;

    case 'admin_products':
        require 'controllers/admin_product_controller.php';
        break;

    case 'admin_culinary':
        require 'controllers/admin_culinary_controller.php';
        break;

    case 'admin_souvenir':
        require 'controllers/admin_souvenir_controller.php';
        break;

    case 'operator':
        if (!isOperator()) redirect('index.php?page=login');
        require 'views/admin/pos.php';
        break;

    case 'monitor':
        if (!isMonitor()) redirect('index.php?page=login');
        require 'controllers/monitor_controller.php';
        break;

    case 'accommodation':
        require 'controllers/accommodation_controller.php';
        break;

    case 'accommodation_detail':
        require 'controllers/accommodation_detail_controller.php';
        break;

    case 'culinary':
        require 'controllers/culinary_controller.php';
        break;

    case 'ticket':
        if (!isLoggedIn()) redirect('index.php?page=login');
        require 'views/desktop/ticket.php'; // Responsive
        break;
        
    case 'api_ticket_scan':
        // API endpoint for scanner
        require 'controllers/ticket_controller.php';
        break;

    case 'verify_ticket':
        if (!isLoggedIn()) redirect('index.php?page=login');
        require 'views/desktop/ticket.php';
        break;

    case 'how_to_buy':
        require 'views/desktop/how_to_buy.php';
        break;

    case 'contacts':
        require 'views/desktop/contacts.php';
        break;

    case 'souvenir':
        require 'controllers/souvenir_controller.php';
        break;

    case 'transportation':
        require 'controllers/transportation_controller.php';
        break;

    case 'map':
        require 'controllers/map_controller.php';
        break;

    case 'destination':
        require 'controllers/destination_controller.php';
        break;

    case 'disclaimer':
        require 'views/desktop/disclaimer.php';
        break;

    case 'privacy_policy':
        require 'views/desktop/privacy_policy.php';
        break;

    default:
        echo "404 Not Found";
        break;
}
