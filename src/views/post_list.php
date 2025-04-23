<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: /Blog/src/views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e0eafc);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .blog-header {
            text-align: center;
            padding: 40px 0;
            color: #343a40;
        }

        .card {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #6c757d;
            color: white;
        }

        .card-body p {
            color: #495057;
        }

        .meta {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .single-post {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between">
        <a href="/Blog/src/views/create_post.php" class="btn btn-primary mb-4 p-2 ">➕ Create New Post</a>

        <span class="me-3 fw-bold text-dark">Welcome, <?php echo $_SESSION['username']; ?>!</span>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Menu
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="./view_profile.php">My Profile </a></li>
                <li><a class="dropdown-item" href="./change_password.php">Change Password</a></li>
                <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
            </ul>
        </div>

    </div>

    <div class="blog-header">
        <h1 class="display-5 fw-bold">📝 My Blog</h1>
    </div>

    <?php
    require_once __DIR__ . '/../../vendor/autoload.php';
    use App\Controller\PostController;

    function limit_words($text, $limit) {
        if (is_array($text)) {
            $text = implode("\n", $text);
        }

        $words = preg_split('/\s+/', $text);
        $limited = implode(' ', array_slice($words, 0, $limit));
        if (count($words) > $limit) {
            $limited .= '...';
        }
        return nl2br(htmlspecialchars($limited));
    }

    $postModel = new PostController();
    $posts = $postModel->getPosts();

    foreach ($posts as $post): ?>
        <div class="card mb-4">
            <a class="single-post" href="view_post.php?id=<?= $post['id'] ?>">
                <div class="card-header">
                    <h4 class="mb-0"><?= htmlspecialchars($post['title']) ?></h4>
                </div>
                <div class="card-body">
                    <p><?= limit_words($post['content'], 21); ?></p>
                    <hr>
                    <div class="meta">
                        <span>👤 Author ID: <?= $post['author_id'] ?></span> |
                        <span>💻 Category: <?= $post['category'] ?></span> |
                        <span>🕒 <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
