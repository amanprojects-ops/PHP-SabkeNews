<?php
// Extract SEO data
extract($seo);

// Generate JSON-LD schema
$jsonLd = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
<!-- Critical CSS for above-the-fold content -->
<style>
.hero-section {
    @apply w-full py-8 bg-gradient-to-r from-purple-600 to-blue-500 text-white;
}
.post-grid {
    @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4;
}
.post-card {
    @apply bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105;
}
.post-image {
    @apply w-full h-48 object-cover;
}
.post-content {
    @apply p-4;
}
.post-title {
    @apply text-xl font-bold mb-2 text-gray-800 line-clamp-2;
}
.post-date {
    @apply text-sm text-gray-500;
}
</style>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
<?php echo $jsonLd; ?>
</script>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <?php echo htmlspecialchars($settings['websitename']); ?>
        </h1>
        <p class="text-xl opacity-90">
            <?php echo htmlspecialchars($settings['description'] ?? 'Latest Updates and News'); ?>
        </p>
    </div>
</section>

<!-- Latest Posts Grid -->
<div class="container mx-auto my-8">
    <div class="post-grid">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <?php if ($post['image']): ?>
                    <img 
                        src="/assets/postImage/<?php echo htmlspecialchars($post['image']); ?>" 
                        alt="<?php echo htmlspecialchars($post['title']); ?>"
                        class="post-image"
                        loading="lazy"
                        srcset="/assets/postImage/<?php echo htmlspecialchars($post['image']); ?> 1x,
                                /assets/postImage/<?php echo htmlspecialchars($post['image']); ?> 2x"
                    >
                <?php endif; ?>
                
                <div class="post-content">
                    <h2 class="post-title">
                        <a href="/post/<?php echo htmlspecialchars($post['url']); ?>/<?php echo $post['id']; ?>">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h2>
                    <time datetime="<?php echo $post['last_date']; ?>" class="post-date">
                        <?php echo date('F j, Y', strtotime($post['last_date'])); ?>
                    </time>
                    <p class="mt-2 text-gray-600 line-clamp-3">
                        <?php echo htmlspecialchars($post['description']); ?>
                    </p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<!-- Deferred JavaScript for enhanced interactions -->
<script defer>
    // Add intersection observer for lazy loading images
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => observer.observe(img));
    });
</script>