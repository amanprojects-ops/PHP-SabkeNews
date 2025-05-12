<?php

function generateSEOTitle($primary, $secondary = '') {
    $title = trim($primary);
    if (!empty($secondary)) {
        $title .= ' - ' . trim($secondary);
    }
    return $title;
}

function generateKeywords($primary, $secondary = '') {
    $keywords = array_filter(array_map('trim', explode(',', $primary . ',' . $secondary)));
    return implode(', ', array_unique($keywords));
}

function generateMetaDescription($text) {
    $description = strip_tags($text);
    return substr($description, 0, 160); // Limit to 160 characters for SEO
}
?>