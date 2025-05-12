<?php
require_once("system/connection.php"); //Database Connection 
require_once("social-media-share.php"); //Clean Url & Unclean url
$web_kits = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings")); // Website Setting Data Fatching 
$url = $web_kits['websiteUrl']; //Get Website url

// Fetch required data
$categoryQ = mysqli_query($conn, "SELECT * FROM category WHERE categoryStatus ='Y'"); //Fetch Category 
$postQ = mysqli_query($conn, "SELECT * FROM post WHERE postStatus = 'Y' ORDER BY created_at DESC"); // Fetch Posts
$mainUrl = $web_kits['websiteUrl'];

// Set the content type to XML
header('Content-Type: application/xml');

// Start the XML file
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
      xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

// Function to format date according to W3C Datetime format
function w3cDateFormat($timestamp) {
    return date('Y-m-d\TH:i:sP', $timestamp);
}

// Add homepage
echo '<url>';
echo '<loc>' . htmlspecialchars($mainUrl) . '</loc>';
echo '<lastmod>' . w3cDateFormat(time()) . '</lastmod>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

// Add categories page
echo '<url>';
echo '<loc>' . htmlspecialchars($mainUrl . '/categories') . '</loc>';
echo '<lastmod>' . w3cDateFormat(time()) . '</lastmod>';
echo '<changefreq>weekly</changefreq>';
echo '<priority>0.8</priority>';
echo '</url>';

// Loop through each category
while ($categoryR = mysqli_fetch_assoc($categoryQ)) {
    $category_name = cleanUrl($categoryR['category_name']);
    $cateUrl = $url . "/post/" . $category_name;
    echo '<url>';
    echo '<loc>' . htmlspecialchars($cateUrl) . '</loc>';
    echo '<lastmod>' . w3cDateFormat(time()) . '</lastmod>';
    echo '<changefreq>daily</changefreq>';
    echo '<priority>0.8</priority>';
    echo '<news:news>';
    echo '<news:publication>';
    echo '<news:name>' . htmlspecialchars($web_kits['websitename']) . '</news:name>';
    echo '<news:language>en</news:language>';
    echo '</news:publication>';
    echo '<news:publication_date>' . w3cDateFormat(time()) . '</news:publication_date>';
    echo '<news:title>' . htmlspecialchars($categoryR['categoryTitle']) . '</news:title>';
    echo '</news:news>';
    echo '</url>';
}

// Loop through each post
while ($postR = mysqli_fetch_assoc($postQ)) {
    $postno = $postR['post_id'];
    $post_title = cleanUrl($postR['title']);
    $postUrl = $url."/".$post_title."/".$postno;
    
    echo '<url>';
    echo '<loc>' . htmlspecialchars($postUrl) . '</loc>';
    echo '<lastmod>' . w3cDateFormat(strtotime($postR['created_at'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.6</priority>';
    
    // Add image if exists
    if (!empty($postR['featured_image'])) {
        echo '<image:image>';
        echo '<image:loc>' . htmlspecialchars($url . '/uploads/' . $postR['featured_image']) . '</image:loc>';
        echo '<image:title>' . htmlspecialchars($postR['title']) . '</image:title>';
        echo '<image:caption>' . htmlspecialchars($postR['sort_details']) . '</image:caption>';
        echo '</image:image>';
    }
    
    echo '<news:news>';
    echo '<news:publication>';
    echo '<news:name>' . htmlspecialchars($web_kits['websitename']) . '</news:name>';
    echo '<news:language>en</news:language>';
    echo '</news:publication>';
    echo '<news:publication_date>' . w3cDateFormat(strtotime($postR['created_at'])) . '</news:publication_date>';
    echo '<news:title>' . htmlspecialchars($postR['sort_details']) . '</news:title>';
    echo '</news:news>';
    echo '</url>';
}

// Add post details page
echo '<url>';
echo '<loc>' . htmlspecialchars($mainUrl . '/post-details') . '</loc>';
echo '<lastmod>' . w3cDateFormat(time()) . '</lastmod>';
echo '<changefreq>weekly</changefreq>';
echo '<priority>0.7</priority>';
echo '</url>';

// End the XML file
echo '</urlset>';
