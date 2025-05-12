<?php
session_start();
//default timezone
date_default_timezone_set("Asia/Kolkata"); // UTC +5:30
// Error reporting

require_once("./system/connection.php");
require_once("./system/seo-helpers.php");
include_once("./social-media-share.php");

// Load website settings with file-based caching
$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0777, true);
}

// Website settings cache
$cache_key = 'website_settings';
$cache_file = $cache_dir . '/' . $cache_key . '.cache';
$web_kit = false;

if (file_exists($cache_file) && (time() - filemtime($cache_file) < 3600)) {
    $web_kit = unserialize(file_get_contents($cache_file));
}

if ($web_kit === false) {
    $stmt = $conn->prepare("SELECT * FROM settings WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $web_kit = $stmt->get_result()->fetch_assoc();
    file_put_contents($cache_file, serialize($web_kit));
}

// Get current page and request info
$page = basename($_SERVER['PHP_SELF']);
$key = basename($_SERVER['REQUEST_URI']);

// Base URL configuration from environment
$url = getenv('SITE_URL') ?: "http://127.0.0.1/www/sabkenews.in";
$requestUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Page-specific meta data configuration
switch ($page) {
    case "post.php":
        $category_name = urlToTitle($key);
        $stmt = $conn->prepare("SELECT * FROM category WHERE categoryStatus = 'Y' AND category_name = ?");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $postdata = $stmt->get_result()->fetch_assoc();

        $title = generateSEOTitle($postdata['category_name'], $postdata['categoryTitle']);
        $keywords = generateKeywords($postdata['category_name'], $postdata['categoryTitle']);
        $description = generateMetaDescription($postdata['categoryTitle']);
        $favicon = $url . "/assets/images/" . $web_kit['websiteFavicon'];
        $postImage = $url . "/assets/images/" . $web_kit['websiteImg'];
        $pageUrl = $url . "/post/" . $key;
        $publishDate = date('c');
        break;

    case "post-details.php":
        $stmt = $conn->prepare("SELECT p.*, c.category_name FROM post p LEFT JOIN category c ON p.category = c.category_id WHERE p.postStatus = 'Y' AND p.post_id = ?");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $postdata = $stmt->get_result()->fetch_assoc();

        $title = generateSEOTitle($postdata['title'], $postdata['category_name']);
        $keywords = generateKeywords($postdata['title'], $postdata['category_name']);
        $description = generateMetaDescription($postdata['sort_details']);
        $favicon = $url . "/assets/images/" . $web_kit['websiteFavicon'];
        $postImage = $url . "/assets/postImage/" . $postdata['post_img'];
        $pageUrl = $url . "/post-details/" . $key;
        $publishDate = date('c', strtotime($postdata['post_date']));
        break;

    default:
        $title = generateSEOTitle($web_kit['websitename'], $web_kit['websiteTitle']);
        $keywords = generateKeywords($web_kit['keywords'] ?? '', $web_kit['websitename']);
        $description = generateMetaDescription($web_kit['description'] ?? '');
        $favicon = $url . "/assets/images/" . ($web_kit['websiteFavicon'] ?? '');
        $postImage = $url . "/assets/images/" . $web_kit['websiteImg'];
        $pageUrl = $url . "/" . $key;
        $publishDate = date('c');
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?></title>

    <!-- Critical CSS Inlined for Faster Rendering -->
    <style>
        /* Critical CSS for above-the-fold content */
        .heading {
            width: 100%;
            height: 6rem;
            background: #2e67d9;
            border-radius: 8px
        }

        .heading td img {
            width: 20rem;
            height: 7rem;
            line-height: 7rem;
            border-radius: 10px;
            border: 2px solid #fff;
            box-shadow: #ffffffc2 0 0 4.5px 1.5px;
            margin: 25px
        }

        .web-content {
            width: 100%;
            font-family: system-ui;
            color: #fff;
            text-transform: uppercase
        }

        .head-title {
            font-size: 25PX;
            text-transform: uppercase
        }

        .container {
            width: 80%;
            display: flex;
            margin: auto;
            flex-direction: column
        }

        .navbar {
            width: 100%;
            height: 50px;
            background-color: #1e1549;
            flex-wrap: wrap;
            overflow-wrap: break-word;
            text-transform: uppercase
        }

        .navbar ul {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            list-style: none;
            font-weight: bolder
        }

        .navbar ul li a {
            text-decoration: none;
            padding: 0 1em;
            color: #fff;
            font-size: 1rem
        }

        @media only screen and (max-width:768px) {
            .container {
                width: 100%;
                max-width: 100%
            }

            .heading {
                width: 100%;
                height: 5.5rem;
                border-radius: 5px
            }

            .heading td img {
                width: 10rem;
                height: 5rem;
                margin: 0;
                border-radius: 8px
            }
        }
    </style>

    <!-- Performance Optimizations with Minified CSS -->
    <link rel="preload" href="<?= $url ?>/assets/css/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= $url ?>/assets/css/media-screen.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Fallback for CSS loading -->
    <noscript>
        <link rel="stylesheet" href="<?= $url ?>/assets/css/style.min.css">
        <link rel="stylesheet" href="<?= $url ?>/assets/css/media-screen.min.css">
    </noscript>

    <!-- Preload critical assets -->
    <link rel="preload" fetchpriority="high" as="image" href="<?= $url ?>/assets/images/logo.png" type="image/png">

    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <link rel="canonical" href="<?= $requestUrl ?>" />
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- News-specific Meta Tags -->
    <meta name="news_keywords" content="<?= $keywords ?>">
    <meta property="article:published_time" content="<?= $publishDate ?>">
    <meta property="article:modified_time" content="<?= $publishDate ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= $pageUrl ?>">
    <meta property="og:title" content="<?= $title ?>">
    <meta property="og:description" content="<?= $description ?>">
    <meta property="og:image" content="<?= $postImage ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="<?= htmlspecialchars($web_kit['websitename']) ?>">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= $pageUrl ?>">
    <meta name="twitter:title" content="<?= $title ?>">
    <meta name="twitter:description" content="<?= $description ?>">
    <meta name="twitter:image" content="<?= $postImage ?>">
    <meta name="twitter:site" content="@<?= htmlspecialchars($web_kit['websitename']) ?>">

    <base href="<?= $url ?>">

    <!-- Resource Hints for Performance -->
    <link rel="preconnect" href="<?= $url ?>" crossorigin>
    <link rel="preload" as="font" href="https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu4mxK.woff2" crossorigin>
    <?php if (isset($postImage) && !empty($postImage)): ?>
        <link rel="preload" as="image" href="<?= $postImage ?>" media="(max-width: 600px)" fetchpriority="high">
    <?php endif; ?>

    <!-- Enhanced Schema.org Markup -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "NewsArticle",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= $pageUrl ?>"
            },
            "headline": "<?= $title ?>",
            "image": ["<?= $postImage ?>"],
            "datePublished": "<?= $publishDate ?>",
            "dateModified": "<?= $publishDate ?>",
            "author": {
                "@type": "Organization",
                "name": "<?= htmlspecialchars($web_kit['websitename']) ?>"
            },
            "publisher": {
                "@type": "Organization",
                "name": "<?= htmlspecialchars($web_kit['websitename']) ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= $url ?>/assets/images/<?= htmlspecialchars($web_kit['websiteLogo']) ?>",
                    "width": "200",
                    "height": "80"
                }
            },
            "description": "<?= $description ?>",
            "speakable": {
                "@type": "SpeakableSpecification",
                "cssSelector": ["article", ".post-description"]
            }
        }
    </script>

    <!-- Page Speed Optimization -->
    <script>
        // Measure and optimize LCP (Largest Contentful Paint)
        const lcpObserver = new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            const lcpEntry = entries[entries.length - 1];
            console.log('LCP:', lcpEntry.startTime / 1000, 'seconds');
        });
        lcpObserver.observe({
            type: 'largest-contentful-paint',
            buffered: true
        });

        // Preload important images when browser is idle
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                // Preload additional resources when browser is idle
                const preloadLink = document.createElement('link');
                preloadLink.rel = 'preload';
                preloadLink.as = 'image';
                preloadLink.href = '<?= $url ?>/assets/images/logo.png';
                document.head.appendChild(preloadLink);
            });
        }
    </script>
</head>

<body>
    <!-- Page load optimization script -->
    <script>
        (function(n) {
            "use strict";
            var o = loadCSS.relpreload = {};
            o.support = function() {
                try {
                    return n.document.createElement("link").relList.supports("preload");
                } catch (e) {
                    return false;
                }
            }();

            o.bindMediaToggle = function(t) {
                var e = t.media || "";

                function a() {
                    t.removeEventListener ? t.removeEventListener("load", a) : t.detachEvent && t.detachEvent("onload", a);
                    t.media = e;
                }
                t.addEventListener ? t.addEventListener("load", a) : t.attachEvent && t.attachEvent("onload", a);
                setTimeout(function() {
                    t.rel = "stylesheet";
                    t.media = "only x";
                }, 0);
                setTimeout(a, 3000);
            };

            o.poly = function() {
                if (!o.support) {
                    var t = n.document.getElementsByTagName("link");
                    for (var e = 0; e < t.length; e++) {
                        var a = t[e];
                        if (a.rel === "preload" && a.getAttribute("as") === "style" && !a.getAttribute("data-loadcss")) {
                            a.setAttribute("data-loadcss", true);
                            o.bindMediaToggle(a);
                        }
                    }
                }
            };

            !o.support && o.poly();
            n.addEventListener ? n.addEventListener("load", function() {
                o.poly();
            }) : n.attachEvent && n.attachEvent("onload", function() {
                o.poly();
            });
        })(typeof global !== "undefined" ? global : this);
    </script>


    <header class="container">
        <div class="heading">
            <div class="logo-container">
                <a href="<?= $url ?>" title="<?= htmlspecialchars($web_kit['websitename']) ?>">
                    <img src="<?= $url ?>/assets/images/<?= htmlspecialchars("") ?>"
                        alt="<?= htmlspecialchars($web_kit['websitename']) ?> Logo"
                        fetchpriority="high"
                        width="200"
                        height="80"
                        class="website-logo"
                        style="aspect-ratio: 2.5/1; height: auto;"
                        onerror="this.onerror=null; this.src='<?= $url ?>/assets/images/logo.png';">
                </a>
                <div class="web-content">
                    <h1 class="head-title">
                        <?= htmlspecialchars($web_kit['websitename']) ?>
                    </h1>
                    <p class="site-tagline"><?= "WWW." . htmlspecialchars($web_kit['websitename']) . ".BLOG" ?></p>
                </div>
            </div>
        </div>

        <nav class="navbar" aria-label="Main navigation">
            <ul role="menubar">
                <?php
                // In the navigation section, replace APCu cache with file-based cache
                $cache_key = 'nav_menu';
                $cache_file = $cache_dir . '/' . $cache_key . '.cache';
                $categories = false;

                if (file_exists($cache_file) && (time() - filemtime($cache_file) < 3600)) {
                    $categories = unserialize(file_get_contents($cache_file));
                }

                if ($categories === false) {
                    $stmt = $conn->prepare("SELECT category_name, categoryStatus, categoryTitle FROM category WHERE categoryStatus = 'Y' ORDER BY category_id DESC");
                    $stmt->execute();
                    $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    file_put_contents($cache_file, serialize($categories));
                }

                echo "<li role='menuitem'><a href='$url' aria-current='page'>HOME</a></li>";
                foreach ($categories as $categoryD) {
                    $category_url = generateUrl($categoryD['category_name']);
                    echo "<li role='menuitem'><a href='$url/post/" . htmlspecialchars($category_url) . "'>" .
                        htmlspecialchars($categoryD['category_name']) . "</a></li>";
                }
                ?>
            </ul>
        </nav>
    </header>