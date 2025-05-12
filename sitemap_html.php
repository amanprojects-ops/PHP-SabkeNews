<?php include_once 'header.php'; ?>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        color: #333;
        text-align: center;
    }

    .sitemap-section {
        margin: 20px 0;
    }

    .sitemap-section h2 {
        color: #444;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .sitemap-list {
        list-style: none;
        padding-left: 20px;
    }

    .sitemap-list li {
        margin: 10px 0;
    }

    .sitemap-list a {
        color: #0066cc;
        text-decoration: none;
    }

    .sitemap-list a:hover {
        text-decoration: underline;
    }
</style>
<div class="container">
    <h1>SabkeNews Sitemap</h1>
    
    <div class="sitemap-section">
        <h2>Main Pages</h2>
        <ul class="sitemap-list">
            <li><a href="<?php echo $web_kit['websiteUrl'] ?>/">Home</a></li>
            <li><a href="<?php echo $web_kit['websiteUrl'] ?>/about">About Us</a></li>
            <li><a href="<?php echo $web_kit['websiteUrl'] ?>/contact">Contact</a></li>
            <li><a href="<?php echo $web_kit['websiteUrl'] ?>/privacy-policy">Privacy Policy</a></li>
            <li><a href="<?php echo $web_kit['websiteUrl'] ?>/terms-conditions">Terms & Conditions</a></li>
        </ul>
    </div>

    <div class="sitemap-section">
        <h2>News Categories</h2>
        <ul class="sitemap-list">
            <?php 
            function slug($txt){
                $txt = preg_replace('~[^\\pL\d]+~u', '-', $txt);
                $txt = trim($txt, '-');
                $txt = iconv('utf-8', 'us-ascii//TRANSLIT', $txt);
                $txt = strtolower($txt);
                $txt = preg_replace('~[^-\w]+~', '', $txt);
                if (empty($txt)) {
                    return 'n-a';
                }
                return $txt;
            }
            include_once 'system/connection.php';
            $sql = "SELECT * FROM `category` WHERE `categoryStatus` = 'Y'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li><a href="'. $web_kit['websiteUrl'] .'/post/'. slug($row['category_name']) .'">'. $row['category_name'] .'</a></li>';
                }
            }
            ?>
        </ul>
    </div>
</div>
<?php include_once 'footer.php'; ?>