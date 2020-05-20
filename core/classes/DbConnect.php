<?php

namespace core\classes;

use PDO;

class DbConnect {


    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $user = 'db user-name';
    private $pass = 'db password';
    private $name = 'db name';

    private function __construct() {
        $this->connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_TABLE , DB_USER, DB_PASS,
            [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}