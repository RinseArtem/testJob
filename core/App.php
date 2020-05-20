<?php
namespace core;


class App {

    private static $module;
    private static $page;
    private static $params;
    private static $arguments;
    private static $router;

    public static function init() {
        self::ProcessAddressBar();

        spl_autoload_register('self::autoLoad');

        self::bootstrap();
    }

    private static function autoLoad($className) {
        $classFile = self::normalizePath($className . '.php');


        if (file_exists($classFile)) { // Загрузка классов ядра
            require_once $classFile;
        } elseif (file_exists($classFile)) { // Загрузка классов модулей
            require_once $classFile;
        }
        elseif (file_exists($classFile)) { // Загрузка системных классов
            require_once $classFile;
        } else {
            throw new \RuntimeException('Класс "' . $className . '" не найден!');
        }
    }

    private static function bootstrap() {
        session_start();
        self::$router = new Route(self::$module, self::$page, self::$params, self::$arguments);
        self::$router->start();
    }

    private static function ProcessAddressBar() {
        $barExplode = explode('?', $_SERVER['REQUEST_URI']);
        $bar = explode('/', $barExplode[0]);

        unset($bar[0]);

        self::$module = array_shift($bar);
        self::$page = array_shift($bar);
        self::$params = $bar;
        self::$arguments = $_GET;
    }

    private static function normalizePath( $path ) {
        $path = str_replace( '\\', '/', $path );
        $path = preg_replace( '|(?<=.)/+|', '/', $path );
        if ( ':' === substr( $path, 1, 1 ) ) {
            $path = ucfirst( $path );
        }
        return $path;
    }
}