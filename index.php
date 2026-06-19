<?php
require_once 'config-example.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oryzenx - Premium Domain Marketplace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-lg">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-cube text-primary"></i> Oryzenx
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#domains">Domains</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="notifBtn">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger" id="notifCount"></span>
                        </a>
                    </li>
                    <?php if ($auth->isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="/user/">Profile</a></li>
                                <li><a class="dropdown-item" href="/user/offers">My Offers</a></li>
                                <li><a class="dropdown-item" href="/user/payments">Payments</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/user/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary btn-sm ms-2" href="/auth/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="/auth/signup">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section bg-gradient py-5">
        <div class="container-lg">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold text-white mb-4">Premium Domain Marketplace</h1>
                    <p class="text-white-50 mb-4">Buy and sell premium domains with confidence. Find the perfect domain for your business.</p>
                    <div class="d-flex gap-3">
                        <a href="#domains" class="btn btn-light btn-lg">
                            <i class="fas fa-search"></i> Browse Domains
                        </a>
                        <a href="/services" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-briefcase"></i> Our Services
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <i class="fas fa-globe fa-10x text-primary opacity-10"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Domains -->
    <section id="domains" class="py-5 bg-light">
        <div class="container-lg">
            <h2 class="text-center mb-5">Featured Domains</h2>
            <div class="row" id="domainsList"></div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-5">
        <div class="container-lg">
            <h2 class="text-center mb-5">Latest Blog Posts</h2>
            <div class="row" id="blogList"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5 bg-light">
        <div class="container-lg">
            <h2 class="text-center mb-5">Our Services</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-globe fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Domain Trading</h5>
                            <p class="card-text">Buy, sell, and trade premium domains</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-code fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Web Development</h5>
                            <p class="card-text">Custom web solutions for your business</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-palette fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">UI/UX Design</h5>
                            <p class="card-text">Beautiful and functional design</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5><i class="fas fa-cube"></i> Oryzenx</h5>
                    <p class="text-muted">Premium domain marketplace and digital services platform</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Domains</a></li>
                        <li><a href="/blog" class="text-muted text-decoration-none">Blog</a></li>
                        <li><a href="/services" class="text-muted text-decoration-none">Services</a></li>
                        <li><a href="/contact" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p class="text-muted">
                        Email: info@oryzenx.com<br>
                        Support: support@oryzenx.com
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <p>&copy; 2026 Oryzenx. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>