<?php
/**
 * Oryzenx Configuration Template
 * Copy this to config.php and fill in your details
 * Auto-generated during installation
 */

// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'oryzenx');

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_NAME', 'Oryzenx');
define('SITE_URL', 'http://localhost');
define('SITE_EMAIL', 'admin@oryzenx.com');
define('CURRENCY', 'USD');

// ============================================
// SECURITY KEYS
// ============================================
define('HASH_KEY', bin2hex(random_bytes(32)));
define('JWT_SECRET', bin2hex(random_bytes(32)));
define('CSRF_TOKEN_KEY', bin2hex(random_bytes(16)));

// ============================================
// PATHS
// ============================================
define('ADMIN_PATH', '/admin');
define('API_PATH', '/api');
define('UPLOAD_BASE_PATH', '/uploads');

// ============================================
// UPLOAD CONFIGURATION
// ============================================
define('UPLOAD_DIR', dirname(__FILE__) . '/uploads');
define('UPLOADS_BLOG', UPLOAD_DIR . '/blog');
define('UPLOADS_DOMAINS', UPLOAD_DIR . '/domains');
define('UPLOADS_PAYMENTS', UPLOAD_DIR . '/payments');
define('UPLOADS_AVATARS', UPLOAD_DIR . '/avatars');
define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx']);
define('IMAGE_QUALITY', 85);
define('IMAGE_MAX_WIDTH', 2000);
define('IMAGE_MAX_HEIGHT', 2000);

// ============================================
// SECURITY SETTINGS
// ============================================
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour
define('SESSION_LIFETIME', 86400 * 30); // 30 days
define('PASSWORD_RESET_LIFETIME', 3600); // 1 hour
define('RATE_LIMIT_ATTEMPTS', 5);
define('RATE_LIMIT_WINDOW', 3600); // 1 hour
define('BCRYPT_COST', 12);
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_SECURE', isset($_SERVER['HTTPS']) ? true : false);
define('SESSION_HTTPONLY', true);
define('SESSION_SAMESITE', 'Strict');

// ============================================
// CRYPTO WALLETS
// ============================================
define('WALLET_USDT_BEP20', '0x79395cbf73a98c48bfa53480d16cd5b428b5aff9');
define('WALLET_TRX', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV');
define('WALLET_TRC20', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV');

// ============================================
// PAGINATION & LIMITS
// ============================================
define('ITEMS_PER_PAGE', 20);
define('ADMIN_ITEMS_PER_PAGE', 50);
define('MIN_OFFER_PRICE', 150);
define('DEFAULT_LIMIT', 100);

// ============================================
// LOGGING & DEBUGGING
// ============================================
define('DEBUG_MODE', false); // Set to true only for development
define('LOG_ERRORS', true);
define('LOG_QUERIES', false);
define('LOG_DIR', dirname(__FILE__) . '/logs');
define('ERROR_LOG_FILE', LOG_DIR . '/errors.log');
define('ACTIVITY_LOG_FILE', LOG_DIR . '/activity.log');
define('SECURITY_LOG_FILE', LOG_DIR . '/security.log');
define('QUERY_LOG_FILE', LOG_DIR . '/queries.log');

// ============================================
// EMAIL CONFIGURATION
// ============================================
define('SMTP_ENABLED', false);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_FROM_NAME', 'Oryzenx');
define('SMTP_FROM_EMAIL', 'noreply@oryzenx.com');

// ============================================
// EXTERNAL SERVICES
// ============================================
define('GOOGLE_ANALYTICS_ID', '');
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// ============================================
// CORS SETTINGS
// ============================================
define('CORS_ENABLED', false);
define('CORS_ORIGINS', ['https://oryzenx.com', 'https://www.oryzenx.com']);

// ============================================
// API SETTINGS
// ============================================
define('API_VERSION', '1.0');
define('API_TOKEN_LIFETIME', 86400 * 7); // 7 days
define('API_RATE_LIMIT', 1000); // per hour

// ============================================
// TIMEZONE
// ============================================
date_default_timezone_set('UTC');

// ============================================
// LOCALE
// ============================================
define('DEFAULT_LANGUAGE', 'en');
define('SUPPORTED_LANGUAGES', ['en', 'bn']);

// ============================================
// INCLUDE CORE FILES
// ============================================
require_once dirname(__FILE__) . '/includes/Database.php';
require_once dirname(__FILE__) . '/includes/Auth.php';
require_once dirname(__FILE__) . '/includes/Helper.php';
require_once dirname(__FILE__) . '/includes/Security.php';
require_once dirname(__FILE__) . '/includes/Validator.php';

// ============================================
// SESSION INITIALIZATION
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_secure' => SESSION_SECURE,
        'cookie_httponly' => SESSION_HTTPONLY,
        'cookie_samesite' => SESSION_SAMESITE,
        'gc_maxlifetime' => SESSION_LIFETIME
    ]);
    
    // Regenerate session ID for security
    if (!isset($_SESSION['_session_created'])) {
        session_regenerate_id(true);
        $_SESSION['_session_created'] = time();
    }
}

// ============================================
// ERROR HANDLING
// ============================================
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

if (LOG_ERRORS) {
    ini_set('log_errors', 1);
    ini_set('error_log', ERROR_LOG_FILE);
}

// ============================================
// CREATE LOG DIRECTORY IF NOT EXISTS
// ============================================
if (!is_dir(LOG_DIR)) {
    @mkdir(LOG_DIR, 0755, true);
}

// ============================================
// ENVIRONMENT CHECK
// ============================================
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('PHP 8.0 or higher is required. Current version: ' . PHP_VERSION);
}

// Check required extensions
$required_extensions = ['pdo', 'gd', 'curl', 'json', 'openssl'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        die("Required PHP extension '$ext' is not installed.");
    }
}
