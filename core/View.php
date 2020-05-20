<?php

namespace core;

class View {

    private $module;
    private $pageTitle = "";
    private $variables;

    public function __construct($module) {
        $this->module = $module;
    }

    public function setVar($title, $value) {
        $this->variables[$title] = $value;
    }

    public function setPageTitle($title) {
        $this->pageTitle = $title;
    }

    public function render($view) {
        $pageTitle = $this->pageTitle;
        $module = $this->module;

        if (file_exists('../modules/' . $module . '/views/' . $view . 'View.php')) {
            throw new \RuntimeException('Файл вида "' . $view .'View.php" не найден!');
        }

        foreach ($this->variables as $variable => $value) {
            $$variable = $value;
        }
        require_once 'resources/template.php';
    }
}