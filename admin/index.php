<?php
require_once '../config-example.php';

if (!$auth->isAdmin()) {
    header('Location: /admin/login');
    exit();
}

$db = Database::getInstance();
$totalUsers = $db->count('users');
$totalDomains = $db->count('domains');
$totalOffers = $db->count('domain_offers');
$totalPayments = $db->count('payments', ['status' => 'pending']);
$recentOffers = $db->fetchAll("SELECT * FROM domain_offers ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Oryzenx</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="navbar navbar-dark bg-dark flex-column sidebar">
            <a class="navbar-brand" href="/admin/">
                <i class="fas fa-cube"></i> Oryzenx Admin
            </a>
            <ul class="nav flex-column w-100">
                <li class="nav-item">
                    <a class="nav-link active" href="/admin/"><i class="fas fa-chart-pie"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/users"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/domains"><i class="fas fa-globe"></i> Domains</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/offers"><i class="fas fa-handshake"></i> Offers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/payments"><i class="fas fa-credit-card"></i> Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/blog"><i class="fas fa-newspaper"></i> Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/contacts"><i class="fas fa-envelope"></i> Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/notifications"><i class="fas fa-bell"></i> Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/seo"><i class="fas fa-search"></i> SEO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/settings"><i class="fas fa-cog"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="flex-fill">
            <div class="container-fluid p-4">
                <h2 class="mb-4">Dashboard</h2>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Users</h6>
                                <h3 class="text-primary"><?php echo $totalUsers; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Domains</h6>
                                <h3 class="text-success"><?php echo $totalDomains; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Pending Offers</h6>
                                <h3 class="text-warning"><?php echo $totalOffers; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Pending Payments</h6>
                                <h3 class="text-danger"><?php echo $totalPayments; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Recent Offers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Domain</th>
                                        <th>User</th>
                                        <th>Offer Price</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOffers as $offer): ?>
                                    <tr>
                                        <td><strong>#<?php echo $offer['id']; ?></strong></td>
                                        <td><?php echo $offer['user_id']; ?></td>
                                        <td>$<?php echo number_format($offer['offer_price'], 2); ?></td>
                                        <td><span class="badge bg-info"><?php echo $offer['status']; ?></span></td>
                                        <td><?php echo Helper::timeAgo($offer['created_at']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>