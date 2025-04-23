<?php

namespace App\Traits;
require __DIR__ . '/../../vendor/autoload.php';



use PDO;
use PDOException;

trait Database
{
    public function connection()
    {
        $servername = 'localhost';
        $username = "root";
        $password = "";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=blog_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            echo "Connected successfully<br>";
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}

