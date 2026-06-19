<?php
require_once '../config-example.php';

if (!$auth->isAdmin()) {
    header('Location: /admin/login');
    exit();
}

$db = Database::getInstance();
$action = $_GET['action'] ?? 'list';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Security::sanitize($_POST['name'] ?? '', 'string');
    $domainName = Security::sanitize($_POST['domain_name'] ?? '', 'string');
    $price = (float)($_POST['price'] ?? 0);
    $description = Security::sanitize($_POST['description'] ?? '', 'string');
    
    $db->insert('domains', [
        'domain_name' => $domainName,
        'extension' => explode('.', $domainName)[1] ?? 'com',
        'price' => $price,
        'description' => $description,
        'quality_badge' => 'standard',
        'status' => 'available'
    ]);
    
    header('Location: /admin/domains');
    exit();
}

$domains = $db->fetchAll("SELECT * FROM domains ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Domains - Oryzenx Admin</title>
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
                <li class="nav-item"><a class="nav-link active" href="/admin/domains"><i class="fas fa-globe"></i> Domains</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/offers"><i class="fas fa-handshake"></i> Offers</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/payments"><i class="fas fa-credit-card"></i> Payments</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/blog"><i class="fas fa-newspaper"></i> Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="flex-fill">
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Domains</h2>
                    <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Domain</a>
                </div>

                <?php if ($action === 'add'): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Add New Domain</h5>
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Domain Name</label>
                                        <input type="text" class="form-control" name="domain_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" step="0.01" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Domain</button>
                                <a href="/admin/domains" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Domain</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($domains as $domain): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($domain['domain_name']); ?></strong></td>
                                        <td>$<?php echo number_format($domain['price'], 2); ?></td>
                                        <td><span class="badge bg-info"><?php echo $domain['status']; ?></span></td>
                                        <td><?php echo $domain['featured'] ? '<i class="fas fa-star text-warning"></i>' : ''; ?></td>
                                        <td><?php echo Helper::timeAgo($domain['created_at']); ?></td>
                                        <td>
                                            <a href="?action=edit&id=<?php echo $domain['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <a href="?action=delete&id=<?php echo $domain['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this domain?')"><i class="fas fa-trash"></i></a>
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