<?php

namespace core;

abstract class MainController
{
    protected $module;
    protected $view;
    protected $model;
    protected $params = [];
    protected $arguments = [];

    public function __construct($module, $model, $params, $arguments) {
        $this->module = $module;
        $this->model = $model;
        $this->params = $params;
        $this->arguments = $arguments;

        $this->view = new View($this->module);
    }


}