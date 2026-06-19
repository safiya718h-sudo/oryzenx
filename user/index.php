<?php
require_once '../config-example.php';

if (!$auth->isLoggedIn()) {
    header('Location: /auth/login');
    exit();
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Oryzenx</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-lg">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-cube text-primary"></i> Oryzenx
            </a>
            <div class="ms-auto">
                <a href="/user/logout" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-lg py-5">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="<?php echo $user['avatar'] ?? '/assets/images/placeholder.png'; ?>" alt="Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5><?php echo htmlspecialchars($user['name']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        <a href="edit-profile" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav nav-tabs mb-4" role="tablist">
                    <button class="nav-link active" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers">
                        <i class="fas fa-handshake"></i> My Offers
                    </button>
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments">
                        <i class="fas fa-credit-card"></i> Payments
                    </button>
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications">
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="offers">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4">My Domain Offers</h5>
                                <div id="offersList"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="payments">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Payment History</h5>
                                <div id="paymentsList"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="notifications">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Notifications</h5>
                                <div id="notificationsList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>