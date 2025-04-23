<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controller\CommentController;
use App\Controller\PostController;

session_start();


$commentModel = new CommentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentModel->createComment();
    $postId = $_POST['post_id'] ?? 0;
    header("Location: /Blog/src/views/view_post.php?id=" . $postId);
    exit();
}

$post = [];
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id) {
    $postModel = new PostController();
    $post = $postModel->getPostByID($id);
}

function renderReplies($parentId, $commentModel, $postId)
{
    $replies = $commentModel->getReplies($parentId);

    if ($replies):

        foreach ($replies as $reply): ?>
            <div class="comment p-3  mt-1 rounded bg-light border-start border-primary">
                <div class="fw-semibold"><?= htmlspecialchars($reply['commenter_name']) ?></div>
                <div><?= nl2br(htmlspecialchars($reply['content'])) ?></div>
                <div>
                    <span class="text-muted small font-monospace">
                        <?= date('F j, Y, g:i a', strtotime($reply['created_at'])) ?>
                    </span>
                </div>

                <form action="" method="POST" class="mt-2">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
                    <input type="hidden" name="parent_id" value="<?= htmlspecialchars($parentId) ?>">
                    <div class="mb-2 ">
                        <textarea class="form-control" name="content" rows="2" placeholder="Write a reply..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary ">Reply</button>
                </form>

                <?php
                renderReplies($reply['id'], $commentModel, $postId);
                ?>
            </div>
        <?php endforeach;?>

   <?php endif;
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog - Post Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .post-card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .post-title {
            font-size: 2rem;
            font-weight: 600;
        }

        .post-meta {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-top: 1.5rem;
        }

        .meta-label {
            font-weight: 500;
        }

        .comment-section {
            border-top: 1px solid #dee2e6;
            padding-top: 2rem;
        }

        .comment-list .comment {
            border-left: 4px solid #0d6efd;
            background-color: #f8f9fa;
        }

        .comment .fw-semibold {
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between">

        <a href="/Blog/src/views/post_list.php" class="btn btn-success mb-4 p-2">Home</a>

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


    <?php if (!empty($post)): ?>
        <div class="post-card bg-white">
            <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>

            <div class="post-meta mb-4">
                <div><span class="meta-label">Author ID:</span> <?= htmlspecialchars($post['author_id']) ?></div>
                <div><span class="meta-label">Category:</span> <?= htmlspecialchars($post['category']) ?></div>
                <div><span class="meta-label">Created at:</span> <?= htmlspecialchars($post['created_at']) ?></div>
                <div><span class="meta-label">Last updated:</span> <?= htmlspecialchars($post['updated_at']) ?></div>
            </div>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <!-- Comment Section -->
            <div class="comment-section mt-5">
                <h4 class="mb-4">Leave a Comment</h4>

                <form action="" method="POST" class="mb-5">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <div class="mb-3">
                        <textarea class="form-control" id="comment_content" name="content" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Comment</button>
                </form>

                <!-- Get All Comments -->
                <?php

                $comments = $commentModel->getComments($post['id']);
                if ($comments):
                    foreach ($comments as $comment): ?>
                        <div class="comment-list mb-3">
                            <div class="comment p-3 rounded">
                                <div class="fw-semibold mb-1"><?= htmlspecialchars($comment['commenter_name']) ?></div>
                                <div><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
                                <div>
                                    <span class="text-muted small font-monospace">
                                        <?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?>
                                    </span>
                                </div>


                                <!-- Replies -->
                                <?php
                                $replies = $commentModel->getReplies($comment['id']); ?>
                                <form action="" method="POST" class="mt-3">
                                    <input type="hidden" name="post_id"
                                           value="<?= htmlspecialchars($post['id']) ?>">
                                    <input type="hidden" name="parent_id"
                                           value="<?= htmlspecialchars($comment['id']) ?>">
                                    <div class="mb-2 ms-2">
                                                        <textarea class="form-control" name="content" rows="2"
                                                                  placeholder="Write a reply..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        Reply
                                    </button>
                                </form>

                                <?php
                                if ($replies):
                                    renderReplies($comment['id'], $commentModel, $post['id']);

                                 ?>

                              <?php  endif;
                                ?>

                            </div>
                        </div>
                    <?php endforeach;
                else: ?>
                    <div class="text-muted">No comments yet.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Post not found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>