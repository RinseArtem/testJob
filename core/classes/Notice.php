<?php


namespace core\classes;


class Notice {

    public static function addSuccess($msg) {
        self::add('success', $msg);
    }
    public static function addDanger($msg) {
        self::add('danger', $msg);
    }
    public static function addInfo($msg) {
        self::add('info', $msg);
    }

    public static function add($type, $msg) {
        $_SESSION['notice'][] =[
            'type' => $type,
            'msg' => $msg
        ];
    }

    public static function get() {
        return $_SESSION['notice'] ?? [];
    }

    public static function clear() {
        unset($_SESSION['notice']);
    }
}