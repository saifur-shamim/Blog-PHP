<?php
namespace App\Model;

use PDO;
use App\Traits\Database;

class Post
{
    use Database;

    protected $db;
    protected $table = 'posts';

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function getAllPosts(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostByID($id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        //return $stmt->fetch(PDO::FETCH_ASSOC);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        return $post ?: [];
    }

    public function createPost(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (title, content, category, author_id) 
             VALUES (:title, :content, :category, :author_id)"
        );
        return $stmt->execute($data);
    }
}
