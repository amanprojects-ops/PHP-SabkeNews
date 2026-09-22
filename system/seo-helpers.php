<?php
/**
 * SEO Helper Functions
 * 
 * Centralized SEO meta generation: titles, descriptions, keywords,
 * Open Graph tags, Twitter Cards, and JSON-LD Schema markup.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

/**
 * Generate SEO-optimized page title
 * 
 * @param string $primary Primary title text
 * @param string $secondary Secondary/suffix text
 * @return string Formatted title
 */
function generateSEOTitle($primary, $secondary = '') {
    $title = trim($primary);
    if (!empty($secondary)) {
        $title .= ' - ' . trim($secondary);
    }
    return htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate keywords meta tag content
 * 
 * @param string $primary Primary keywords (comma-separated)
 * @param string $secondary Additional keywords
 * @return string Unique, deduplicated keyword string
 */
function generateKeywords($primary, $secondary = '') {
    $combined = $primary . ',' . $secondary;
    $keywords = array_filter(array_map('trim', explode(',', $combined)));
    $unique = array_unique($keywords);
    return htmlspecialchars(implode(', ', $unique), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate meta description (truncated to SEO-optimal length)
 * 
 * @param string $text Source text (may contain HTML)
 * @param int $maxLen Maximum characters (default: 160)
 * @return string Clean, truncated description
 */
function generateMetaDescription($text, $maxLen = 160) {
    $description = strip_tags($text);
    $description = preg_replace('/\s+/', ' ', trim($description));
    if (strlen($description) > $maxLen) {
        $description = substr($description, 0, $maxLen - 3) . '...';
    }
    return htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate Open Graph meta tags HTML
 * 
 * @param array $data Associative array with keys: type, url, title, description, image, site_name
 * @return string HTML meta tags
 */
function generateOpenGraphTags($data) {
    $tags = '';
    $ogMap = [
        'type'        => 'og:type',
        'url'         => 'og:url',
        'title'       => 'og:title',
        'description' => 'og:description',
        'image'       => 'og:image',
        'site_name'   => 'og:site_name',
    ];
    
    foreach ($ogMap as $key => $property) {
        if (isset($data[$key]) && !empty($data[$key])) {
            $content = htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8');
            $tags .= "    <meta property=\"{$property}\" content=\"{$content}\">\n";
        }
    }
    
    // Standard image dimensions for social sharing
    if (!empty($data['image'])) {
        $tags .= "    <meta property=\"og:image:width\" content=\"1200\">\n";
        $tags .= "    <meta property=\"og:image:height\" content=\"630\">\n";
    }
    
    $tags .= "    <meta property=\"og:locale\" content=\"en_US\">\n";
    
    return $tags;
}

/**
 * Generate Twitter Card meta tags HTML
 * 
 * @param array $data Associative array with keys: url, title, description, image, site
 * @return string HTML meta tags
 */
function generateTwitterCardTags($data) {
    $tags = "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    
    $twitterMap = [
        'url'         => 'twitter:url',
        'title'       => 'twitter:title',
        'description' => 'twitter:description',
        'image'       => 'twitter:image',
        'site'        => 'twitter:site',
    ];
    
    foreach ($twitterMap as $key => $name) {
        if (isset($data[$key]) && !empty($data[$key])) {
            $content = htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8');
            $tags .= "    <meta name=\"{$name}\" content=\"{$content}\">\n";
        }
    }
    
    return $tags;
}

/**
 * Generate JSON-LD Schema.org markup for NewsArticle
 * 
 * @param array $data Article data with keys: url, title, image, publishDate, siteName, logoUrl, description
 * @return string JSON-LD script tag
 */
function generateSchemaMarkup($data) {
    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'NewsArticle',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => $data['url'] ?? '',
        ],
        'headline'        => $data['title'] ?? '',
        'image'           => [$data['image'] ?? ''],
        'datePublished'   => $data['publishDate'] ?? date('c'),
        'dateModified'    => $data['publishDate'] ?? date('c'),
        'author'          => [
            '@type' => 'Organization',
            'name'  => $data['siteName'] ?? '',
        ],
        'publisher'       => [
            '@type' => 'Organization',
            'name'  => $data['siteName'] ?? '',
            'logo'  => [
                '@type'  => 'ImageObject',
                'url'    => $data['logoUrl'] ?? '',
                'width'  => '200',
                'height' => '80',
            ],
        ],
        'description'     => $data['description'] ?? '',
        'speakable'       => [
            '@type'       => 'SpeakableSpecification',
            'cssSelector' => ['article', '.post-description'],
        ],
    ];
    
    $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    return "<script type=\"application/ld+json\">\n{$json}\n    </script>";
}