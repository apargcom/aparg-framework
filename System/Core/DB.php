<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * DB class is for working with Database
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 */
class DB extends Singleton {

    /**
     * @var object Contains object which represents the connection to a database server
     */
    private $mysql = null;
    /**
     * @var integer ID of last insert row
     */
    public $lastID = 0;
    /**
     * @var string Contains database error
     */
    public $error = '';
    /**
     * @var string Last query that was executed
     */
    public $query = '';

    /**
     * Initialize the DB
     *      
     * @return boolean True on success
     */
    public function init() {

        $this->mysql = new \mysqli(Config::obj()->get('db_host'), Config::obj()->get('db_username'), Config::obj()->get('db_password'), Config::obj()->get('db_name'));    
        return true;
    }

    /**
     * Generate INSERT query
     * 
     * @param string $table Name of the table
     * @param array $columns Contains names of the columns Ex.:['col1','col2']
     * @param type $values Contains values to insert Ex.:[['val1','val2'],['val3','val4']]
     *                     For one row Ex.:['val1','val2']
     * @return boolean True on success, false on fail
     * @see query()
     */
    public function insert($table = '', $columns = [], $values = []) {

        $columnsStr = !empty($columns) ? " (" . implode(',', array_values($columns)) . ")" : "";
        if(!empty($values)){
            if (is_array($values[0])) {
                $valuesStr = ' VALUES';
                foreach ($values as $row) {
                    $row = $this->escape($row);
                    $valuesStr.= " ('" . implode("','", array_values($row)) . "'), ";
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

    /**
     * Generate UPDATE query
     * 
     * @param string $table Name of the table
     * @param array $columns Contains names of the columns Ex.:['col1','col2']
     * @param array $values Contains values to update Ex.:['val1','val2']
     * @param string $where Contains WHERE clause
     * @return boolean True on success, false on fail
     * @see query()
     */
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
        $where = ($where != '') ? ' WHERE ' . $where : '';
        
        $query = "UPDATE " . $table . $set . $where;
        return $this->query($query);
    }

    /**
     * Generate UPDATE query
     * 
     * @param string $table Name of the table
     * @param string $where Contains WHERE clause
     * @return boolean True on success, false on fail
     * @see query()
     */
    public function delete($table = '', $where = '') {

        $where = ($where != '') ? ' WHERE ' . $where : '';
        
        $query = "DELETE FROM " . $table . $where;
        return $this->query($query);
    }

    /**
     * Generate SELECT query
     * 
     * @param string $table Name of the table
     * @param array $columns Contains names of the columns Ex.:['col1','col2']. If empty array passed all columns are selected
     * @param array $joins Contains JOIN clause which 0 element is join type(INNER,LEFT OR RIGHT), 1 element is table name and 2 element is ON clause.
     *                     Ex.:[['INNER','table1','col1=col2'],['LEFT','table2','col3=col4']]
     *                     For one JOIN clause Ex.:['INNER','table1','col1=col2']
     * @param string $where Contains WHERE clause
     * @param string $groupBy Contains GROUP BY clause
     * @param string $orderBy Contains ORDER BY clause
     * @param string $sort Contains SORT clause
     * @param integer $limit1 Contains LIMIT clause offset
     * @param integer $limit2 Contains LIMIT clause count
     * @return boolean|array Associative array with selected values, false on fail
     * @see fetch()
     */
    public function select($table = '', $columns = [], $joins = [], $where = '', $groupBy = '', $orderBy = '', $sort = 'ASC', $limit1 = null, $limit2 = null) {

        $columns = !empty($columns) ? implode(',', array_values($columns)) : '*';
        if (!empty($joins)) {
            if (!is_array($joins)) {
                $joinStr = ' INNER JOIN ' . $joins;
            } else {
                if (is_array($joins[0])) {
                    $joinStr = '';
                    foreach ($joins as $join) {
                        $joinStr.= (($join[0] == '') ? ' INNER' : ' ' . strtoupper($join[0])) . ' JOIN ' . $join[1] . ' ON ' . $join[2];
                    }
                } else {
                    $joinStr = (($joins[0] == '') ? ' INNER' : ' ' . strtoupper($joins[0])) . ' JOIN ' . $joins[1] . ' ON ' . $joins[2];
                }
            }
        } else {
            $joinStr = '';
        }
        $where = ($where != '') ? ' WHERE ' . $where : '';
        $groupBy = ($groupBy != '') ? ' GROUP BY ' . $groupBy : '';
        $orderBy = ($orderBy != '') ? ' ORDER BY ' . $orderBy . ' ' . $sort : '';
        $limit = (!is_null($limit1) && !is_null($limit2)) ? ' LIMIT ' . $limit1 . ', ' . $limit2 : (!is_null($limit1) ? ' LIMIT ' . $limit1 : '');   
        
        $query = "SELECT " . $columns . " FROM " . $table . $joinStr . $where . $groupBy . $orderBy . $limit;
        return $this->fetch($query);
    }

    /**
     * Call runQuery() and return array with selected values
     * 
     * @param string $query Query to run
     * @param integer $type Type of fetching(MYSQLI_ASSOC, MYSQLI_NUM, or MYSQLI_BOTH)
     * @return array Array with selected values, false on fail
     * @see runQuery()
     */
    public function fetch($query, $type = MYSQLI_ASSOC) {

        $result = $this->runQuery($query);
        if ($result == false) {
            return false;
        }        
        $fetchedResult = [];
        while ($row = $result->fetch_array($type)) {
            $fetchedResult[] = $row;
        }
        return $fetchedResult;
    }

    /**
     * Call runQuery()
     * 
     * @param string $query Query to run
     * @return boolean True on success, false on fail
     * @see runQuery()
     */
    public function query($query) {

        return ($this->runQuery($query) == false) ? false : true;
    }

    /**
     * Runs given query and return result
     * 
     * @param string $query Query to run
     * @return object mysqli_result object
     * @see mysqli_result
     */
    private function runQuery($query) {

        $result = $this->mysql->query($query);
        $this->lastID = $this->mysql->insert_id;
        $this->error = $this->mysql->error;
        $this->query = $query;
        return $result;
    }
    
    /**
     * Escapes special characters
     * 
     * @param string|array $values Values to escape
     * @return string|array Escaped data
     */
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