# 📰 PHP-SabkeNews

**SabkeNews** — A PHP-based news/blog portal built for Bihar local news coverage. Runs on Apache (XAMPP) with MySQL database. Designed for SEO-optimized content delivery with file-based caching, social media sharing, and a full admin panel.

---

## 🚀 Features

| Feature | Description |
|---------|-------------|
| 🏠 **Homepage** | Trending posts tabs (color-coded), category-wise post listings with Schema.org markup |
| 📰 **Post Details** | Full article view with social sharing (WhatsApp, Telegram, Facebook, Twitter, LinkedIn) |
| 📂 **Category Pages** | SEO-friendly URLs, paginated post listings per category |
| 🔍 **SEO Optimized** | Auto-generated meta tags, Open Graph, Twitter Cards, JSON-LD Schema, XML Sitemap |
| 🗺️ **Sitemaps** | Both XML (`sitemap.php`) and HTML (`sitemap_html.php`) sitemaps |
| 💾 **File-based Caching** | Settings & navigation cached to filesystem (1-hour TTL) |
| 👤 **Visitor Logging** | Lightweight visitor tracking with rate limiting & log rotation |
| 🛡️ **Security** | Prepared statements, XSS protection, CSRF headers, directory listing disabled |
| 📱 **Responsive** | Mobile-first design with breakpoints at 1700px, 1300px, 850px, 768px, 480px, 320px |
| 🔧 **Admin Panel** | Full CMS at `/admin/` — manage posts, categories, users, settings, file uploads |

---

## 📁 Directory Structure

```
PHP-SabkeNews/
├── admin/                      # Admin Panel (CMS)
│   ├── app/                    # Admin backend logic
│   │   ├── _DBconnect.php      # Admin DB connection
│   │   ├── app.php             # Main admin operations
│   │   ├── fileUpload.php      # File upload handler
│   │   ├── generateUrl.php     # URL generator
│   │   ├── loading-post.php    # AJAX post loader
│   │   ├── lodaing-category.php # AJAX category loader
│   │   ├── lodaing-message.php # AJAX message loader
│   │   ├── lodaing-user.php    # AJAX user loader
│   │   ├── lodaing-user-data.php # User data loader
│   │   ├── login.php           # Admin authentication
│   │   └── verifyUsername.php   # Username verification
│   ├── assets/                 # Admin CSS, JS, images
│   │   ├── css/                # Admin stylesheets
│   │   ├── js/                 # Admin JavaScript
│   │   ├── img/                # Admin images
│   │   ├── summernotes/        # Rich text editor
│   │   ├── tany/               # UI components
│   │   └── vendor/             # Admin vendor libs
│   ├── dashboard/              # Dashboard pages
│   │   ├── _header.php         # Admin header template
│   │   ├── _footer.php         # Admin footer template
│   │   ├── _contentBox.php     # Content container
│   │   ├── _subHeader.php      # Sub-header component
│   │   ├── new-post.php        # Create new post
│   │   ├── update-post.php     # Edit existing post
│   │   ├── view-post.php       # View all posts
│   │   ├── new-category.php    # Create category
│   │   ├── view-category.php   # View categories
│   │   ├── new-user.php        # Create user
│   │   ├── view-user.php       # View users
│   │   ├── messages.php        # Contact messages
│   │   ├── manage-website.php  # Website settings
│   │   ├── setting.php         # Admin settings
│   │   └── ...                 # Other admin pages
│   ├── images/                 # Admin uploaded images
│   ├── index.php               # Admin login page
│   └── forgot-password.php     # Password recovery
│
├── assets/                     # Frontend assets
│   ├── css/
│   │   ├── style.css           # Main stylesheet
│   │   ├── media-screen.css    # Responsive breakpoints
│   │   ├── footer.css          # Footer styles
│   │   ├── login.css           # Login page styles
│   │   └── icon.js             # Icon definitions
│   ├── files/                  # Uploaded documents
│   ├── images/                 # Site images (logo, favicon)
│   ├── postImage/              # Post featured images
│   └── web-kits/               # Web assets (favicon.ico)
│
├── cache/                      # File-based cache (auto-generated)
│   ├── website_settings.cache
│   └── nav_menu.cache
│
├── img/                        # Static images
│   ├── display.gif
│   └── micon.png
│
├── system/                     # Core system modules
│   ├── config.php              # ⚙️ Central configuration
│   ├── connection.php          # 🔌 Database connection
│   ├── cache.php               # 💾 Cache class
│   ├── helpers.php             # 🔧 Utility functions
│   └── seo-helpers.php         # 🔍 SEO meta generators
│
├── index.php                   # 🏠 Homepage
├── header.php                  # Page header + navigation
├── footer.php                  # Page footer + copyright
├── post.php                    # 📂 Category post listing
├── post-details.php            # 📰 Single post view
├── about.php                   # About Us page
├── contact.php                 # Contact form
├── disclaimer.php              # Legal disclaimer
├── privacy-policy.php          # Privacy policy
├── terms-conditions.php        # Terms & conditions
├── sitemap.php                 # 🗺️ XML sitemap
├── sitemap_html.php            # 🗺️ HTML sitemap
├── social-media-share.php      # Social share (wrapper)
├── visitor-counter.php         # 👤 Visitor logging
├── 404.php                     # 🚫 Error page
├── robots.txt                  # Search engine directives
├── .htaccess                   # Apache URL rewriting & security
└── .gitignore                  # Git ignore rules
```

---

## ⚡ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 7.4+ (vanilla, no framework) |
| **Database** | MySQL 5.7+ / MariaDB |
| **Server** | Apache (XAMPP) with mod_rewrite |
| **Frontend** | HTML5, CSS3, JavaScript (vanilla) |
| **Icons** | Font Awesome 5.15 |
| **Rich Editor** | Summernote (admin panel) |
| **Caching** | File-based (system/cache.php) |

---

## 🛠️ Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 7.4+, Apache, MySQL)
- Git

### 1. Clone Repository
```bash
cd C:\xampp\htdocs
git clone https://github.com/amanprojects-ops/PHP-SabkeNews.git
```

### 2. Database Setup
1. Start **Apache** and **MySQL** from XAMPP Control Panel
2. Open **phpMyAdmin** → `http://localhost/phpmyadmin`
3. Create a new database named `blog`
4. Import the SQL file: Select `blog.sql` and import

### 3. Configuration
Edit `system/config.php`:
```php
// Database (change if needed)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Set your MySQL password
define('DB_NAME', 'blog');

// Set to false in production
define('DEBUG_MODE', true);
```

### 4. Verify
Open browser → `http://localhost/PHP-SabkeNews`

### 5. Admin Panel
Navigate to → `http://localhost/PHP-SabkeNews/admin`

---

## 🔧 System Modules

### `system/config.php` — Central Configuration
All site-wide constants: database credentials, URLs, cache settings, timezone, session config, debug toggle.

### `system/connection.php` — Database Connection
MySQLi connection with utf8mb4 charset, exception-based error handling, strict SQL mode.

### `system/cache.php` — File-based Cache
```php
// Usage
$data = Cache::get('my_key');
if ($data === false) {
    $data = expensiveDbQuery();
    Cache::set('my_key', $data);
}
Cache::delete('my_key');
Cache::clearAll();
```

### `system/helpers.php` — Utility Functions
| Function | Purpose |
|----------|---------|
| `generateUrl($text)` | Convert text to SEO-friendly URL slug |
| `urlToTitle($slug)` | Convert URL slug back to title |
| `cleanUrl($url)` | Simple space-to-hyphen URL cleaner |
| `note($text, $maxLen)` | Truncate and sanitize text |
| `generatePostShareLinks(...)` | Generate social media share URLs |

### `system/seo-helpers.php` — SEO Generators
| Function | Purpose |
|----------|---------|
| `generateSEOTitle($primary, $secondary)` | Page title |
| `generateKeywords($primary, $secondary)` | Meta keywords |
| `generateMetaDescription($text)` | Meta description (160 chars) |
| `generateOpenGraphTags($data)` | Facebook/OG meta tags |
| `generateTwitterCardTags($data)` | Twitter Card meta tags |
| `generateSchemaMarkup($data)` | JSON-LD Schema.org markup |

---

## 🗺️ URL Routing (.htaccess)

| URL Pattern | Maps To |
|-------------|---------|
| `/` | `index.php` |
| `/post/{category-slug}` | `post.php?post={slug}` |
| `/post-details/{title-slug}/{id}` | `post-details.php?post-title={slug}&postno={id}` |
| `/aboutus` | `about.php` |
| `/contactus` | `contact.php` |
| `/privacy-policy` | `privacy-policy.php` |
| `/disclaimer` | `disclaimer.php` |
| `/sitemap_html` | `sitemap_html.php` |
| `/sitemap.php` | XML Sitemap |

---

## 🛡️ Security Features

- ✅ **Prepared Statements** — All database queries use parameterized queries
- ✅ **XSS Protection** — `htmlspecialchars()` with `ENT_QUOTES | UTF-8` on all output
- ✅ **Content Sanitization** — `strip_tags()` whitelist on user-generated content
- ✅ **HTTP Security Headers** — `X-XSS-Protection`, `X-Content-Type-Options`, `X-Frame-Options`
- ✅ **Directory Listing Disabled** — `Options -Indexes` in `.htaccess`
- ✅ **Session Security** — `httponly`, `strict_mode` enabled
- ✅ **Input Validation** — `intval()` for numeric parameters, `trim()` for strings

---

## 📊 Performance Optimizations

- ✅ **File-based Caching** — Settings & navigation cached (1-hour TTL)
- ✅ **Gzip Compression** — Enabled for text, CSS, JS, fonts, SVG
- ✅ **Browser Caching** — 1-year for images/fonts, 1-month for CSS/JS
- ✅ **Async Font Loading** — Font Awesome loaded with `media="print"` trick
- ✅ **Minimal External Calls** — Visitor counter uses only local server variables
- ✅ **Selective DB Columns** — `SELECT post_id, title` instead of `SELECT *`
- ✅ **Critical CSS Inlined** — Above-the-fold styles in `<head>` for faster FCP

---

## 👨‍💻 Author

**Technical Aman**  
🌐 [devbin.site](https://devbin.site)  
📧 fullstackdeveloper014@gmail.com  
🐙 [github.com/amanprojects-ops](https://github.com/amanprojects-ops)

---

## 📄 License

This project is proprietary software. All rights reserved © <?php echo date('Y'); ?> SabkeNews.
