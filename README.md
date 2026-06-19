# Oryzenx - Premium Domain Marketplace & SaaS Platform

A complete production-ready SaaS platform for domain trading, blogging, and digital services built with PHP 8, MySQL, and Bootstrap 5.

## 🚀 Features

### Domain Marketplace
- Browse and search premium domains
- Make offers on domains (minimum $150)
- View domain ratings and quality badges
- Feature domains (admin only)
- Track offer history

### Blog System
- Multi-language support (English & Bangla)
- Rich text editor with image upload
- Automatic image compression & optimization
- Schedule posts, drafts, and publishing
- Like & Love reactions
- View counter and relative timestamps

### User Management
- Secure authentication system
- User registration with email verification
- Profile management
- Password recovery system
- Notification preferences

### Payment System
- Crypto payments only (USDT, TRX, TRC20)
- QR code generation
- Payment proof upload with compression
- Admin approval workflow
- Payment history tracking

### Admin Dashboard
- Complete user management
- Domain management (add, edit, delete, feature)
- Offer management
- Payment approval system
- Blog and category management
- Contact message management
- SEO control panel
- Database backup/restore
- Activity and security logs
- CSV/Excel export

### Advanced Features
- Glassmorphism design with smooth animations
- Responsive mobile-first layout
- Bottom navigation for mobile
- CSRF & XSS protection
- Rate limiting on APIs
- Session security
- Password hashing with bcrypt

## 📋 Tech Stack

- **Backend**: PHP 8+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **AJAX**: Native JavaScript
- **Image Processing**: PHP GD Library
- **Security**: OpenSSL, bcrypt, CSRF tokens

## 📁 Project Structure

```
oryzenx/
├── install.php                 # Installation wizard
├── index.php                   # Homepage
├── config.php                  # Configuration (auto-generated)
├── config-example.php          # Configuration template
├── .htaccess                   # Apache rewrite rules
├── database.sql                # Database schema
├── README.md                   # This file
│
├── admin/                      # Admin panel
├── user/                       # User dashboard
├── api/                        # REST API endpoints
├── includes/                   # Core classes
├── assets/                     # Static assets
├── uploads/                    # User uploads
└── logs/                       # Application logs
```

## 🔧 Installation

### Requirements
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB
- Apache 2.4+ with mod_rewrite enabled
- Required PHP Extensions: PDO, GD, cURL, OpenSSL, JSON

### Quick Start

1. **Upload files to your hosting**
   ```bash
   FTP/SFTP upload all files to public_html or www folder
   ```

2. **Run Installation Wizard**
   - Visit: `http://yourdomain.com/install.php`
   - Follow the setup steps
   - Create admin account
   - Configure database and settings

3. **Delete install.php** (for security)
   ```bash
   rm install.php
   ```

4. **Access Admin Panel**
   - URL: `http://yourdomain.com/admin`
   - Email: (your admin email)
   - Password: (your admin password)

5. **Configure Settings**
   - Add your website logo
   - Set crypto wallet addresses
   - Configure SEO settings
   - Customize homepage sections

## 💰 Default Crypto Wallets

```
USDT BEP20:  0x79395cbf73a98c48bfa53480d16cd5b428b5aff9
TRX:         TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV
TRC20:       TLKZgeHU45vMuZcHeEHQ95GZQ2UhB3cfxV
```

Change these in admin panel under Settings.

## 🔐 Security Features

- CSRF Protection on all forms
- XSS Protection with input sanitization
- SQL Injection Prevention with prepared statements
- Password hashing with bcrypt (12 rounds)
- Session security with secure cookies
- Rate limiting on API endpoints
- Activity logging for audit trails
- Security logs for suspicious activities
- HTTPS support via .htaccess
- No directory listing
- Sensitive file protection

## 📊 Database Tables

1. users - User accounts and authentication
2. domains - Domain listings
3. domain_offers - User offers on domains
4. blog_posts - Blog articles
5. blog_categories - Post categories
6. blog_reactions - Like/Love reactions
7. payments - Payment records
8. notifications - User notifications
9. contact_messages - Contact form submissions
10. password_resets - Password recovery tokens
11. partners - Partner logos
12. site_settings - Website configuration
13. activity_logs - Admin actions log
14. security_logs - Security events log
15. user_sessions - Active sessions tracking

## 🎨 Design Features

### Desktop
- Professional SaaS dashboard
- Glassmorphism cards with transparency
- Smooth transitions and animations
- Premium button styles
- Responsive grid layout
- Professional typography

### Mobile
- Native app-like interface
- Bottom navigation bar
- Touch-optimized buttons
- Smooth scrolling
- Mobile-first responsive design
- Optimized image loading

## 📱 API Documentation

### Authentication
```
POST /api/auth/signup.php
POST /api/auth/login.php
POST /api/auth/logout.php
POST /api/auth/forgot.php
```

### Domains
```
GET  /api/domains/index.php
POST /api/domains/store.php (admin)
PUT  /api/domains/update.php (admin)
DEL  /api/domains/delete.php (admin)
POST /api/domains/offer.php
```

### Blog
```
GET  /api/blog/index.php
POST /api/blog/store.php (admin)
PUT  /api/blog/update.php (admin)
DEL  /api/blog/delete.php (admin)
POST /api/blog/react.php
```

### Payments
```
GET  /api/payments/index.php
POST /api/payments/store.php
POST /api/payments/verify.php
```

### Search
```
GET /api/search.php?q=keyword&type=domain|blog|all
```

## 🚨 Troubleshooting

### White screen?
- Check error logs in `/logs/errors.log`
- Verify PHP version is 8.0+
- Check database connection settings
- Enable DEBUG_MODE in config.php temporarily

### Can't create config.php?
- Check folder permissions (755)
- Ensure PHP can write to directory
- Try uploading config.php manually

### Database connection fails?
- Verify database credentials
- Check database host address
- Ensure MySQL is running
- Ask hosting provider for database info

### Images not uploading?
- Check /uploads folder permissions (755)
- Verify GD library is installed
- Check PHP max upload size (php.ini)
- Ensure file extensions are allowed

## 📧 Support

For issues and feature requests:
- Email: admin@oryzenx.com
- Create an issue on GitHub
- Contact your hosting provider

## 📄 License

Proprietary - All rights reserved © 2026 Oryzenx

## 📝 Changelog

### Version 1.0 (2026-06-19)
- Initial release
- Complete admin panel
- Domain marketplace system
- Blog with reactions
- Payment system with crypto
- User authentication
- Notification system
- SEO management
- Mobile responsive design

---

**Happy using Oryzenx! 🚀**