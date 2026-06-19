<?php
/**
 * Helper Class
 * Utility functions
 */

class Helper {
    public static function getSetting($key, $default = null) {
        $db = Database::getInstance();
        $setting = $db->fetch("SELECT setting_value FROM site_settings WHERE setting_key = ?", [$key]);
        return $setting ? $setting['setting_value'] : $default;
    }

    public static function updateSetting($key, $value) {
        $db = Database::getInstance();
        $existing = $db->fetch("SELECT id FROM site_settings WHERE setting_key = ?", [$key]);
        if ($existing) {
            return $db->update('site_settings', ['setting_value' => $value], ['setting_key' => $key]);
        }
        return $db->insert('site_settings', ['setting_key' => $key, 'setting_value' => $value]);
    }

    public static function generateSlug($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s-]/', '', $text);
        $text = preg_replace('/[\s]+/', '-', $text);
        return trim($text, '-');
    }

    public static function timeAgo($datetime) {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('M d, Y', $time);
    }

    public static function paginate($total, $perPage = ITEMS_PER_PAGE) {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $totalPages = ceil($total / $perPage);
        return ['page' => $page, 'perPage' => $perPage, 'total' => $total, 'totalPages' => $totalPages, 'offset' => ($page - 1) * $perPage];
    }

    public static function logActivity($action, $description = '', $module = '') {
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'] ?? null;
        $db->insert('activity_logs', ['user_id' => $userId, 'action' => $action, 'description' => $description, 'module' => $module, 'ip_address' => Security::getClientIP(), 'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '']);
    }

    public static function sendNotification($userId, $title, $message, $type = 'info') {
        $db = Database::getInstance();
        return $db->insert('notifications', ['user_id' => $userId, 'title' => $title, 'message' => $message, 'type' => $type]);
    }

    public static function formatCurrency($amount, $currency = CURRENCY) {
        $symbols = ['USD' => '$', 'EUR' => 'EUR', 'GBP' => 'GBP', 'BDT' => 'Tk'];
        $symbol = $symbols[$currency] ?? $currency;
        return $symbol . number_format($amount, 2);
    }
}