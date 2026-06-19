<?php
require_once '../config-example.php';

if ($auth->isLoggedIn()) {
    header('Location: /admin/');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Security::sanitize($_POST['email'] ?? '', 'email');
    $password = $_POST['password'] ?? '';

    if (!Security::checkRateLimit('admin_login')) {
        $errors[] = 'Too many attempts. Try again later.';
    } else {
        $result = $auth->login($email, $password);
        if ($result['success'] && $auth->isAdmin()) {
            header('Location: /admin/');
            exit();
        } else {
            $errors[] = 'Invalid admin credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Oryzenx</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border-radius: 15px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2><i class="fas fa-cube text-primary"></i></h2>
                            <h3>Admin Login</h3>
                        </div>

                        <?php foreach ($errors as $error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endforeach; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>