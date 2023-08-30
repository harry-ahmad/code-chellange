<?php

namespace App\Database;

use PDO;

class Database {
    /** @var Database $connection  */
    private static $instance;
    /** @var PDO $connection  */
    private $connection;

    private function __construct(){
        if(empty(self::$instance)){
            try{
                $this->connection = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(\Exception $e) {
                echo "Connection failed : " . $e->getMessage();
            }
        }
    }

    public static function getInstance(): Database{
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

}