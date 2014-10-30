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
        if(self::isObj()){
            return true;            
        }        
        self::obj()->mysql = new \mysqli($host, $username, $password, $db);
        return true;
    }
    
    public function insert($table = '', $columns = '', $values = []) {

        $columnsStr = " (" . $columns . ")";
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
        $query = "INSERT INTO " . $table . $columnsStr . $valuesStr;

        return $this->query($query);
    }
    
    public function delete($table = '', $where = ''){
        
        $where = !empty($where) ? ' WHERE ' . $where : '';
        $query = "DELETE FROM " . $table . $where;        
        return $this->query($query);
    }
    
    public function select($table = '', $columns = '*', $where = '', $groupBy = '', $orderBy = '', $sort = 'ASC', $limit1 = 0,  $limit2 = 0){
        
        $where = !empty($where) ? ' WHERE ' . $where : '';
        $groupBy = !empty($groupBy) ? ' GROUP BY ' . $groupBy : '';
        $orderBy = !empty($orderBy) ? ' ORDER BY ' . $orderBy . ' ' . $sort : '';
        $limit = !empty($limit1) && !empty($limit2)? ' LIMIT ' . $limit1 . ', ' . $limit2 : (!empty($limit1) ? ' LIMIT ' . $limit1 : '');
        $query = "SELECT " . $columns . " FROM " . $table . $where . $groupBy . $orderBy . $limit;
        return $this->fetch($query);
    }
    
    public function fetch($query, $type = MYSQLI_ASSOC) {

        $result = $this->runQuery($query);
        return $result ? $result->fetch_all($type) : false;
    }

    public function query($query){
        
        return !$this->runQuery($query) ? false : true;
    }

    private function runQuery($query) {

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
