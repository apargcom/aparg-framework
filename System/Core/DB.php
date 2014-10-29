<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class DB extends Singleton {

    private $mysql = null;
    public $lastID = 0;
    public $error = '';

    public static function init($host, $username, $password, $db) {

        self::obj()->mysql = new \mysqli($host, $username, $password, $db);
        return true;
    }

    public function insert($table = '', $columns = [], $values = []) {

        $columnsStr = "(" . implode(',', array_values($columns)) . ")";
        if (is_array($values[0])) {
            $valuesStr = 'VALUES ';
            foreach ($values as $row) {
                $row = $this->escape($row);
                $valuesStr.= "('" . implode("','", array_values($row)) . "'), ";
            }
            $valuesStr = rtrim($valuesStr, ', ');
        } else {
            $row = $this->escape($values);
            $valuesStr = "VALUES ('" . implode("','", array_values($row)) . "')";
        }
        $query = "INSERT INTO " . $table . " " . $columnsStr . " " . $valuesStr;

        return $this->query($query);
    }

    public function fetch($query) {

        $result = $this->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }

    private function query($query) {

        $result = $this->mysql->query($query);
        $this->lastID = $this->mysql->insert_id;
        $this->error = $this->mysql->error;
        return $result;
    }

    public function escape($values = []) {

        if (is_array($values)) {
            return array_map(function($value) {
                return $this->mysql->real_escape_string($value);
            }, $values);
        } else {
            return $this->mysql->real_escape_string($values);
        }
    }

}
