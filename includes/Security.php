<?php
/**
 * Security Class
 * CSRF, XSS, SQL Injection Protection
 */

class Security {
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || time() - $_SESSION['csrf_token_time'] > CSRF_TOKEN_LIFETIME) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function sanitize($input, $type = 'string') {
        $input = trim($input);
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            default:
                return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
    }

    public static function escape($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    public static function checkRateLimit($endpoint) {
        $db = Database::getInstance();
        $ip = self::getClientIP();
        $limit = $db->fetch("SELECT attempts FROM rate_limits WHERE ip_address = ? AND endpoint = ? AND last_attempt > DATE_SUB(NOW(), INTERVAL 1 HOUR)", [$ip, $endpoint]);
        if ($limit && $limit['attempts'] >= RATE_LIMIT_ATTEMPTS) {
            return false;
        }
        if ($limit) {
            $db->update('rate_limits', ['attempts' => $limit['attempts'] + 1], ['ip_address' => $ip, 'endpoint' => $endpoint]);
        } else {
            $db->insert('rate_limits', ['ip_address' => $ip, 'endpoint' => $endpoint, 'attempts' => 1]);
        }
        return true;
    }
}