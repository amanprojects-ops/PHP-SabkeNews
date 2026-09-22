<?php 
/**
 * Homepage — SabkeNews
 * 
 * Displays welcome section, trending posts tabs, and category-wise post listings.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */
include_once("header.php");
?>

<div class="container box-style welcome-section">
    <h1 class="welcome-heading">
        Welcome to <?= htmlspecialchars($web_kit['websitename'], ENT_QUOTES, 'UTF-8') ?> 
        <span class="year"><?= date("Y") ?></span>
    </h1>
    <div class="news-ticker">
        <div class="ticker-content">
            WELCOME TO <?= strtoupper(htmlspecialchars($web_kit['websitename'], ENT_QUOTES, 'UTF-8')) ?> - Your Trusted Source for Bihar News
        </div>
    </div>
</div>

<!-- Trending Posts Tabs -->
<div class="container box-style">
    <?php 
    $COLORS = ["color1", "color2", "color3", "color4", "color5", "color6", "color7", "color8"];
    
    $limit = 8;
    $stmt = $conn->prepare("SELECT post_id, title FROM post WHERE postStatus = 'Y' ORDER BY post_id DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="row">
        <?php
        $i = 0;
        while ($post = $result->fetch_assoc()) {
            $postId = htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8');
            $title = htmlspecialchars(substr($post['title'], 0, 54), ENT_QUOTES, 'UTF-8');
            $postUrl = generateUrl($title);
            $colorClass = $COLORS[$i % count($COLORS)];
            echo <<<HTML
            <div class="tab-res {$colorClass}">
                <a href="{$url}/post-details/{$postUrl}/{$postId}" 
                   aria-label="{$title}"
                   rel="bookmark"
                   title="{$title}">
                    {$title}
                </a>
            </div>
            HTML;
            $i++;
        }
        $stmt->close();
        ?>
    </div>
</div>

<!-- Category-wise Post Listings -->
<main class="container box-style" role="main">
    <div class="row">
        <?php
        $stmt = $conn->prepare("SELECT COUNT(category_id) as count FROM category WHERE categoryStatus = 'Y'");
        $stmt->execute();
        $categoryCount = $stmt->get_result()->fetch_assoc()['count'];
        $stmt->close();

        $limit = 1;
        $catCounter = ceil($categoryCount / $limit);
        $start = 0;

        for ($b = 1; $b <= $catCounter; $b++): ?>
            <section class="content-lists" itemscope itemtype="http://schema.org/ItemList">
                <?php
                $stmt = $conn->prepare("SELECT category_id, category_name FROM category WHERE categoryStatus = 'Y' LIMIT ?, ?");
                $stmt->bind_param("ii", $start, $limit);
                $stmt->execute();
                $categoryResult = $stmt->get_result();

                while ($categoryData = $categoryResult->fetch_assoc()):
                    $category_id = htmlspecialchars($categoryData['category_id'], ENT_QUOTES, 'UTF-8');
                    $category_name = htmlspecialchars($categoryData['category_name'], ENT_QUOTES, 'UTF-8');
                    $category_url = generateUrl($category_name);
                ?>
                    <h2 class="content-heading" itemprop="name"><?= $category_name ?></h2>
                    <div class="list" role="list">
                        <?php
                        $stmt2 = $conn->prepare("SELECT post_id, title, last_update FROM post WHERE category = ? AND postStatus = 'Y' LIMIT 10");
                        $stmt2->bind_param("s", $category_id);
                        $stmt2->execute();
                        $postsResult = $stmt2->get_result();

                        while ($post = $postsResult->fetch_assoc()):
                            $post_title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
                            $post_url = generateUrl($post_title);
                            $post_id = htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8');
                            $date = new DateTime($post['last_update']);
                        ?>
                            <article class="content-rows" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" role="listitem">
                                <a href="<?= $url ?>/post-details/<?= $post_url ?>/<?= $post_id ?>" 
                                   itemprop="url"
                                   title="<?= $post_title ?>"
                                   rel="bookmark" aria-label="<?= $post_title ?>">
                                    <span itemprop="name"><?= $post_title ?></span>
                                </a>
                                <meta itemprop="datePublished" content="<?= $date->format('Y-m-d') ?>">
                            </article>
                        <?php endwhile;
                        $stmt2->close();
                        ?>
                    </div>
                    <div class="view-more">
                        <a href="<?= $url ?>/post/<?= $category_url ?>" 
                           class="btn btn-primary"
                           title="View more posts in <?= $category_name ?>"
                           rel="category">
                            View More <?= $category_name ?> Posts
                        </a>
                    </div>
                <?php 
                $start++;
                endwhile;
                $stmt->close();
                ?>
            </section>
        <?php endfor; ?>
    </div>
</main>

<?php include_once("footer.php"); ?>
