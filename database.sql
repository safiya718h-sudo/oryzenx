-- Oryzenx Database Schema
-- Complete production-ready schema with all tables and relationships

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `address` TEXT,
  `avatar` VARCHAR(255),
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `status` ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'active',
  `email_verified` TINYINT(1) DEFAULT 0,
  `email_token` VARCHAR(255),
  `last_login` TIMESTAMP NULL,
  `remember_token` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_role (role),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DOMAINS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `domains` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `domain_name` VARCHAR(255) UNIQUE NOT NULL,
  `extension` VARCHAR(10) NOT NULL,
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `asking_price` DECIMAL(15,2),
  `offer_price` DECIMAL(15,2),
  `rating` DECIMAL(3,2) DEFAULT 0,
  `quality_badge` ENUM('standard', 'premium', 'luxury') DEFAULT 'standard',
  `description` TEXT,
  `image` VARCHAR(255),
  `featured` TINYINT(1) DEFAULT 0,
  `featured_until` TIMESTAMP NULL,
  `traffic` INT DEFAULT 0,
  `authority_score` INT DEFAULT 0,
  `backlinks` INT DEFAULT 0,
  `keywords` TEXT,
  `history` TEXT,
  `status` ENUM('available', 'sold', 'pending', 'offer_review') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_price (price),
  INDEX idx_featured (featured),
  INDEX idx_status (status),
  FULLTEXT idx_search (domain_name, description, keywords)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DOMAIN OFFERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `domain_offers` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `domain_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `offer_price` DECIMAL(15,2) NOT NULL,
  `status` ENUM('pending', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending',
  `message` TEXT,
  `contact_email` VARCHAR(100),
  `contact_phone` VARCHAR(20),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_domain (domain_id),
  INDEX idx_user (user_id),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOG CATEGORIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT,
  `icon` VARCHAR(255),
  `color` VARCHAR(7) DEFAULT '#667eea',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOG POSTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `content` LONGTEXT NOT NULL,
  `excerpt` TEXT,
  `featured_image` VARCHAR(255),
  `category_id` INT UNSIGNED,
  `author_id` INT UNSIGNED NOT NULL,
  `language` ENUM('en', 'bn') DEFAULT 'en',
  `status` ENUM('draft', 'scheduled', 'published', 'archived') DEFAULT 'draft',
  `published_at` TIMESTAMP NULL,
  `scheduled_at` TIMESTAMP NULL,
  `views` INT DEFAULT 0,
  `likes` INT DEFAULT 0,
  `loves` INT DEFAULT 0,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `reading_time` INT DEFAULT 5,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_slug (slug),
  INDEX idx_status (status),
  INDEX idx_published (published_at),
  INDEX idx_category (category_id),
  FULLTEXT idx_search (title, content, excerpt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOG REACTIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `blog_reactions` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `post_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `reaction_type` ENUM('like', 'love') DEFAULT 'like',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_reaction (post_id, user_id),
  INDEX idx_post (post_id),
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PAYMENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'USDT',
  `wallet_type` ENUM('USDT_BEP20', 'TRX', 'TRC20') DEFAULT 'USDT_BEP20',
  `wallet_address` VARCHAR(255) NOT NULL,
  `transaction_hash` VARCHAR(255),
  `proof_image` VARCHAR(255),
  `status` ENUM('pending', 'approved', 'rejected', 'refunded') DEFAULT 'pending',
  `reference_id` VARCHAR(100),
  `reference_type` VARCHAR(50),
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_status (status),
  INDEX idx_reference (reference_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- NOTIFICATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('info', 'success', 'warning', 'error', 'domain', 'blog', 'payment') DEFAULT 'info',
  `icon` VARCHAR(100),
  `link` VARCHAR(255),
  `read` TINYINT(1) DEFAULT 0,
  `read_at` TIMESTAMP NULL,
  `send_to_all` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_read (read),
  INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CONTACT MESSAGES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `subject` VARCHAR(255),
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied', 'resolved') DEFAULT 'new',
  `replied_at` TIMESTAMP NULL,
  `admin_notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PASSWORD RESETS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `password_resets` {
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(255) UNIQUE NOT NULL,
  `status` ENUM('pending', 'used', 'expired') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_token (token),
  INDEX idx_status (status)
} ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PARTNERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `partners` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `logo` VARCHAR(255) NOT NULL,
  `website` VARCHAR(255),
  `description` TEXT,
  `order_by` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_order (order_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SITE SETTINGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `setting_key` VARCHAR(100) UNIQUE NOT NULL,
  `setting_value` LONGTEXT,
  `type` ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ACTIVITY LOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `module` VARCHAR(100),
  `reference_id` INT,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_user (user_id),
  INDEX idx_action (action),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SECURITY LOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `security_logs` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `event_type` ENUM('login_attempt', 'failed_login', 'csrf_blocked', 'xss_attempt', 'sql_injection', 'rate_limit', 'suspicious') DEFAULT 'suspicious',
  `user_id` INT UNSIGNED,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255),
  `details` JSON,
  `severity` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_event (event_type),
  INDEX idx_severity (severity),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USER SESSIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `session_token` VARCHAR(255) UNIQUE NOT NULL,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_token (session_token),
  INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEO SETTINGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `page` VARCHAR(100) UNIQUE NOT NULL,
  `meta_title` VARCHAR(255),
  `meta_description` VARCHAR(255),
  `meta_keywords` TEXT,
  `og_image` VARCHAR(255),
  `og_type` VARCHAR(50),
  `robots` VARCHAR(100) DEFAULT 'index, follow',
  `canonical` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_page (page)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- RATE LIMITING TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `endpoint` VARCHAR(255),
  `attempts` INT DEFAULT 1,
  `last_attempt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_limit (ip_address, endpoint),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Create Initial Indices for Performance
-- ============================================
CREATE INDEX idx_created ON users(created_at);
CREATE INDEX idx_created ON domains(created_at);
CREATE INDEX idx_created ON blog_posts(created_at);
CREATE INDEX idx_created ON payments(created_at);
CREATE INDEX idx_created ON notifications(created_at);

-- ============================================
-- Insert Default Data
-- ============================================

-- Default Blog Categories
INSERT IGNORE INTO `blog_categories` (name, slug, description, color) VALUES
('Domain Tips', 'domain-tips', 'Tips and tricks for domain investing', '#667eea'),
('Technology', 'technology', 'Latest tech news and updates', '#764ba2'),
('Tutorials', 'tutorials', 'How-to guides and tutorials', '#f093fb'),
('News', 'news', 'Company and industry news', '#4facfe'),
('Resources', 'resources', 'Useful resources and tools', '#00f2fe');

-- Default Site Settings
INSERT IGNORE INTO `site_settings` (setting_key, setting_value, type, description) VALUES
('site_name', 'Oryzenx', 'string', 'Website name'),
('site_description', 'Premium domain marketplace and digital services', 'string', 'Site description'),
('site_logo', '/assets/images/logo.png', 'string', 'Logo URL'),
('site_favicon', '/assets/images/favicon.ico', 'string', 'Favicon URL'),
('admin_email', 'admin@oryzenx.com', 'string', 'Admin email'),
('support_email', 'support@oryzenx.com', 'string', 'Support email'),
('phone_number', '', 'string', 'Contact phone'),
('address', '', 'string', 'Company address'),
('currency', 'USD', 'string', 'Default currency'),
('wallet_usdt', '0x79395cbf73a98c48bfa53480d16cd5b428b5aff9', 'string', 'USDT BEP20 wallet'),
('wallet_trx', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV', 'string', 'TRX wallet'),
('wallet_trc20', 'TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV', 'string', 'TRC20 wallet'),
('min_offer_price', '150', 'number', 'Minimum offer price'),
('items_per_page', '20', 'number', 'Pagination items'),
('image_quality', '85', 'number', 'Image compression quality'),
('max_upload_size', '52428800', 'number', 'Max upload size in bytes'),
('smtp_enabled', '0', 'boolean', 'Enable SMTP'),
('smtp_host', '', 'string', 'SMTP host'),
('smtp_port', '587', 'number', 'SMTP port'),
('smtp_username', '', 'string', 'SMTP username'),
('smtp_password', '', 'string', 'SMTP password'),
('analytics_enabled', '0', 'boolean', 'Enable analytics'),
('google_analytics', '', 'string', 'Google Analytics ID');

-- ============================================
-- ALTER SESSIONS TABLE IF NEEDED
-- ============================================
ALTER TABLE `password_resets` ENGINE=InnoDB;

-- ============================================
-- DONE
-- ============================================
