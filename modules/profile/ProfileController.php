<?php

use core\classes\Auth;
use core\classes\Filter;
use core\classes\Notice;
use core\MainController;


class ProfileController extends MainController {

    public function login() {
        $login = Filter::string($_POST['login'], 'Логин введен неверно!');
        $password = Filter::string($_POST['password'], 'Пароль введен неверно!');

        if ($login && $password) {
            if (Auth::logIn($_POST['login'], $_POST['password'])) {
                Notice::addSuccess('Авторизация прошла успешно!');
            } else {
                Notice::addDanger('Пользователь не найден!');
            }
        }


        Header('Location: /');
    }

    public function logout() {
        Auth::logOut();
        Notice::addSuccess('Выход произведен!');

        Header('Location: /');
    }
}