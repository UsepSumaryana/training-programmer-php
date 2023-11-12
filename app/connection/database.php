<?php

namespace App\Connection;

use PDO;
use Config;

class Database
{

    private $host;
    private $name;
    private $user;
    private $pass;
    public $conn;

    public function __construct()
    {
        $config = new Config();
        $this->host = $config::DB_HOST;
        $this->name = $config::DB_NAME;
        $this->user = $config::DB_USER;
        $this->pass = $config::DB_PASS;
        $this->conn = $this->connect();
    }

    public function connect()
    {
        try {

            $pdo =  new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($pdo) {
                return $pdo;
            } else {
                echo 'Database connection Failure';
            }
        } catch (\PDOException  $e) {

            echo $e->getMessage();
        }
    }

}
