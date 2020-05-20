<?php

namespace core\classes;

use PDO;

class DB {

    static private $request;
    static private $pdo;


    public static function query($sql) {
        $pdo = DbConnect::getInstance()->getConnection();

        return $pdo->query($sql);
    }

    public static function fetch($sql, $type = PDO::FETCH_ASSOC) {
        $pdo = DbConnect::getInstance()->getConnection();

        return $pdo->query($sql)->fetch($type);
    }
    static public function fetchAll($sql, $type = PDO::FETCH_ASSOC) {
        $pdo = DbConnect::getInstance()->getConnection();

        return $pdo->query($sql)->fetchAll($type);
    }

    public static function select($table) {
        self::$request = new Request($table, 'select');
        return self::$request;
    }

    public static function insert($table) {
        self::$request = new Request($table, 'insert');
        return self::$request;
    }
    public static function update($table) {
        self::$request = new Request($table, 'update');
        return self::$request;
    }

    public static function delete($table) {
        self::$request = new Request($table, 'delete');
        return self::$request;
    }

    public static function getLastInsertId() {
        $pdo = DbConnect::getInstance()->getConnection();

        return $pdo->lastInsertId();
    }
}