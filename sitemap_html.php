<?php 
/**
 * HTML Sitemap Page
 * 
 * Displays a user-friendly sitemap with main pages and news categories.
 * Uses generateUrl() from system/helpers.php instead of local slug() function.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */
include_once 'header.php'; 
?>
<style>
    .sitemap-section { margin: 20px 0 }
    .sitemap-section h2 { color: #444; border-bottom: 2px solid #eee; padding-bottom: 10px }
    .sitemap-list { list-style: none; padding-left: 20px }
    .sitemap-list li { margin: 10px 0 }
    .sitemap-list a { color: #0066cc; text-decoration: none }
    .sitemap-list a:hover { text-decoration: underline }
</style>

<div class="container">
    <h1>Sabke News Sitemap</h1>
    
    <div class="sitemap-section">
        <h2>Main Pages</h2>
        <ul class="sitemap-list">
            <li><a href="<?= $url ?>/">Home</a></li>
            <li><a href="<?= $url ?>/about">About Us</a></li>
            <li><a href="<?= $url ?>/contact">Contact</a></li>
            <li><a href="<?= $url ?>/privacy-policy">Privacy Policy</a></li>
            <li><a href="<?= $url ?>/terms-conditions">Terms & Conditions</a></li>
        </ul>
    </div>

    <div class="sitemap-section">
        <h2>News Categories</h2>
        <ul class="sitemap-list">
            <?php 
            // Use prepared statement — connection already established via header.php
            $stmt = $conn->prepare("SELECT category_name FROM category WHERE categoryStatus = 'Y' ORDER BY category_id DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categoryUrl = generateUrl($row['category_name']);
                    $categoryName = htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8');
                    echo "<li><a href=\"{$url}/post/{$categoryUrl}\">{$categoryName}</a></li>";
                }
            } else {
                echo '<li>No categories found</li>';
            }
            $stmt->close();
            ?>
        </ul>
    </div>
</div>

<?php include_once 'footer.php'; ?>