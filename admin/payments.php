<?php
require_once '../config-example.php';

if (!$auth->isAdmin()) {
    header('Location: /admin/login');
    exit();
}

$db = Database::getInstance();
$payments = $db->fetchAll("SELECT p.*, u.name, u.email FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Oryzenx Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <nav class="navbar navbar-dark bg-dark flex-column sidebar">
            <a class="navbar-brand" href="/admin/"><i class="fas fa-cube"></i> Oryzenx Admin</a>
            <ul class="nav flex-column w-100">
                <li class="nav-item"><a class="nav-link" href="/admin/"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/users"><i class="fas fa-users"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/domains"><i class="fas fa-globe"></i> Domains</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/offers"><i class="fas fa-handshake"></i> Offers</a></li>
                <li class="nav-item"><a class="nav-link active" href="/admin/payments"><i class="fas fa-credit-card"></i> Payments</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="flex-fill">
            <div class="container-fluid p-4">
                <h2 class="mb-4">Payment Management</h2>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Wallet</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td>#<?php echo $payment['id']; ?></td>
                                        <td><?php echo htmlspecialchars($payment['name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($payment['email']); ?></small></td>
                                        <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $payment['wallet_type']; ?></span></td>
                                        <td>
                                            <?php if ($payment['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($payment['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo Helper::timeAgo($payment['created_at']); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                        </td>
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