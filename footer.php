<footer class="footer-wrapper">
    <div class="container">
        <div class="footer-grid">
            <!-- About Section -->
            <div class="footer-section">
                <h2 class="footer-heading">About Us</h2>
                <div class="footer-divider"></div>
                <p class="website-name"><?= strtoupper($web_kit['websitename']) ?></p>
                <p class="website-about"><?= htmlspecialchars($web_kit['websiteAbout'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <div class="social-links">
                    <a href="#" class="social-icon" aria-label="Facebook" rel="noopener"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter" rel="noopener"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram" rel="noopener"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Latest Posts Section -->
            <div class="footer-section">
                <h2 class="footer-heading">Latest Posts</h2>
                <div class="footer-divider"></div>
                <ul class="footer-posts">
                    <?php
                    $stmt = $conn->prepare("SELECT post_id, title FROM post WHERE postStatus = 'Y' ORDER BY post_id DESC LIMIT 5");
                    $stmt->execute();
                    $footerPosts = $stmt->get_result();
                    
                    if ($footerPosts->num_rows > 0):
                        while ($post = $footerPosts->fetch_assoc()):
                            $postUrl = generateUrl($post['title']);
                            $postTitle = htmlspecialchars(substr($post['title'], 0, 60), ENT_QUOTES, 'UTF-8');
                    ?>
                    <li class="footer-post-item">
                        <a href="<?= "{$url}/post-details/{$postUrl}/{$post['post_id']}" ?>" class="footer-link"
                            aria-label="<?= $postTitle ?>">
                            <i class="fas fa-angle-right"></i> <?= $postTitle ?>
                        </a>
                    </li>
                    <?php
                        endwhile;
                    else:
                        echo '<li class="no-posts">No Records Found</li>';
                    endif;
                    $stmt->close();
                    ?>
                </ul>
            </div>

            <!-- Quick Links Section -->
            <div class="footer-section">
                <h2 class="footer-heading">Quick Links</h2>
                <div class="footer-divider"></div>
                <ul class="footer-links">
                    <li><a href="<?= $url ?>/" class="footer-link" aria-label="Home"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?= $url ?>/aboutus" class="footer-link" aria-label="About Us"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="<?= $url ?>/contactus" class="footer-link" aria-label="Contact Us"><i class="fas fa-envelope"></i> Contact Us</a></li>
                    <li><a href="<?= $url ?>/disclaimer" class="footer-link" aria-label="Disclaimer"><i class="fas fa-exclamation-circle"></i> Disclaimer</a></li>
                    <li><a href="<?= $url ?>/privacy-policy" class="footer-link" aria-label="Privacy Policy"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                    <li><a href="<?= $url ?>/sitemap_html" class="footer-link" aria-label="Site Map"><i class="fas fa-sitemap"></i> Site Map</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="copyright">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= strtoupper($web_kit['websitename']) ?> - All Rights Reserved</p>
            <p>Designed with <i class="fas fa-heart"></i> by <a href="https://devbin.site" class="creator-link"
                    rel="noopener" target="_blank">Technical Aman</a></p>
        </div>
    </div>
</footer>

<!-- External CSS & JS -->
<link rel="stylesheet" href="<?= $url ?>/assets/css/footer.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></noscript>

<?php 
// Close DB connection & log visitor
mysqli_close($conn);
include_once __DIR__ . '/visitor-counter.php';
?>

</body>
</html>