<?php
// Extract SEO data
extract($seo);

// Generate JSON-LD schema
$jsonLd = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
<!-- Critical CSS for post listing -->
<style>
.post-listing-header {
    @apply w-full py-8 bg-gradient-to-r from-purple-600 to-blue-500 text-white;
}
.post-filters {
    @apply flex flex-wrap gap-4 items-center mb-8;
}
.search-form {
    @apply flex-1 max-w-lg;
}
.search-input {
    @apply w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent;
}
.category-filter {
    @apply px-4 py-2 rounded-lg bg-white text-gray-700 border border-gray-300 hover:bg-gray-50;
}
.post-grid {
    @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6;
}
.post-card {
    @apply bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105;
}
.pagination {
    @apply flex justify-center gap-2 mt-8;
}
.page-link {
    @apply px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50;
}
.page-link.active {
    @apply bg-blue-500 text-white border-blue-500;
}
</style>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
<?php echo $jsonLd; ?>
</script>

<!-- Post Listing Header -->
<header class="post-listing-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Blog Posts</h1>
        <?php if (isset($category)): ?>
            <p class="text-xl opacity-90">Category: <?php echo htmlspecialchars($category['name']); ?></p>
        <?php endif; ?>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8">
    <!-- Filters Section -->
    <div class="post-filters">
        <form action="/search" method="GET" class="search-form">
            <input 
                type="search" 
                name="q" 
                placeholder="Search posts..." 
                value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                class="search-input"
                aria-label="Search posts"
            >
        </form>
        
        <div class="flex gap-2 overflow-x-auto pb-2">
            <?php foreach ($categories as $cat): ?>
                <a 
                    href="/category/<?php echo htmlspecialchars($cat['url']); ?>" 
                    class="category-filter <?php echo isset($category) && $category['category_id'] === $cat['category_id'] ? 'bg-blue-500 text-white' : ''; ?>"
                >
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Posts Grid -->
    <?php if (!empty($posts)): ?>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <?php if ($post['image']): ?>
                        <picture>
                            <source 
                                srcset="/assets/postImage/<?php echo htmlspecialchars($post['image']); ?>.webp"
                                type="image/webp"
                            >
                            <img 
                                src="/assets/postImage/<?php echo htmlspecialchars($post['image']); ?>" 
                                alt="<?php echo htmlspecialchars($post['title']); ?>"
                                class="w-full h-48 object-cover"
                                loading="lazy"
                            >
                        </picture>
                    <?php endif; ?>
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2">
                            <a 
                                href="/post/<?php echo htmlspecialchars($post['url']); ?>/<?php echo $post['id']; ?>" 
                                class="hover:text-blue-600"
                            >
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($post['description']); ?></p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <time datetime="<?php echo $post['last_date']; ?>">
                                <?php echo date('F j, Y', strtotime($post['last_date'])); ?>
                            </time>
                            <?php if (isset($post['category_name'])): ?>
                                <a 
                                    href="/category/<?php echo htmlspecialchars($post['category_url']); ?>" 
                                    class="text-blue-600 hover:underline"
                                >
                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>" class="page-link" aria-label="Previous page">&laquo; Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a 
                        href="?page=<?php echo $i; ?>" 
                        class="page-link <?php echo $current_page === $i ? 'active' : ''; ?>"
                        aria-label="Page <?php echo $i; ?>"
                        <?php echo $current_page === $i ? 'aria-current="page"' : ''; ?>
                    >
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>" class="page-link" aria-label="Next page">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-12">
            <p class="text-xl text-gray-600">No posts found.</p>
        </div>
    <?php endif; ?>
</main>

<!-- Deferred JavaScript for enhanced interactions -->
<script defer>
    // Add smooth scrolling and lazy loading
    document.addEventListener('DOMContentLoaded', () => {
        // Lazy load images
        const images = document.querySelectorAll('img[loading="lazy"]');
        if ('loading' in HTMLImageElement.prototype) {
            images.forEach(img => {
                img.src = img.src;
            });
        } else {
            // Fallback for browsers that don't support lazy loading
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
            images.forEach(img => {
                img.classList.add('lazyload');
            });
        }

        // Smooth scroll to top when changing pages
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (!e.ctrlKey && !e.shiftKey && !e.metaKey && !e.altKey) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    });
</script>