<?php
namespace App;

require __DIR__ . '/../vendor/autoload.php';


use App\Traits\Database;


use PDOException;

class Migration
{
    use Database;

    private $db;

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function runMigrations()
    {

        try {

            $this->db->exec(" CREATE TABLE IF NOT EXISTS userdata  (
                 id  int NOT NULL AUTO_INCREMENT  PRIMARY KEY,
                 username  varchar(45) NOT NULL,
                 email  varchar(45) NOT NULL,
                 password  varchar(255) NOT NULL
                 )"
            );

            $this->db->exec("
                CREATE TABLE IF NOT EXISTS authors (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    authorname VARCHAR(50) NOT NULL UNIQUE,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");

            $this->db->exec("
                CREATE TABLE IF NOT EXISTS posts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    category VARCHAR(255) NOT NULL,
                    author_id INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");


            $this->db->exec("
                CREATE TABLE IF NOT EXISTS comments (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    content TEXT NOT NULL,
                    commenter_id INT NOT NULL,
                    post_id INT NOT NULL,
                    parent_id INT DEFAULT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (commenter_id) REFERENCES authors(id) ON DELETE CASCADE,
                    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
                )
            ");



        } catch (PDOException $e) {
            die("Migration failed: " . $e->getMessage());
        }


    }


}

