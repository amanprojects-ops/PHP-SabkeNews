<footer class="footer-wrapper">
    <div class="container">
        <div class="footer-grid">
            <!-- About Section -->
            <div class="footer-section">
                <h2 class="footer-heading">About Us</h2>
                <div class="footer-divider"></div>
                <p class="website-name"><?= strtoupper($web_kit['websitename']); ?></p>
                <p class="website-about"><?= $web_kit['websiteAbout'] ?></p>
                <div class="social-links">
                    <a href="#" class="social-icon" aria-label="Facebook" rel="noopener"><i
                            class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter" rel="noopener"><i
                            class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram" rel="noopener"><i
                            class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Latest Posts Section -->
            <div class="footer-section">
                <h2 class="footer-heading">Latest Posts</h2>
                <div class="footer-divider"></div>
                <ul class="footer-posts">
                    <?php
                                        $shortPost = mysqli_query($conn, "SELECT * FROM post WHERE postStatus ='Y' ORDER BY post_id DESC LIMIT 0,5");
                                        if (mysqli_num_rows($shortPost) > 0):
                                                while ($post = mysqli_fetch_assoc($shortPost)):
                                                        $txt = generateUrl($post['title']);
                                                        $title = substr($post['title'], 0, 60);
                                                        $postid = $post['post_id'];
                                        ?>
                    <li class="footer-post-item">
                        <a href="<?= "{$url}/post-details/{$txt}/{$postid}" ?>" class="footer-link"
                            aria-label="<?= htmlspecialchars($title) ?>">
                            <i class="fas fa-angle-right"></i> <?= htmlspecialchars($title) ?>
                        </a>
                    </li>
                    <?php
                                                endwhile;
                                        else:
                                                echo '<li class="no-posts">No Records Found</li>';
                                        endif;
                                        ?>
                </ul>
            </div>

            <!-- Quick Links Section -->
            <div class="footer-section">
                <h2 class="footer-heading">Quick Links</h2>
                <div class="footer-divider"></div>
                <ul class="footer-links">
                    <li><a href="<?= $url ?>/" class="footer-link" aria-label="Home"><i class="fas fa-home"></i>
                            Home</a></li>
                    <li><a href="<?= $url ?>/aboutus" class="footer-link" aria-label="About Us"><i
                                class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="<?= $url ?>/contactus" class="footer-link" aria-label="Contact Us"><i
                                class="fas fa-envelope"></i> Contact Us</a></li>
                    <li><a href="<?= $url ?>/disclaimer" class="footer-link" aria-label="Disclaimer"><i
                                class="fas fa-exclamation-circle"></i> Disclaimer</a></li>
                    <li><a href="<?= $url ?>/privacy-policy" class="footer-link" aria-label="Privacy Policy"><i
                                class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                    <li><a href="<?= $url ?>/sitemap_html" class="footer-link" aria-label="Site Map"><i
                                class="fas fa-sitemap"></i> Site Map</a></li>
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

<!-- Footer styles moved to external CSS file -->
<script>
// Defer loading of non-critical resources
document.addEventListener('DOMContentLoaded', function() {
    // Load Font Awesome asynchronously if needed
    if (typeof FontAwesome === 'undefined') {
        var fontAwesome = document.createElement('link');
        fontAwesome.rel = 'stylesheet';
        fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css';
        fontAwesome.media = 'print';
        fontAwesome.onload = function() {
            this.media = 'all';
        };
        document.head.appendChild(fontAwesome);
    }

    // Load footer CSS
    var footerCSS = document.createElement('link');
    footerCSS.rel = 'stylesheet';
    footerCSS.href = '<?=$url?>/assets/css/footer.css';
    document.head.appendChild(footerCSS);
});
</script>
<style>
        .footer-wrapper {
    background: #222222; /* Dark background */
    color: #f1f1f1; /* Light text color for better contrast */
    padding: 3rem 0;
}

.website-name {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #ffffff; /* Ensuring better visibility */
}

.website-about {
    line-height: 1.6;
    margin-bottom: 1.5rem;
    color: #e0e0e0; /* Slightly lighter grey for better contrast with background */
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-icon {
    color: #f1f1f1; /* Lighter color for icons */
    font-size: 1.5rem;
    transition: color 0.3s ease;
}

.social-icon:hover {
    color: #3498db; /* Change color on hover */
}

.footer-posts,
.footer-links {
    list-style: none;
    padding: 0;
}

.footer-post-item,
.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-link {
    color: #f1f1f1; /* Ensure readability against dark background */
    text-decoration: none;
    transition: color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.footer-link:hover {
    color: #3498db; /* Color change on hover for better visibility */
}

.copyright {
    background: #111111; /* Darker background for copyright */
    padding: 1.5rem 0;
    text-align: center;
}

.copyright p {
    margin: 0.5rem 0;
    color: #e0e0e0; /* Light grey text for copyright */
}

.creator-link {
    color: #3498db; /* Blue color for creator link */
    text-decoration: none;
}

.creator-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .footer-wrapper {
        padding: 2rem 0 0; /* Reduced padding for smaller screens */
    }

    .footer-grid {
        grid-template-columns: 1fr; /* Single column layout for mobile */
    }
}

</style>

<script src="https://kit.fontawesome.com/d0b3cb21b9.js" crossorigin="anonymous"></script>
<?php mysqli_close($conn); include_once 'visitor-counter.php'; ?>

</body>

</html>