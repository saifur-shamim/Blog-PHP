<?php
namespace App\Controller;

require __DIR__ . '/../../vendor/autoload.php';

use App\Model\Post;

class PostController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function getPosts(): array
    {
        return $this->postModel->getAllPosts();
    }

    public function getPostByID($id): array
    {
        return $this->postModel->getPostByID($id);
    }

    public function createPost():bool
    {
        session_start();
        $id = $_SESSION['user_id'];

        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'author_id' => $id,
            'category' => $_POST['category']
        ];

        return $this->postModel->createPost($data);
    }
}
