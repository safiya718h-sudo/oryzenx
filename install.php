<?php
/**
 * Oryzenx Installation Wizard
 * Complete setup for production deployment
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$installDir = dirname(__FILE__);
$configFile = $installDir . '/config.php';
$installed = file_exists($configFile);

if ($installed) {
    header('Location: index.php');
    exit();
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];
$success = [];

// Check PHP Version
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    $errors[] = 'PHP 8.0 or higher is required. Current version: ' . PHP_VERSION;
}

// Check Required Extensions
$requiredExtensions = ['pdo', 'gd', 'curl', 'json', 'openssl'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    $errors[] = 'Missing PHP Extensions: ' . implode(', ', $missingExtensions);
}

// Check write permissions
if (!is_writable($installDir)) {
    $errors[] = 'Installation directory is not writable. CHMOD to 755';
}

// Create directories if writable
if (is_writable($installDir)) {
    $dirs = [
        'admin',
        'api',
        'user',
        'includes',
        'assets/css',
        'assets/js',
        'assets/fonts',
        'assets/images',
        'uploads/blog',
        'uploads/domains',
        'uploads/payments',
        'uploads/avatars',
        'logs'
    ];
    
    foreach ($dirs as $dir) {
        $dirPath = $installDir . '/' . $dir;
        if (!is_dir($dirPath)) {
            @mkdir($dirPath, 0755, true);
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'database') {
        $dbHost = trim($_POST['db_host'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = trim($_POST['db_pass'] ?? '');
        $dbName = trim($_POST['db_name'] ?? '');
        
        if (empty($dbHost) || empty($dbUser) || empty($dbName)) {
            $errors[] = 'All database fields are required';
        } else {
            try {
                $pdo = new PDO(
                    "mysql:host=$dbHost",
                    $dbUser,
                    $dbPass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_THROW]
                );
                
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `$dbName`");
                
                $success[] = 'Database connected successfully';
                $step = 2;
                
                $_SESSION['db_host'] = $dbHost;
                $_SESSION['db_user'] = $dbUser;
                $_SESSION['db_pass'] = $dbPass;
                $_SESSION['db_name'] = $dbName;
                
            } catch (PDOException $e) {
                $errors[] = 'Database connection failed: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'admin_account') {
        $adminName = trim($_POST['admin_name'] ?? '');
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPassword = trim($_POST['admin_password'] ?? '');
        $adminConfirm = trim($_POST['admin_confirm'] ?? '');
        
        if (empty($adminName) || empty($adminEmail) || empty($adminPassword)) {
            $errors[] = 'All admin fields are required';
        } elseif ($adminPassword !== $adminConfirm) {
            $errors[] = 'Passwords do not match';
        } elseif (strlen($adminPassword) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        } else {
            $_SESSION['admin_name'] = $adminName;
            $_SESSION['admin_email'] = $adminEmail;
            $_SESSION['admin_password'] = password_hash($adminPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            $step = 3;
            $success[] = 'Admin account configured';
        }
    }
    
    if ($action === 'site_settings') {
        $siteName = trim($_POST['site_name'] ?? 'Oryzenx');
        $siteUrl = trim($_POST['site_url'] ?? '');
        $siteEmail = trim($_POST['site_email'] ?? '');
        $currency = $_POST['currency'] ?? 'USD';
        
        if (empty($siteUrl) || empty($siteEmail)) {
            $errors[] = 'Site URL and Email are required';
        } else {
            $_SESSION['site_name'] = $siteName;
            $_SESSION['site_url'] = rtrim($siteUrl, '/');
            $_SESSION['site_email'] = $siteEmail;
            $_SESSION['currency'] = $currency;
            $step = 4;
            $success[] = 'Site settings configured';
        }
    }
    
    if ($action === 'finalize') {
        $configContent = "<?php\n/**\n * Oryzenx Configuration File\n * Generated: " . date('Y-m-d H:i:s') . "\n * DO NOT EDIT MANUALLY\n */\n\ndefine('DB_HOST', '" . addslashes($_SESSION['db_host']) . "');\ndefine('DB_USER', '" . addslashes($_SESSION['db_user']) . "');\ndefine('DB_PASS', '" . addslashes($_SESSION['db_pass']) . "');\ndefine('DB_NAME', '" . addslashes($_SESSION['db_name']) . "');\n\ndefine('SITE_NAME', '" . addslashes($_SESSION['site_name']) . "');\ndefine('SITE_URL', '" . addslashes($_SESSION['site_url']) . "');\ndefine('SITE_EMAIL', '" . addslashes($_SESSION['site_email']) . "');\ndefine('CURRENCY', '" . addslashes($_SESSION['currency']) . "');\n\ndefine('HASH_KEY', '" . bin2hex(random_bytes(32)) . "');\ndefine('JWT_SECRET', '" . bin2hex(random_bytes(32)) . "');\n\ndefine('ADMIN_PATH', '/admin');\ndefine('API_PATH', '/api');\ndefine('UPLOAD_DIR', dirname(__FILE__) . '/uploads');\ndefine('MAX_UPLOAD_SIZE', 50 * 1024 * 1024);\ndefine('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);\n\ndefine('CSRF_TOKEN_LIFETIME', 3600);\ndefine('SESSION_LIFETIME', 86400 * 30);\ndefine('PASSWORD_RESET_LIFETIME', 3600);\ndefine('RATE_LIMIT_ATTEMPTS', 5);\ndefine('RATE_LIMIT_WINDOW', 3600);\n\ndefine('WALLET_USDT_BEP20', '0x79395cbf73a98c48bfa53480d16cd5b428b5aff9');\ndefine('WALLET_TRX', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV');\ndefine('WALLET_TRC20', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV');\n\ndefine('ITEMS_PER_PAGE', 20);\ndefine('MIN_OFFER_PRICE', 150);\ndefine('DEBUG_MODE', false);\ndefine('LOG_ERRORS', true);\n\nrequire_once dirname(__FILE__) . '/includes/Database.php';\nrequire_once dirname(__FILE__) . '/includes/Auth.php';\nrequire_once dirname(__FILE__) . '/includes/Helper.php';\n";
        
        if (file_put_contents($configFile, $configContent)) {
            $success[] = 'Configuration file created';
            
            try {
                $pdo = new PDO(
                    "mysql:host=" . $_SESSION['db_host'],
                    $_SESSION['db_user'],
                    $_SESSION['db_pass'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_THROW]
                );
                
                $pdo->exec("USE `" . $_SESSION['db_name'] . "`");
                
                $sqlFile = $installDir . '/database.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    $statements = array_filter(array_map('trim', explode(';', $sql)), function($s) {
                        return !empty($s) && strpos($s, '--') !== 0;
                    });
                    
                    foreach ($statements as $stmt) {
                        if (!empty(trim($stmt))) {
                            $pdo->exec($stmt . ';');
                        }
                    }
                    $success[] = 'Database tables created';
                }
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $_SESSION['admin_name'],
                    $_SESSION['admin_email'],
                    $_SESSION['admin_password'],
                    'admin',
                    'active'
                ]);
                
                $success[] = 'Admin account created';
                $step = 5;
                
            } catch (Exception $e) {
                $errors[] = 'Database setup failed: ' . $e->getMessage();
            }
        } else {
            $errors[] = 'Failed to create configuration file';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oryzenx Installation Wizard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            margin: 20px;
        }
        .install-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .install-header h1 {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 10px;
        }
        .step-item {
            flex: 1;
            text-align: center;
        }
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #666;
            font-weight: bold;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }
        .step-item.active .step-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .step-item.completed .step-number {
            background: #28a745;
            color: white;
        }
        .step-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .success-message {
            text-align: center;
            padding: 40px 20px;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1><i class="fas fa-cube"></i> Oryzenx</h1>
            <p>Installation Wizard v1.0</p>
        </div>

        <div class="progress-steps">
            <div class="step-item <?php echo $step >= 1 ? 'active' : ''; ?> <?php echo $step > 1 ? 'completed' : ''; ?>">
                <div class="step-number">1</div>
                <div class="step-label">Check</div>
            </div>
            <div class="step-item <?php echo $step >= 2 ? 'active' : ''; ?> <?php echo $step > 2 ? 'completed' : ''; ?>">
                <div class="step-number">2</div>
                <div class="step-label">Database</div>
            </div>
            <div class="step-item <?php echo $step >= 3 ? 'active' : ''; ?> <?php echo $step > 3 ? 'completed' : ''; ?>">
                <div class="step-number">3</div>
                <div class="step-label">Admin</div>
            </div>
            <div class="step-item <?php echo $step >= 4 ? 'active' : ''; ?> <?php echo $step > 4 ? 'completed' : ''; ?>">
                <div class="step-number">4</div>
                <div class="step-label">Settings</div>
            </div>
            <div class="step-item <?php echo $step >= 5 ? 'active' : ''; ?> <?php echo $step > 5 ? 'completed' : ''; ?>">
                <div class="step-number">5</div>
                <div class="step-label">Done</div>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <?php foreach ($success as $msg): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($step === 1): ?>
            <div>
                <h3 class="mb-4">System Requirements</h3>
                <div class="mb-2">
                    <i class="fas <?php echo version_compare(PHP_VERSION, '8.0.0', '>=') ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                    PHP Version: <?php echo PHP_VERSION; ?>
                </div>
                <div class="mb-2">
                    <i class="fas <?php echo extension_loaded('pdo') ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                    PDO Extension
                </div>
                <div class="mb-2">
                    <i class="fas <?php echo extension_loaded('gd') ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                    GD Library
                </div>
                <div class="mb-2">
                    <i class="fas <?php echo extension_loaded('curl') ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                    cURL Extension
                </div>
                
                <?php if (empty($errors)): ?>
                    <div class="mt-4">
                        <a href="install.php?step=2" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right"></i> Next
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($step === 2): ?>
            <form method="POST">
                <input type="hidden" name="action" value="database">
                <h3 class="mb-4">Database Configuration</h3>
                <div class="mb-3">
                    <label class="form-label">Database Host</label>
                    <input type="text" name="db_host" class="form-control" value="localhost" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Database User</label>
                    <input type="text" name="db_user" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Database Password</label>
                    <input type="password" name="db_pass" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Database Name</label>
                    <input type="text" name="db_name" class="form-control" value="oryzenx" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        Test Connection <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($step === 3): ?>
            <form method="POST">
                <input type="hidden" name="action" value="admin_account">
                <h3 class="mb-4">Admin Account</h3>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="admin_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="admin_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="admin_password" class="form-control" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="admin_confirm" class="form-control" minlength="8" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        Continue <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($step === 4): ?>
            <form method="POST">
                <input type="hidden" name="action" value="site_settings">
                <h3 class="mb-4">Site Settings</h3>
                <div class="mb-3">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="Oryzenx" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Site URL</label>
                    <input type="url" name="site_url" class="form-control" placeholder="https://example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="email" name="site_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-control" required>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="BDT">BDT</option>
                        <option value="INR">INR</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        Install <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($step === 5): ?>
            <div class="success-message">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="text-success">Installation Complete!</h2>
                <p>Your Oryzenx website is ready to use.</p>
                <div class="mt-4">
                    <a href="/admin/login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Go to Admin Panel
                    </a>
                    <a href="/" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Go to Homepage
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>