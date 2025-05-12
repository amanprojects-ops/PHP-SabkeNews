<?php include_once("header.php");
// Sanitize and validate input
$post_title = htmlspecialchars(urlToTitle(mysqli_real_escape_string($conn, $_REQUEST['post'])));

// Prepare and execute query using prepared statements
$query = "SELECT p.*, c.category_name 
          FROM category c 
          LEFT JOIN post p ON c.category_id = p.category 
          WHERE c.category_name = ? AND p.postStatus = 'Y' 
          ORDER BY p.last_update DESC 
          LIMIT 100";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $post_title);
mysqli_stmt_execute($stmt);
$postFetchQ = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($postFetchQ) > 0) {
?>
    <main class="container">
        <article class="content-lists" itemscope itemtype="https://schema.org/Article">
            <h1 class="content-heading" itemprop="headline"><?php echo ucwords($post_title) ?></h1>
            <meta itemprop="description" content="Latest posts in <?php echo $post_title ?> category">
            <hr>
            <div class="list">
                <?php while ($postFetchR = mysqli_fetch_assoc($postFetchQ)) {
                    $post_url = generateUrl($postFetchR['title']);
                    $postid = $postFetchR['post_id'];
                ?>
                    <div class="content-rows" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo $url; ?>/post-details/<?php echo $post_url; ?>/<?php echo $postid; ?>"
                            class="post-link"
                            itemprop="url"
                            title="<?php echo htmlspecialchars($postFetchR['title']); ?>">
                            <span itemprop="name"><?php echo htmlspecialchars($postFetchR['title']); ?></span>
                            <br>
                            <time datetime="<?php echo $postFetchR['last_update']; ?>" itemprop="datePublished">
                                Post Updated: <?php echo date('F j, Y', strtotime($postFetchR['last_update'])); ?>
                            </time>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </article>
    </main>

    <style>
        .post-link {
            font-weight: 700;
            color: #5b5c5c;
            display: block;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .post-link:hover {
            background-color: #f5f5f5;
        }

        .post-link time {
            color: #394c68;
            font-size: 0.9em;
        }
    </style>
<?php
}
include_once("footer.php"); ?>