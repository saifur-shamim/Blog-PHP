<?php
namespace App\Model;

use PDO;
class Comment extends Post
{
    protected $commentTable = 'comments';
    protected $userTable = 'userdata';

    public function getComments(int $postId): array
    {
        $stmt = $this->db->prepare("
    SELECT 
        {$this->commentTable}.content,
        {$this->commentTable}.id,
        {$this->commentTable}.created_at,
        {$this->userTable}.username AS commenter_name
    FROM 
        {$this->commentTable}
    JOIN 
        {$this->userTable} ON {$this->commentTable}.commenter_id = {$this->userTable}.id
    WHERE 
        {$this->commentTable}.post_id = :post_id
        AND {$this->commentTable}.parent_id is null
    ORDER BY 
        {$this->commentTable}.created_at DESC
");

        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
       // $stmt->bindValue(':parent_id', NULL, PDO::PARAM_NULL);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createComment(array $data): bool
    {
        $stmt = $this->db->prepare("
        INSERT INTO {$this->commentTable} (content, commenter_id, post_id, parent_id) 
        VALUES (:content, :commenter_id, :post_id, :parent_id)
    ");
        return $stmt->execute([
            ':content' => $data['content'],
            ':commenter_id' => $data['commenter_id'],
            ':post_id' => $data['post_id'],
            ':parent_id' => $data['parent_id']
        ]);
    }

    public function getReplies(int $parentId): array
    {
        $stmt = $this->db->prepare("
        SELECT 
            {$this->commentTable}.content,
             {$this->commentTable}.id,
            {$this->commentTable}.created_at,
            {$this->userTable}.username AS commenter_name
        FROM 
            {$this->commentTable}
        JOIN 
            {$this->userTable} ON {$this->commentTable}.commenter_id = {$this->userTable}.id
        WHERE 
            {$this->commentTable}.parent_id = :parent_id
        ORDER BY 
            {$this->commentTable}.created_at ASC
    ");
        $stmt->bindParam(':parent_id', $parentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
