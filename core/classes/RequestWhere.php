<?php

namespace core\classes;

class RequestWhere {

    private $condition;
    private $whereArr = [];

    function __construct($condition) {
        $this->condition = $condition;
    }

    public function where($column, $statement, $value) {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => $statement,
            'value' => "'$value'",
            'condition' => $this->condition
        ];
        return $this;
    }
    public function whereEquals($column, $value) {
        $this->where($column, '=', $value);
        return $this;
    }
    public function whereLike($column, $value) {
        $this->where($column, 'LIKE', $value);
        return $this;
    }
    public function whereNotEquals($column, $value) {
        $this->where($column, '<>', "$value'");
        return $this;
    }
    public function whereIn($column, $value) {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => 'IN',
            'value' => "($value)",
            'condition' => $this->condition
        ];
        return $this;
    }
    public function whereNotIn($column, $value) {
        $this->whereArr[] = [
            'column' => $column,
            'statement' => 'NOT IN',
            'value' => "($value)",
            'condition' => $this->condition
        ];
        return $this;
    }

    public function get() {
        $whereSQL = $this->getWhereSQL();
        return "($whereSQL)";
    }

    private function getWhereSQL() {
        if (count($this->whereArr) > 0) {
            $sql = '';
            foreach ($this->whereArr as $where) {
                $sql .= "$where[column] $where[statement] $where[value]  $where[condition] ";
            }

            return substr($sql, 0, -4);
        }
        return 1;
    }
}