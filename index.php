<?php include_once("header.php");
$web_kit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings")); ?>
<div class="container box-style welcome-section">
    <h1 class="welcome-heading animate__animated animate__fadeInDown">
        Welcome to <?php echo htmlspecialchars($web_kit['websitename'], ENT_QUOTES); ?> 
        <span class="year"><?php echo date("Y"); ?></span>
    </h1>

    <div class="news-ticker animate__animated animate__fadeInUp">
        <div class="ticker-content">
            WELCOME TO SABKE NEWS UPDATED BIHAR - Your Trusted Source for Bihar News
        </div>
    </div>

    <!-- Add required CSS -->
    <style>
        /* Import animate.css */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

        .welcome-section {
            padding: 2rem 0;
        }

        .welcome-heading {
            text-align: center;
            color: #8100f3;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .news-ticker {
            overflow: hidden;
            position: relative;
            background: rgba(129, 0, 243, 0.1);
            padding: 1rem;
            border-radius: 8px;
        }

        .ticker-content {
            white-space: nowrap;
            animation: ticker 20s linear infinite;
            font-size: 1.5rem;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        @media (max-width: 768px) {
            .welcome-heading {
                font-size: 1.5rem;
            }
            .ticker-content {
                font-size: 1.2rem;
            }
        }

        .color1 {
            background-color: #3973fa;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color2 {
            background-color: #d339fa;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color3 {
            background-color: #ff9b20;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color4 {
            background-color: #ff286f;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color5 {
            background-color: #07a26c;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color6 {
            background-color: #ff570f;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color7 {
            background-color: #38ceff;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }

        .color8 {
            background-color: #2e67d9;
            background-image: linear-gradient(120deg, rgba(255, 255, 255, .1), rgba(0, 0, 0, .45));
        }
    </style>
</div>

<div class="container box-style">
    <?php 
    // Define colors array as a constant to avoid recreation
    $COLORS = ["color1", "color2", "color3", "color4", "color5", "color6", "color7", "color8"];
    
    // Use prepared statement for better security
    $limit = 8;
    $stmt = mysqli_prepare($conn, "SELECT post_id, title FROM `post` WHERE postStatus = 'Y' ORDER BY post_id DESC LIMIT ?");
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    ?>

    <div class="row">
        <?php
        $i = 0;
        while ($post = mysqli_fetch_assoc($result)) {
            // Sanitize output and improve readability
            $postId = htmlspecialchars($post['post_id'], ENT_QUOTES);
            $title = htmlspecialchars(substr($post['title'],0,54));
            $postUrl = generateUrl($title);
            $colorClass = $COLORS[$i % count($COLORS)]; // Use modulo to cycle through colors
            // Use heredoc for better HTML readability
            echo <<<HTML
            <div class="tab-res {$colorClass}">
                <a href="{$url}/post-details/{$postUrl}/{$postId}" 
                   aria-labelledby="{$title}"
                   aria-label="{$title}"
                   rel="bookmark"
                   title="{$title}">
                    {$title}
                </a>
            </div>
            HTML;
            
            $i++;
        }
        mysqli_stmt_close($stmt);
        ?>
    </div>
</div>
<main class="container box-style" role="main">
    <div class="row">
        <?php
        // Use prepared statement to get active categories count
        $stmt = mysqli_prepare($conn, "SELECT COUNT(category_id) as count FROM category WHERE categoryStatus = 'Y'");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $categoryCount = mysqli_fetch_assoc($result)['count'];
        mysqli_stmt_close($stmt);

        $limit = 1;
        $catCounter = ceil($categoryCount / $limit);
        $start = 0;

        for ($b = 1; $b <= $catCounter; $b++): ?>
            <section class="content-lists" itemscope itemtype="http://schema.org/ItemList">
                <?php
                // Prepare and execute category query
                $stmt = mysqli_prepare($conn, "SELECT category_id, category_name FROM category WHERE categoryStatus = 'Y' LIMIT ?, ?");
                mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
                mysqli_stmt_execute($stmt);
                $categoryResult = mysqli_stmt_get_result($stmt);

                while ($categoryData = mysqli_fetch_assoc($categoryResult)):
                    $category_id = htmlspecialchars($categoryData['category_id'], ENT_QUOTES);
                    $category_name = htmlspecialchars($categoryData['category_name'], ENT_QUOTES);
                    $category_url = generateUrl($category_name);
                ?>
                    <h2 class="content-heading" itemprop="name"><?= $category_name ?></h2>
                    <div class="list" role="list">
                        <?php
                        // Prepare and execute posts query
                        $stmt2 = mysqli_prepare($conn, "SELECT post_id, title, last_update FROM post WHERE category = ? AND postStatus = 'Y' LIMIT 10");
                        mysqli_stmt_bind_param($stmt2, "s", $category_id);
                        mysqli_stmt_execute($stmt2);
                        $postsResult = mysqli_stmt_get_result($stmt2);

                        while ($post = mysqli_fetch_assoc($postsResult)):
                            $post_title = htmlspecialchars($post['title'], ENT_QUOTES);
                            $post_url = generateUrl($post_title);
                            $post_id = htmlspecialchars($post['post_id'], ENT_QUOTES);
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
                        mysqli_stmt_close($stmt2);
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
                mysqli_stmt_close($stmt);
                ?>
            </section>
        <?php endfor; ?>
    </div>
</main>
<?php include_once("footer.php"); ?>
