<?php

use core\classes\DB;
use core\MainModel;


class IndexModel extends MainModel {

    private $countPages;
    private $currentPage;
    private $sortBy = 'id';
    private $way = 'ASC';

    public function getTasks($page = 1) {
        $this->countPages = (((DB::select('tasks')->fetchCount() - 1) / 3) + 1);


        $this->currentPage = $page;
        if ($page < 1) {
            $this->currentPage = 1;
        } elseif ($page > $this->countPages) {
            $this->currentPage = $this->countPages;
        }
        $limit = $this->currentPage * 3 - 3;

        $request = DB::select('tasks')
            ->orderBy($this->sortBy, $this->way)
            ->limit(3, $limit);

        return $request->fetchAll();
    }

    public function getCountPages() {
        return $this->countPages;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setSort($by, $way) {
        $this->sortBy = $by;
        $this->way = $way;
    }

    public function addTask($user, $mail, $description) {
        DB::insert('tasks')
            ->fieldsValues([
                'user' => $user,
                'mail' => $mail,
                'description' => $description
            ])->make();
    }

    public function updateTask($id, $user, $mail, $description, $status) {
        DB::update('tasks')
            ->set('user', $user)
            ->set('mail', $mail)
            ->set('description', $description)
            ->set('status', $status)
            ->whereEquals('id', $id)
            ->make();
    }


}