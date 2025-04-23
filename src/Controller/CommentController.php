<?php

namespace App\Controller;

require __DIR__ . '/../../vendor/autoload.php';


use App\Model\Comment;


class CommentController
{

    protected $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function getComments($postId): array
    {
        return $this->commentModel->getComments($postId) ?? [];
    }

    public function createComment(): bool
    {
        session_start();
        $id = $_SESSION['user_id'];

        $data = [
            'content' => $_POST['content'],
            'commenter_id' => $id,
            'post_id' => $_POST['post_id'],
            'parent_id' => $_POST['parent_id'] ?? null
        ];

        return $this->commentModel->createComment($data);
    }

    public function getReplies($parentId): array
    {
        return $this->commentModel->getReplies($parentId) ?? [];
    }
}