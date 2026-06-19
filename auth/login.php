<?php
require_once '../config-example.php';

if ($auth->isLoggedIn()) {
    header('Location: /user/');
    exit();
}

$errors = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $email = Security::sanitize($_POST['email'] ?? '', 'email');
        $password = $_POST['password'] ?? '';

        if (!Security::checkRateLimit('login_' . $email)) {
            $errors[] = 'Too many login attempts. Please try again later.';
        } else {
            $result = $auth->login($email, $password);
            if ($result['success']) {
                header('Location: /user/');
                exit();
            } else {
                $errors[] = $result['message'];
            }
        }
    }

    if ($action === 'forgot') {
        $email = Security::sanitize($_POST['email'] ?? '', 'email');
        if (!empty($email)) {
            $success[] = 'Please wait. Our admin will contact you within 24 hours.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Oryzenx</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border-radius: 15px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card border-0">
                    <div class="card-body p-5">
                        <h3 class="card-title text-center mb-4">Login</h3>

                        <?php foreach ($errors as $error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($success as $msg): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msg); ?>
                            </div>
                        <?php endforeach; ?>

                        <form method="POST">
                            <input type="hidden" name="action" value="login">
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

                        <hr>
                        <p class="text-center text-muted mb-0">
                            Don't have an account? <a href="/auth/signup" class="text-decoration-none">Sign up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>