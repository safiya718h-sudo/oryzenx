<?php
require_once '../config-example.php';

if (!$auth->isAdmin()) {
    header('Location: /admin/login');
    exit();
}

session_destroy();
header('Location: /admin/login');
exit();