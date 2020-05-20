<?php
namespace core;

class Route {
    private $module = 'index';
    private $page = 'index';
    private $params;
    private $arguments;
    private $controller;
    private $model;


    function __construct($module, $page, $params, $arguments) {
        if (!empty($module)) {
            $this->module = strtolower($module);
        }

        if (!empty($page)) {
            $this->page = strtolower($page);
        }

        if (!empty($params)) {
            $this->params = $params;
        }

        if (!empty($arguments)) {
            $this->arguments = $arguments;
        }

        $this->controller = ucfirst(strtolower($this->module)) . 'Controller';
        $this->model = ucfirst(strtolower($this->module)) . 'Model';
    }

    public function start() {
        if(!$this->moduleExist() || !$this->controllerExist() ) {
            $this->errorPage404();
        }

        $model = false;
        if ($this->modelExist()) {
            require_once 'modules/' . $this->module . '/' . $this->model . '.php';
            $model = new $this->model;
        }

        require_once 'modules/' . $this->module . '/' . $this->controller . '.php';

        $controller = new $this->controller($this->module, $model, $this->params, $this->arguments);
        $controller->{$this->page} ();

    }

    private function errorPage404() {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';

        header('Location:' . $host.ERROR_PAGE_404);
    }

    private function moduleExist() {
        if (file_exists('modules/' . $this->module)) {
            return true;
        }
        return false;
    }

    private function controllerExist() {
        if (file_exists('modules/' . $this->module . '/' . $this->controller . '.php')) {
            return true;
        }
        return false;
    }
    private function modelExist() {
        if (file_exists('modules/' . $this->module . '/' . $this->model . '.php')) {
            return true;
        }
        return false;
    }

}