<?php
/**
 * Helper Functions — Single source of truth
 * 
 * All utility functions consolidated here to avoid duplication.
 * Previously scattered across: social-media-share.php, sitemap_html.php, BaseController.php
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

/**
 * Generate SEO-friendly URL slug from text
 * 
 * Converts title/text to URL-safe slug.
 * Example: "Latest Bihar News 2024!" → "latest-bihar-news-2024"
 * 
 * @param string $txt  Input text to convert
 * @param int $maxLen  Maximum slug length (default: 54)
 * @return string URL-safe slug
 */
function generateUrl($txt, $maxLen = 54) {
    $txt = substr($txt, 0, $maxLen);
    $txt = preg_replace('~[^\pL\d]+~u', '-', $txt);
    $txt = trim($txt, '-');
    // Transliterate to ASCII
    if (function_exists('iconv')) {
        $txt = @iconv('utf-8', 'us-ascii//TRANSLIT', $txt);
    }
    $txt = strtolower($txt);
    $txt = preg_replace('~[^-\w]+~', '', $txt);
    return empty($txt) ? 'n-a' : $txt;
}

/**
 * Convert URL slug back to readable title
 * 
 * @param string $title URL slug
 * @return string Human-readable title (first 2 words)
 */
function urlToTitle($title) {
    $parts = explode('-', $title);
    if (count($parts) >= 2) {
        return $parts[0] . ' ' . $parts[1];
    }
    return str_replace('-', ' ', strtolower($title));
}

/**
 * Simple URL cleaner — spaces to hyphens
 * 
 * @param string $url Raw URL string
 * @return string Cleaned URL
 */
function cleanUrl($url) {
    return str_replace(' ', '-', strtolower($url));
}

/**
 * Truncate and sanitize text for display
 * 
 * @param string $text Raw text (may contain HTML)
 * @param int $maxLen Maximum character length
 * @return string Sanitized, truncated text
 */
function note($text, $maxLen = 200) {
    $text = strip_tags($text);
    if (strlen($text) > $maxLen) {
        $text = substr($text, 0, $maxLen - 3) . '...';
    }
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate social media share links for a post
 * 
 * @param string $title Post title
 * @param string $description Post description/excerpt
 * @param string $imageURL Post image URL
 * @param string $pageURL Post page URL
 * @param string $faviconURL Site favicon URL
 * @return array Associative array of share links
 */
function generatePostShareLinks($title, $description, $imageURL, $pageURL, $faviconURL) {
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    $encodedPageURL = urlencode($pageURL);

    return [
        'facebookUrl'  => "https://www.facebook.com/sharer/sharer.php?u={$encodedPageURL}",
        'instagramUrl'  => "https://www.instagram.com/",
        'whatsappUrl'  => "https://wa.me/?text={$encodedTitle}%0A{$encodedPageURL}",
        'linkedinUrl'  => "https://www.linkedin.com/shareArticle?url={$encodedPageURL}&title={$encodedTitle}&summary={$encodedDescription}",
        'telegramUrl'  => "https://t.me/share/url?url={$encodedPageURL}&text={$encodedTitle}",
        'twitterUrl'   => "https://twitter.com/intent/tweet?url={$encodedPageURL}&text={$encodedTitle}",
    ];
}
