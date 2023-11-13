<?php

    require '../vendor/autoload.php';
    use Dotenv\Dotenv;
    
    $path = dirname(__FILE__, 2);
    $dotenv = Dotenv::createImmutable($path);
    $dotenv->load();

    class Connection {
        public $con;
        public function __construct() {
            $dsn = "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            
            try {
                $this->con = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
?>