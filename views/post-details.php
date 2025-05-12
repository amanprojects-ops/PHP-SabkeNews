<?php
// Extract SEO data
extract($seo);

// Generate JSON-LD schema
$jsonLd = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
<!-- Critical CSS for post details -->
<style>
.post-header {
    @apply w-full py-8 bg-gradient-to-r from-purple-600 to-blue-500 text-white;
}
.post-content-area {
    @apply prose prose-lg max-w-none mt-8;
}
.post-meta {
    @apply flex items-center text-sm text-gray-500 space-x-4;
}
.share-buttons {
    @apply flex space-x-4 mt-6;
}
.share-button {
    @apply p-2 rounded-full transition-colors;
}
.related-posts {
    @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12;
}
</style>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
<?php echo $jsonLd; ?>
</script>

<!-- Post Header -->
<header class="post-header">
    <div class="container mx-auto px-4">
        <nav class="text-sm mb-4">
            <a href="/" class="text-white opacity-80 hover:opacity-100">Home</a>
            <?php if (isset($post['category_name'])): ?>
                <span class="mx-2">/</span>
                <a href="/category/<?php echo htmlspecialchars($post['category_url']); ?>" class="text-white opacity-80 hover:opacity-100">
                    <?php echo htmlspecialchars($post['category_name']); ?>
                </a>
            <?php endif; ?>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <?php echo htmlspecialchars($post['title']); ?>
        </h1>
        <div class="post-meta">
            <time datetime="<?php echo $post['last_date']; ?>">
                <?php echo date('F j, Y', strtotime($post['last_date'])); ?>
            </time>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8">
    <?php if ($post['featured_image']): ?>
        <figure class="mb-8">
            <picture>
                <source 
                    srcset="/assets/postImage/<?php echo htmlspecialchars($post['featured_image']); ?>.webp"
                    type="image/webp"
                >
                <img 
                    src="/assets/postImage/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                    alt="<?php echo htmlspecialchars($post['title']); ?>"
                    class="w-full h-auto rounded-lg shadow-lg"
                    loading="eager"
                >
            </picture>
        </figure>
    <?php endif; ?>

    <article class="post-content-area">
        <?php echo $post['content']; ?>
    </article>

    <!-- Social Share Buttons -->
    <div class="share-buttons">
        <a 
            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($canonical); ?>" 
            target="_blank"
            rel="noopener noreferrer"
            class="share-button bg-blue-600 text-white hover:bg-blue-700"
            aria-label="Share on Facebook"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
        </a>
        <a 
            href="https://twitter.com/intent/tweet?url=<?php echo urlencode($canonical); ?>&text=<?php echo urlencode($post['title']); ?>" 
            target="_blank"
            rel="noopener noreferrer"
            class="share-button bg-blue-400 text-white hover:bg-blue-500"
            aria-label="Share on Twitter"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.58v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
        </a>
        <a 
            href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($canonical); ?>&title=<?php echo urlencode($post['title']); ?>" 
            target="_blank"
            rel="noopener noreferrer"
            class="share-button bg-blue-700 text-white hover:bg-blue-800"
            aria-label="Share on LinkedIn"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
    </div>

    <!-- Related Posts -->
    <?php if (!empty($related_posts)): ?>
    <section class="mt-16">
        <h2 class="text-3xl font-bold mb-8">Related Posts</h2>
        <div class="related-posts">
            <?php foreach ($related_posts as $related): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ($related['image']): ?>
                        <img 
                            src="/assets/postImage/<?php echo htmlspecialchars($related['image']); ?>" 
                            alt="<?php echo htmlspecialchars($related['title']); ?>"
                            class="w-full h-48 object-cover"
                            loading="lazy"
                        >
                    <?php endif; ?>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">
                            <a href="/post/<?php echo htmlspecialchars($related['url']); ?>/<?php echo $related['id']; ?>" class="hover:text-blue-600">
                                <?php echo htmlspecialchars($related['title']); ?>
                            </a>
                        </h3>
                        <time datetime="<?php echo $related['date']; ?>" class="text-sm text-gray-500">
                            <?php echo date('F j, Y', strtotime($related['date'])); ?>
                        </time>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Deferred JavaScript for enhanced interactions -->
<script defer>
    // Add smooth scrolling to content
    document.addEventListener('DOMContentLoaded', () => {
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>