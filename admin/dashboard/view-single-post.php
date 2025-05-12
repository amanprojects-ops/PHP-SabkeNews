<?php
require_once '../../system/connection.php';
if (isset($_GET['id'])):
    $post_id = base64_decode($_GET['id']);
    $sql = "SELECT * FROM post WHERE post_id = $post_id LIMIT 1";
    $result = $conn->query($sql);
    if ($conn->affected_rows > 0):
        $post = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }

        .post-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .post-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        .post-content {
            color: #444;
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }

        .post-container:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            .post-container {
                padding: 20px;
            }
            
            .post-title {
                font-size: 1.5rem;
            }
            
            .post-content {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="post-container">
        <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="post-content"><?php echo $post['description']; ?></div>
        <div class="post-footer">
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($post['post_date'])); ?></p>
        </div>
    </div>
</body>
</html>
<?php endif; endif; ?>
