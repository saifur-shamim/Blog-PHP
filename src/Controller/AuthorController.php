<?php

namespace App\Controller;

require __DIR__ . '/../../vendor/autoload.php';

use PDO;
use App\Traits\Database;
class AuthorController
{
    use Database;

    protected $db;
    protected $table = 'authors';

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function getAuthors(): array
    {
        $stmt = $this->db->query("SELECT authorname FROM {$this->table} ORDER BY authorname ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}