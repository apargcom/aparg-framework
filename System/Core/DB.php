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
    public $query = '';

    public static function load($host, $username, $password, $db) {
        if (self::isObj()) {
            return self::obj();
        }
        self::obj()->mysql = new \mysqli($host, $username, $password, $db);        
        return self::obj();
    }

    public function insert($table = '', $columns = [], $values = []) {

        $columnsStr = !empty($columns) ? "(" . implode(',', array_values($columns)) . ")" : "";
        if(!empty($values)){
            if (is_array($values[0])) {
                $valuesStr = 'VALUES ';
                foreach ($values as $row) {
                    $row = $this->escape($row);
                    $valuesStr.= "('" . implode("','", array_values($row)) . "'), ";
                }
                $valuesStr = rtrim($valuesStr, ', ');
            } else {
                $row = $this->escape($values);
                $valuesStr = " VALUES ('" . implode("','", array_values($row)) . "')";
            }
        }else{
            $valuesStr = "";
        }
        $query = "INSERT INTO " . $table . $columnsStr . $valuesStr;

        return $this->query($query);
    }

    public function update($table = '', $columns = [], $values = [], $where = '') {
        
        if(!empty($columns) && !empty($values)){
            if (is_array($columns) && is_array($values)) {

                $set = '';
                $row = $this->escape($values);
                foreach ($columns as $key => $column) {
                    $set.= $column . "='" . $row[$key] . "',";
                }
                $set = " SET " . rtrim($set, ",");
            } else if(!is_array($columns) && !is_array($values)){
                $value = $this->escape($values);
                $set = " SET " . $columns . "='" . $value . "'";
            }else{
                $set = '';
            }
        }else{
            $set = '';
        }
        $where = !empty($where) ? ' WHERE ' . $where : '';
        $query = "UPDATE " . $table . $set . $where;

        return $this->query($query);
    }

    public function delete($table = '', $where = '') {

        $where = !empty($where) ? ' WHERE ' . $where : '';
        $query = "DELETE FROM " . $table . $where;
        return $this->query($query);
    }

    public function select($table = '', $columns = [], $joins = [], $where = '', $groupBy = '', $orderBy = '', $sort = 'ASC', $limit1 = 0, $limit2 = 0) {

        $columns = !empty($columns) ? implode(',', array_values($columns)) : '*';
        if (!empty($joins)) {
            if (!is_array($joins)) {
                $joinStr = ' INNER JOIN ' . $joins;
            } else {
                if (is_array($joins[0])) {
                    $joinStr = '';
                    foreach ($joins as $join) {
                        $joinStr.= (empty($join[0]) ? ' INNER' : ' ' . strtoupper($join[0])) . ' JOIN ' . $join[1] . ' ON ' . $join[2];
                    }
                } else {
                    $joinStr = (empty($joins[0]) ? ' INNER' : ' ' . strtoupper($joins[0])) . ' JOIN ' . $joins[1] . ' ON ' . $joins[2];
                }
            }
        } else {
            $joinStr = '';
        }
        $where = !empty($where) ? ' WHERE ' . $where : '';
        $groupBy = !empty($groupBy) ? ' GROUP BY ' . $groupBy : '';
        $orderBy = !empty($orderBy) ? ' ORDER BY ' . $orderBy . ' ' . $sort : '';
        $limit = !empty($limit1) && !empty($limit2) ? ' LIMIT ' . $limit1 . ', ' . $limit2 : (!empty($limit1) ? ' LIMIT ' . $limit1 : '');
        $query = "SELECT " . $columns . " FROM " . $table . $joinStr . $where . $groupBy . $orderBy . $limit;
        return $this->fetch($query);
    }

    public function fetch($query, $type = MYSQLI_ASSOC) {

        $result = $this->runQuery($query);
        return $result ? $result->fetch_all($type) : false;
    }

    public function query($query) {

        return !$this->runQuery($query) ? false : true;
    }

    private function runQuery($query) {

        $result = $this->mysql->query($query);
        $this->lastID = $this->mysql->insert_id;
        $this->error = $this->mysql->error;
        $this->query = $query;
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
