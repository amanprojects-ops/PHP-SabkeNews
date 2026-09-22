<?php 
/**
 * Post Details Page
 * 
 * Displays a single blog post with social sharing, image, content,
 * social connect links, and disclaimer.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */
include_once("header.php");

// ─── Validate & Sanitize Post ID ────────────────────────────────────
$postno = isset($_REQUEST['postno']) ? intval($_REQUEST['postno']) : 0;

if ($postno <= 0) {
    echo "<script>window.location.href='/post/';</script>";
    include_once("footer.php");
    exit;
}

// ─── Fetch Post with Prepared Statement ─────────────────────────────
$stmt = $conn->prepare("SELECT * FROM post WHERE post_id = ? AND postStatus = 'Y' LIMIT 1");
$stmt->bind_param('i', $postno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $postDQ = $result->fetch_assoc();
    $stmt->close();
?>

    <div class="container">
        <article class="single-post h-entry" itemscope itemtype="http://schema.org/BlogPosting">
            <header class="post-header box-style">
                <h1 class="post-title p-name" itemprop="headline"><?= htmlspecialchars($postDQ['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                <meta itemprop="author" content="<?= htmlspecialchars($web_kit['websitename'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="post-meta">
                    <time datetime="<?= date('Y-m-d', strtotime($postDQ['last_update'])) ?>" class="post-date dt-published" itemprop="datePublished">
                        <i class="fas fa-calendar-alt"></i> <?= date('F j, Y', strtotime($postDQ['last_update'])) ?>
                    </time>
                </div>
                <div class="post-excerpt p-summary" itemprop="description">
                    <?= htmlspecialchars($postDQ['sort_details'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </header>

            <div class="social-share box-style">
                <?php
                $postUrl = ($web_kit['websiteUrl'] ?? $url) . "/post-details/" . generateUrl($postDQ['title']) . "/" . $postno;
                $postImgUrl = ($web_kit['websiteUrl'] ?? $url) . '/assets/postImage/' . ($postDQ['post_img'] ?: 'default.png');
                $postFavUrl = ($web_kit['websiteUrl'] ?? $url) . "/assets/images/" . ($web_kit['websiteFavicon'] ?? '');
                $share_links = generatePostShareLinks($postDQ['title'], $postDQ['sort_details'], $postImgUrl, $postUrl, $postFavUrl);
                ?>
                <h3><i class="fas fa-share-alt"></i> Share This Post</h3>
                <ul class="share-buttons">
                    <li><a href="<?= $share_links['whatsappUrl'] ?>" class="btn-share whatsapp" target="_blank" rel="noopener nofollow" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                    <li><a href="<?= $share_links['telegramUrl'] ?>" class="btn-share telegram" target="_blank" rel="noopener nofollow" aria-label="Share on Telegram"><i class="fab fa-telegram"></i></a></li>
                    <li><a href="<?= $share_links['facebookUrl'] ?>" class="btn-share facebook" target="_blank" rel="noopener nofollow" aria-label="Share on Facebook"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="<?= $share_links['twitterUrl'] ?>" class="btn-share twitter" target="_blank" rel="noopener nofollow" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="<?= $share_links['linkedinUrl'] ?>" class="btn-share linkedin" target="_blank" rel="noopener nofollow" aria-label="Share on LinkedIn"><i class="fab fa-linkedin"></i></a></li>
                </ul>
            </div>

            <figure class="post-image box-style" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                <img src="<?= htmlspecialchars(($web_kit['websiteUrl'] ?? $url) . "/assets/postImage/" . ($postDQ['post_img'] ?: 'default.png'), ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($postDQ['title'], ENT_QUOTES, 'UTF-8') ?>"
                    loading="lazy"
                    width="800"
                    height="600"
                    class="u-photo"
                    itemprop="url">
                <meta itemprop="width" content="800">
                <meta itemprop="height" content="600">
                <figcaption class="post-image-caption" itemprop="caption">
                    <?= htmlspecialchars($postDQ['title'], ENT_QUOTES, 'UTF-8') ?>
                </figcaption>
            </figure>

            <div class="post-content box-style e-content" itemprop="articleBody">
                <?php
                // Sanitize post content — allow safe HTML tags only
                $allowedTags = '<p><br><strong><b><em><i><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><table><tr><td><th><thead><tbody><blockquote><pre><code><hr><div><span><figure><figcaption>';
                echo strip_tags($postDQ['description'], $allowedTags);
                ?>
            </div>

            <aside class="social-connect box-style">
                <h3><i class="fas fa-users"></i> Connect With Us</h3>
                <div class="social-grid">
                    <?php if (!empty($web_kit['telegramChannel'])): ?>
                    <a href="<?= htmlspecialchars($web_kit['telegramChannel'], ENT_QUOTES, 'UTF-8') ?>" class="social-btn telegram" target="_blank" rel="noopener" aria-label="Join our Telegram Channel">
                        <i class="fab fa-telegram"></i>
                        <span>Join Telegram Channel</span>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($web_kit['whatsappGroup'])): ?>
                    <a href="<?= htmlspecialchars($web_kit['whatsappGroup'], ENT_QUOTES, 'UTF-8') ?>" class="social-btn whatsapp" target="_blank" rel="noopener" aria-label="Join our WhatsApp Group">
                        <i class="fab fa-whatsapp"></i>
                        <span>Join WhatsApp Group</span>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($web_kit['youtubeChannel'])): ?>
                    <a href="<?= htmlspecialchars($web_kit['youtubeChannel'], ENT_QUOTES, 'UTF-8') ?>" class="social-btn youtube" target="_blank" rel="noopener" aria-label="Subscribe to our YouTube Channel">
                        <i class="fab fa-youtube"></i>
                        <span>Subscribe on YouTube</span>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($web_kit['facebookPage'])): ?>
                    <a href="<?= htmlspecialchars($web_kit['facebookPage'], ENT_QUOTES, 'UTF-8') ?>" class="social-btn facebook" target="_blank" rel="noopener" aria-label="Follow us on Facebook">
                        <i class="fab fa-facebook"></i>
                        <span>Follow on Facebook</span>
                    </a>
                    <?php endif; ?>
                </div>
            </aside>

            <footer class="post-footer">
                <div class="disclaimer box-style" role="contentinfo">
                    <h4><i class="fas fa-exclamation-triangle"></i> Disclaimer</h4>
                    <p>The Examination Results/Marks published in this Website are for immediate information to the examinees and do not constitute a legal document. While all efforts have been made to ensure the accuracy of information, we are not responsible for any inadvertent errors or inaccuracies in the published results/marks.</p>
                </div>
            </footer>
        </article>
    </div>

    <style>
        .single-post { max-width: 1200px; margin: 2rem auto }
        .post-header { text-align: center; padding: 2rem; margin-bottom: 1.5rem }
        .post-title { font-size: 2.5rem; color: #333; margin-bottom: 1rem }
        .post-meta { color: #666; margin-bottom: 1rem }
        .post-excerpt { font-size: 1.2rem; line-height: 1.6; color: #555 }
        .social-share { text-align: center; padding: 1.5rem }
        .share-buttons { display: flex; justify-content: center; gap: 1rem; list-style: none; padding: 0 }
        .btn-share { display: inline-block; padding: 0.8rem; border-radius: 50%; color: #fff; transition: transform 0.2s }
        .btn-share:hover { transform: scale(1.1) }
        .whatsapp { background: #25D366 }
        .telegram { background: #0088cc }
        .facebook { background: #1877F2 }
        .twitter { background: #1DA1F2 }
        .linkedin { background: #0A66C2 }
        .youtube { background: #FF0000 }
        .post-image { text-align: center; margin: 2rem 0 }
        .post-image img { max-width: 100%; height: auto; border-radius: 8px }
        .post-content { font-size: 1.1rem; line-height: 1.8; color: #333; padding: 2rem }
        .social-connect { padding: 2rem; margin: 2rem 0 }
        .social-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem }
        .social-btn { display: flex; align-items: center; gap: 0.5rem; padding: 1rem; color: #fff; border-radius: 8px; text-decoration: none; transition: transform 0.2s }
        .social-btn:hover { transform: translateY(-2px) }
        .disclaimer { background: #fff8f8; padding: 1.5rem; border-radius: 8px; margin-top: 2rem }
        .disclaimer h4 { color: #dc3545; margin-bottom: 1rem }
        .box-style { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) }
    </style>

<?php 
} else {
    $stmt->close();
    echo "<script>window.location.href='/post/';</script>";
}
include_once("footer.php"); 
?>