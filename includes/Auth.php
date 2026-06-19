<?php
/**
 * Authentication Class
 * Handles login, registration, password recovery
 */

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register($name, $email, $password, $address = '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email'];
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password too short'];
        }
        $existing = $this->db->fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            return ['success' => false, 'message' => 'Email exists'];
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $userId = $this->db->insert('users', ['name' => $name, 'email' => $email, 'password' => $hashedPassword, 'address' => $address, 'role' => 'user', 'status' => 'active']);
        if ($userId) {
            return ['success' => true, 'message' => 'Registered', 'user_id' => $userId];
        }
        return ['success' => false, 'message' => 'Registration failed'];
    }

    public function login($email, $password) {
        $user = $this->db->fetch("SELECT * FROM users WHERE email = ? AND status = 'active'", [$email]);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], ['id' => $user['id']]);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public function logout() {
        session_destroy();
        return true;
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_role'] === 'admin';
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $this->db->fetch("SELECT id, name, email, role, avatar FROM users WHERE id = ?", [$_SESSION['user_id']]);
        }
        return null;
    }
}

$auth = new Auth();