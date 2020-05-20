<?php


namespace core\classes;


class Auth {

    public static function is() {
        return(!empty($_SESSION['user']));
    }

    public static function logIn($login, $password) {
        if (!self::is()) {
            $user = DB::select('users')
                ->whereEquals('login', $login)
                ->whereEquals('password', $password)
                ->fetch();

            if (!empty($user)) {
                $_SESSION['user'] = $login;
                return true;
            }
        }
        return false;
    }

    public static function logOut() {
        unset($_SESSION['user']);
    }
}