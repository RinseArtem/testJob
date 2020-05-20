<?php

namespace core\classes;

use PDO;

class Request {

    private $sql;
    private $table;
    private $fields = '*';
    private $whereArr;
    private $whereSQL;
    private $whereRequest = [];
    private $whereRequestsCondition;
    private $setArr;
    private $valuesArr;
    private $orderBy;
    private $sortBy;
    private $limit;
    private $offset;

    // Плейсхолдеры для формирования SQL запроса
    private $placeholders = ['%table%', '%fields%', '%where%', '%set%', '%values%', '%orderBy%', '%limit%'];

    /**
     * Request constructor.
     * Обращение происходит внутри класса DB
     *
     * @param $table - таблица с которой будем работать
     * @param $type - тип запроса
     * @example DB::select('users');
     */
    function __construct(string $table, string $type) {
        $this->table = $table;
        $this->createRequestTemplate($type);
    }


    /**
     * Формирует конструкцию WHERE внутри скобок типа:
     *     (user = 1 OR user = 2) AND (privilege = 1 OR privilege = 1)
     *
     * @return RequestWhere
     */
    public function whereOr():RequestWhere {
        return new RequestWhere('OR');
    }
    public function whereAnd():RequestWhere {
        return new RequestWhere('AND');
    }

    /**
     * Возвращает готовую конструкцию WHERE
     *
     * @param RequestWhere $whereRequest
     * @param string $condition
     */
    public function getCondition(RequestWhere $whereRequest, string $condition = 'AND') {
        $this->whereRequest[] = $whereRequest->get();
        $this->whereRequestsCondition = $condition;
    }


    /**
     * Позволяет создавать простую конструкцию WHERE типа: WHERE user = 10
     *
     * @param string $column        - поле
     * @param string $statement     - логический оператор
     * @param string $value         - значение
     * @param string $condition     - условие AND или OR
     * @return Request
     *
     * @example DB::select('users')->whereEquals('id', 10);
     */
    public function where(string $column, string $statement, string $value, string $condition = 'AND'):Request {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => $statement,
            'value' => "'$value'",
            'condition' => $condition
        ];
        return $this;
    }
    public function whereEquals(string $column, string $value, string $condition = 'AND'):Request {
        $this->where($column, '=', $value, $condition);
        return $this;
    }
    public function whereLike(string $column, string $value, string $condition = 'AND'):Request {
        $this->where($column, 'LIKE', $value, $condition);
        return $this;
    }
    public function whereNotEquals(string $column, string $value, string $condition = 'AND'):Request {
        $this->where($column, '<>', "$value'", $condition);
        return $this;
    }
    public function whereIn(string $column, string $value, string $condition = 'AND'):Request {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => 'IN',
            'value' => "($value)",
            'condition' => $condition
        ];
        return $this;
    }
    public function whereNotIn(string $column, string $value, string $condition = 'AND'):Request {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => 'NOT IN',
            'value' => "($value)",
            'condition' => $condition
        ];
        return $this;
    }

    public function whereSQL(string $sql) {
        $this->whereSQL[] = $sql;
    }

    /**
     * Формирования блока SET для запроса UPDATE
     *
     * @param string $column
     * @param string $value
     * @return Request
     *
     * @example DB::update('users')->set('year', 20);
     */
    public function set(string $column, string $value):Request {
        $this->setArr[] = [
            'column' => $column,
            'value' => $value
        ];
        return $this;
    }

    /**
     * Формирования списка полей для запросов SELECT и INSERT
     *
     * @param array $arrayFields
     * @return Request
     *
     * @example DB::select('users')->fields(['id', 'name'])
     */
    public function fields(array $arrayFields):Request {
        if (count($arrayFields) > 0) {
            foreach ($arrayFields as $field) {
                $fields[] =  "`$field`";
            }
            $this->fields = implode(', ', $fields);
        }
        return $this;
    }

    /**
     * Формирование блока ORDER BY для запроса SELECT
     *
     * @param string $orderBy
     * @param string $sortBy
     * @return Request
     *
     * @example DB:select('users')->orderBy('id', 'DESC');
     */
    public function orderBy(string $orderBy = 'id', string $sortBy = 'ASC'):Request {
        $this->orderBy = $orderBy;
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * Формирование блока LIMIT OFFSET для запроса SELECT
     *
     * @param int $limit
     * @param int $offset
     * @return Request
     *
     * @example DB:select('users')->limit(10, 20);
     */
    public function limit(int $limit, int $offset = 0):Request {
        $this->limit = $limit;
        if ($offset > 0) {
            $this->offset = $offset;
        }

        return $this;
    }

    /**
     * Формирование блоков INTO и VALUES для запроса INSERT
     *
     * @param array $kv
     * @return Request
     *
     * @example DB:insert('users')->fieldsValues( ['name' => 'Иван', 'year' => 20] );
     */
    public function fieldsValues(array $kv):Request {
        foreach ($kv as $field => $value) {
            $fields[] = "`$field`";
            $values[] = $value;
        }
        if ($this->fields == '*' ) {
            $this->fields = implode(', ', $fields);
        }
        $this->valuesArr[] = $values;
        return $this;
    }


    /**
     * Выполняет формированый запрос получая запрашиваемые данные
     *
     * @param $type     - Тип запроса PDO
     * @return mixed
     */
    public function fetch($type = PDO::FETCH_ASSOC ) {
        return DB::fetch($this->generateSQL(), $type);
    }
    public function fetchAll( $type = PDO::FETCH_ASSOC ) {
        return DB::fetchAll($this->generateSQL(), $type);
    }
    public function fetchCount($column = '*') {
        $this->fields = "COUNT($column) as count";
        return (DB::fetch($this->generateSQL(true)))['count'];
    }
    public function make() { // Производит запрос без возвращения данных
        return DB::query($this->generateSQL());
    }

    /**
     * Возвращает сформированный запрос SQL
     * @return string
     *
     * @example DB::select('users')->whereEquals('id', 1)->getSQL();
     */
    public function getSQL():string {
        return $this->generateSQL();
    }


    private function getSetSQL() {
//        if (count($this->setArr) > 0) {
        if (!empty($this->setArr)) {
            $updateSQL = '';
            foreach ($this->setArr as $set) {
                $updateSQL .= "`$set[column]` = '$set[value]', ";
            }
            return substr($updateSQL, 0, -2);
        }
        return 1;
    }

    private function getValuesSQL() {
        if (!empty($this->valuesArr)) {
            $valuesSQL = '';
            foreach ($this->valuesArr as $values) {
                $valuesStr = '';
                foreach ($values as $value) {
                    $valuesStr .= "'$value', ";
                }
                $valuesStr = substr($valuesStr, 0, -2);
                $valuesSQL .= "($valuesStr), ";
            }
            return substr($valuesSQL, 0, -2);
        }

        return false;
    }
    private function getWhereSQL() {
        $sql = '';
//        if (count($this->whereArr) > 0) {
        if (!empty($this->whereArr)) {
            foreach ($this->whereArr as $where) {
                $sql .= "$where[column] $where[statement] $where[value]  $where[condition] ";
            }
        }
//        if (count($this->whereRequest) > 0) {
        if (!empty($this->whereRequest)) {
            foreach ($this->whereRequest as $request) {
                $condition = $this->whereRequestsCondition;
                $sql .= "$request $condition";
            }
        }

        if (!empty($this->whereSQL)) {
            foreach ($this->whereSQL as $whereSQL) {
                $sql .= "AND $whereSQL";
            }
        }

        if ($sql != '') {
            return substr($sql, 0, -4);
        }
        return 1;
    }


    private function getOrderBySQL() {
        if (!empty($this->orderBy)) {
            return "ORDER BY $this->orderBy $this->sortBy";
        }
        return false;
    }

    private function getLimitSQL() {
        if (!empty($this->limit)) {
            $offset = '';
            if (!empty($this->offset)) {
                $offset = "$this->offset ,";
            }
            return "LIMIT $offset $this->limit";
        }

        return false;
    }

    private function createRequestTemplate($type) {
        switch ($type) {
            case 'select' :
                $this->sql = 'SELECT %fields% FROM %table% WHERE %where% %orderBy% %limit%';
                break;

            case 'insert' :
                $this->sql = 'INSERT INTO %table% (%fields%) VALUES %values%';
                break;

            case 'update' :
                $this->sql = 'UPDATE %table% SET %set% WHERE %where%';
                break;

            case 'delete' :
                $this->sql = 'DELETE FROM %table% WHERE %where% ';
                break;
        }
    }
    private function generateSQL($count = false) {
        // ['%table%', '%fields%', '%where%', '%set%', '%values%', '%orderBy%', '%limit%']
        $replace = [
            $this->table,
            $this->fields,
            $this->getWhereSQL(),
            $this->getSetSQL(),
            $this->getValuesSQL(),
            $this->getOrderBySQL(),
            ($count) ? null : $this->getLimitSQL(),
        ];
        return str_replace($this->placeholders, $replace, $this->sql);
    }
}