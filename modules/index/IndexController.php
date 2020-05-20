<?php


use core\classes\Auth;
use core\classes\Filter;
use core\classes\Notice;
use core\MainController;


class IndexController extends MainController {

    public function index() {
        if (empty($_SESSION['sort'])) {
            $_SESSION['sort']['by'] = 'id';
            $_SESSION['sort']['way'] = 'ASC';
        }

        if (!empty($this->arguments['by'])) {
            if ($this->arguments['by'] == $_SESSION['sort']['by'] && $_SESSION['sort']['way'] == 'ASC') {
                $_SESSION['sort']['way'] = 'DESC';
            } else {
                $_SESSION['sort']['by'] = $this->arguments['by'];
                $_SESSION['sort']['way'] = 'ASC';
            }

        }
        $this->model->setSort($_SESSION['sort']['by'], $_SESSION['sort']['way']);


        $tasks = $this->model->getTasks($this->arguments['page'] ?? 0);
        $countPages = $this->model->getCountPages();
        $currentPage = $this->model->getCurrentPage();

        $this->view->setPageTitle('Список задач');
        $this->view->setVar('tasks', $tasks);
        $this->view->setVar('countPages', $countPages);
        $this->view->setVar('currentPage', $currentPage);
        $this->view->setVar('sortBy', $_SESSION['sort']['by']);
        $this->view->setVar('way', strtolower($_SESSION['sort']['way']));

        $this->view->render('index');
    }

    public function new() {
        $user = Filter::string($_POST['user'], 'Логин введен не корректно!');
        $mail = Filter::mail($_POST['mail'], 'E-Mail введен не корректно!');
        $description = Filter::string($_POST['description'], 'Описиние введено не корректно!');

        if ($user && $mail && $description) {
            $this->model->addTask($user, $mail, $description);
            Notice::addSuccess('Задача успешно добавлена!');
        }

        header('Location: /');
    }

    public function edit() {
        if (!Auth::is()) {
            Notice::addDanger('Для выполнения операции нужно авторизоваться!');
        } else {
            $user = Filter::string($_POST['user'], 'Логин введен не корректно!');
            $mail = Filter::mail($_POST['mail'], 'E-Mail введен не корректно!');
            $description = Filter::string($_POST['description'], 'Описиние введено не корректно!');

            $this->model->updateTask($this->params[0], $user,  $mail, $description, !empty($_POST['status']) ? 1 : 0);
            Notice::addSuccess('Задача успешно обновлена!');
        }

        header('Location: /');
    }
}