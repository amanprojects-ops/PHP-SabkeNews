<?php
namespace App\Controllers;

class PostListController extends BaseController {
    private $postsPerPage = 12;

    public function index($page = 1) {
        return $this->getPosts($page);
    }

    public function category($categoryUrl, $page = 1) {
        // Get category info
        $query = "SELECT * FROM category WHERE url = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $categoryUrl);
        mysqli_stmt_execute($stmt);
        $category = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$category) {
            $this->redirect('/');
        }

        return $this->getPosts($page, ['category_id' => $category['category_id']], $category);
    }

    public function search($page = 1) {
        $search = $_GET['q'] ?? '';
        if (empty($search)) {
            $this->redirect('/');
        }

        return $this->getPosts($page, ['search' => $search]);
    }

    private function getPosts($page = 1, $filters = [], $category = null) {
        $offset = ($page - 1) * $this->postsPerPage;
        
        // Build cache key based on filters
        $cacheKey = 'posts:' . $page;
        if (isset($filters['category_id'])) {
            $cacheKey .= ':category:' . $filters['category_id'];
        }
        if (isset($filters['search'])) {
            $cacheKey .= ':search:' . md5($filters['search']);
        }

        // Try to get cached data
        $cachedData = $this->redis->get($cacheKey);
        if ($cachedData) {
            $data = json_decode($cachedData, true);
            $posts = $data['posts'];
            $totalPosts = $data['total_posts'];
        } else {
            // Build query
            $where = ['postStatus = "Y"'];
            $params = [];
            $types = '';

            if (isset($filters['category_id'])) {
                $where[] = 'category_id = ?';
                $params[] = $filters['category_id'];
                $types .= 'i';
            }

            if (isset($filters['search'])) {
                $where[] = '(title LIKE ? OR content LIKE ?)';
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $types .= 'ss';
            }

            // Count total posts
            $countQuery = "SELECT COUNT(*) as total FROM post WHERE " . implode(' AND ', $where);
            $stmt = mysqli_prepare($this->conn, $countQuery);
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $totalPosts = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

            // Get posts
            $query = "SELECT p.*, c.name as category_name, c.url as category_url 
                      FROM post p 
                      LEFT JOIN category c ON p.category_id = c.category_id 
                      WHERE " . implode(' AND ', $where) . "
                      ORDER BY last_date DESC 
                      LIMIT ? OFFSET ?";
            
            $stmt = mysqli_prepare($this->conn, $query);
            $types .= 'ii';
            $params[] = $this->postsPerPage;
            $params[] = $offset;
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $posts = [];
            while ($post = mysqli_fetch_assoc($result)) {
                $posts[] = [
                    'id' => $post['post_id'],
                    'title' => $post['title'],
                    'url' => $this->generateUrl($post['title']),
                    'description' => substr(strip_tags($post['content']), 0, 160),
                    'last_date' => $post['last_date'],
                    'image' => $post['featured_image'],
                    'category_name' => $post['category_name'],
                    'category_url' => $post['category_url']
                ];
            }

            // Cache the results for 1 hour
            $this->redis->set($cacheKey, json_encode([
                'posts' => $posts,
                'total_posts' => $totalPosts
            ]), 'EX', 3600);
        }

        // Get all categories for filter
        $categories = mysqli_query($this->conn, "SELECT * FROM category ORDER BY name");
        $categoriesList = mysqli_fetch_all($categories, MYSQLI_ASSOC);

        // Get website settings
        $settings = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM settings"));

        // Prepare SEO metadata
        $title = $settings['websitename'];
        if ($category) {
            $title = $category['name'] . ' - ' . $title;
        } elseif (isset($filters['search'])) {
            $title = 'Search: ' . $filters['search'] . ' - ' . $title;
        } else {
            $title .= ' - Blog Posts';
        }

        $seoData = [
            'title' => $title,
            'description' => $settings['description'] ?? '',
            'og_type' => 'website',
            'og_image' => $settings['logo'] ?? '',
            'canonical' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $title,
                'description' => $settings['description'] ?? '',
                'url' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
            ]
        ];

        return $this->view('post', [
            'posts' => $posts,
            'categories' => $categoriesList,
            'category' => $category,
            'search' => $filters['search'] ?? '',
            'current_page' => $page,
            'total_pages' => ceil($totalPosts / $this->postsPerPage),
            'settings' => $settings,
            'seo' => $seoData
        ]);
    }
}