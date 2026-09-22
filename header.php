<?php
/**
 * Header Template — SabkeNews
 * 
 * Handles: session init, config loading, database connection, caching,
 * SEO meta generation, and renders the site header + navigation.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

// ─── Core System Bootstrap ─────────────────────────────────────────
require_once __DIR__ . '/system/config.php';
require_once __DIR__ . '/system/connection.php';
require_once __DIR__ . '/system/cache.php';
require_once __DIR__ . '/system/seo-helpers.php';
require_once __DIR__ . '/system/helpers.php';

// ─── Load Website Settings (with caching) ──────────────────────────
$web_kit = Cache::get('website_settings');
if ($web_kit === false) {
    $stmt = $conn->prepare('SELECT * FROM settings WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $web_kit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    Cache::set('website_settings', $web_kit);
}

// ─── Page Detection & URL Config ───────────────────────────────────
$page = basename($_SERVER['PHP_SELF']);
$key = basename($_SERVER['REQUEST_URI']);
$url = SITE_URL;
$requestUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// ─── Page-specific SEO Meta Data ───────────────────────────────────
switch ($page) {
    case 'post.php':
        $category_name = urlToTitle($key);
        $stmt = $conn->prepare("SELECT * FROM category WHERE categoryStatus = 'Y' AND category_name = ?");
        $stmt->bind_param('s', $category_name);
        $stmt->execute();
        $postdata = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $title = generateSEOTitle($postdata['category_name'], $postdata['categoryTitle']);
        $keywords = generateKeywords($postdata['category_name'], $postdata['categoryTitle']);
        $description = generateMetaDescription($postdata['categoryTitle']);
        $favicon = $url . '/assets/images/' . $web_kit['websiteFavicon'];
        $postImage = $url . '/assets/images/' . $web_kit['websiteImg'];
        $pageUrl = $url . '/post/' . $key;
        $publishDate = date('c');
        break;

    case 'post-details.php':
        $stmt = $conn->prepare("SELECT p.*, c.category_name FROM post p LEFT JOIN category c ON p.category = c.category_id WHERE p.postStatus = 'Y' AND p.post_id = ?");
        $stmt->bind_param('s', $key);
        $stmt->execute();
        $postdata = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $title = generateSEOTitle($postdata['title'], $postdata['category_name']);
        $keywords = generateKeywords($postdata['title'], $postdata['category_name']);
        $description = generateMetaDescription($postdata['sort_details']);
        $favicon = $url . '/assets/images/' . $web_kit['websiteFavicon'];
        $postImage = $url . '/assets/postImage/' . $postdata['post_img'];
        $pageUrl = $url . '/post-details/' . $key;
        $publishDate = date('c', strtotime($postdata['post_date']));
        break;

    default:
        $title = generateSEOTitle($web_kit['websitename'], $web_kit['websiteTitle']);
        $keywords = generateKeywords($web_kit['keywords'] ?? '', $web_kit['websitename']);
        $description = generateMetaDescription($web_kit['description'] ?? '');
        $favicon = $url . '/assets/images/' . ($web_kit['websiteFavicon'] ?? '');
        $postImage = $url . '/assets/images/' . $web_kit['websiteImg'];
        $pageUrl = $url . '/' . $key;
        $publishDate = date('c');
        break;
}

// ─── Prepare SEO Tag Data ──────────────────────────────────────────
$siteName = htmlspecialchars($web_kit['websitename'], ENT_QUOTES, 'UTF-8');
$logoUrl = $url . '/assets/images/' . htmlspecialchars($web_kit['websiteLogo'] ?? '', ENT_QUOTES, 'UTF-8');

$ogData = [
    'type'        => 'article',
    'url'         => $pageUrl,
    'title'       => $title,
    'description' => $description,
    'image'       => $postImage,
    'site_name'   => $siteName,
];

$twitterData = [
    'url'         => $pageUrl,
    'title'       => $title,
    'description' => $description,
    'image'       => $postImage,
    'site'        => '@' . $siteName,
];

$schemaData = [
    'url'         => $pageUrl,
    'title'       => $title,
    'image'       => $postImage,
    'publishDate' => $publishDate,
    'siteName'    => $siteName,
    'logoUrl'     => $logoUrl,
    'description' => $description,
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= $url ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= $url ?>/assets/css/media-screen.css">

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <link rel="canonical" href="<?= htmlspecialchars($requestUrl) ?>">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- News-specific Meta Tags -->
    <meta name="news_keywords" content="<?= $keywords ?>">
    <meta property="article:published_time" content="<?= $publishDate ?>">
    <meta property="article:modified_time" content="<?= $publishDate ?>">

    <!-- Open Graph Meta Tags -->
<?= generateOpenGraphTags($ogData) ?>
    <!-- Twitter Card Meta Tags -->
<?= generateTwitterCardTags($twitterData) ?>
    <base href="<?= $url ?>">

    <!-- Schema.org Markup -->
    <?= generateSchemaMarkup($schemaData) ?>

    <!-- Critical CSS for above-the-fold content -->
    <style>
        .heading { width: 100%; height: 6rem; background: #2e67d9; border-radius: 8px }
        .heading td img { width: 20rem; height: 7rem; line-height: 7rem; border-radius: 10px; border: 2px solid #fff; box-shadow: #ffffffc2 0 0 4.5px 1.5px; margin: 25px }
        .web-content { width: 100%; font-family: system-ui; color: #fff; text-transform: uppercase }
        .head-title { font-size: 25px; text-transform: uppercase }
        .container { width: 80%; display: flex; margin: auto; flex-direction: column }
        .navbar { width: 100%; height: 50px; background-color: #1e1549; flex-wrap: wrap; overflow-wrap: break-word; text-transform: uppercase }
        .navbar ul { display: flex; justify-content: center; align-items: center; position: relative; list-style: none; font-weight: bolder }
        .navbar ul li a { text-decoration: none; padding: 0 1em; color: #fff; font-size: 1rem }
        @media only screen and (max-width: 768px) {
            .container { width: 100%; max-width: 100% }
            .heading { width: 100%; height: 5.5rem; border-radius: 5px }
            .heading td img { width: 10rem; height: 5rem; margin: 0; border-radius: 8px }
        }
    </style>
</head>

<body>
    <header class="container">
        <div class="heading">
            <div class="logo-container">
                <a href="<?= $url ?>" title="<?= $siteName ?>">
                    <img src="<?= $url ?>/assets/images/<?= htmlspecialchars($web_kit['websiteLogo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= $siteName ?> Logo"
                        fetchpriority="high"
                        width="200"
                        height="80"
                        class="website-logo"
                        style="aspect-ratio: 2.5/1; height: auto;"
                        onerror="this.onerror=null; this.src='<?= $url ?>/assets/images/logo.png';">
                </a>
                <div class="web-content">
                    <h1 class="head-title"><?= $siteName ?></h1>
                    <p class="site-tagline"><?= 'WWW.' . $siteName . '.BLOG' ?></p>
                </div>
            </div>
        </div>

        <nav class="navbar" aria-label="Main navigation">
            <ul role="menubar">
                <?php
                // Navigation with caching
                $categories = Cache::get('nav_menu');
                if ($categories === false) {
                    $stmt = $conn->prepare("SELECT category_name, categoryStatus, categoryTitle FROM category WHERE categoryStatus = 'Y' ORDER BY category_id DESC");
                    $stmt->execute();
                    $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    Cache::set('nav_menu', $categories);
                }

                echo "<li role='menuitem'><a href='{$url}' aria-current='page'>HOME</a></li>";
                foreach ($categories as $categoryD) {
                    $category_url = generateUrl($categoryD['category_name']);
                    echo "<li role='menuitem'><a href='{$url}/post/" . htmlspecialchars($category_url) . "'>"
                        . htmlspecialchars($categoryD['category_name']) . '</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>