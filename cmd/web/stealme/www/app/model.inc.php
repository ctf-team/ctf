<?php
/*
    qmvc - A small but powerful MVC framework written in PHP.
    Copyright (C) 2016 ThrDev
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Database {
    private $conn;
    
    public function __construct() {
        try {
            $this->conn = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME), DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());        
        }
    }
    
    public function instance() {
        return $this->conn;
    }
}

class Model {
    private $db;
    private $dbinst;
    //model loader
    public function __construct() {
        $args = func_get_args();
        if(count($args) > 0) {
            $key = "name";
            $this->$key = func_get_arg(0);
        }
        //
        $this->db = $GLOBALS['__mydb'];
        $this->dbinst = $this->db->instance();
    }
    
    public function __getmodelname() {
        return $this->name;
    }
    
    private function __prepared($query, $binds, $return = true) {
        try {

            $stmt = $this->dbinst->prepare($query);
            
            $stmt->execute($binds);
            
            if($stmt->rowCount() <= 0) return false;
            
            if(!$return) return true;
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(!($result = $this->afterFind($result))) return false;
            
            return $result;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    private function __conditions($array) {
        $conditions = array();
        if(array_key_exists('conditions', $array)) {
            $i = 0;
            $conditions[0] = " WHERE ";
            foreach($array['conditions'] as $name => $val) {
                if(strstr($name, " IN")) {
                    //this should be an array.
                    $conditions[0] .= $name." (";
                    foreach($val as $item) {
                        $conditions[0] .= $this->dbinst->quote($item).",";
                    }
                    $conditions[0] = rtrim($conditions[0], ',');
                    $conditions[0] .= ")";
                } else if(strstr($name, '.')) {
                    //multiple tables?
                }else {
                    $conditions[0] .= sprintf("`%s` = ?", $name);
                    $conditions[] = $val;
                }
                
                if($i++ != (count($array['conditions']) - 1)) {
                    $conditions[0] .= " AND ";
                }
            }
        }
        return $conditions;
    }

    /**********************
    * User Callable Functions
    **********************/

    public function toobject($msql) {
        $arr = array();
        foreach ($msql as $entry) {
            $object = new stdClass();
            foreach($entry as $key => $value) {
                $object->$key = $value;
            }
            array_push($arr, $object);
        }
        return $arr;
    }
    
    public function beforeFind($array) {
        //maybe permission-based role check here
        return $array;
    }
    
    public function afterFind($array) {
        //to cleanup data
        return $array;
    }
    
    public function beforeSave($array) {
        //pre-save logic such as modifying dates in a specific format etc
        return $array;
    }

    public function lastinsertid() {
        return $this->dbinst->lastInsertId();
    }
    
    public function find($type = 'all', $array = array()) {
        
        if(($array = $this->beforeFind($array)) === false) return false;
        
        $conditions = $this->__conditions($array);
        
        $fields = "";
        if(array_key_exists('fields', $array)) {
            $fields = implode(',', $array['fields']);
        }
        
        $options = "";
        if(array_key_exists('options', $array)) {
            foreach($array['options'] as $key => $value) {
                $options .= sprintf("%s %s ", strtoupper($key), $value);
            }
        }
        
        switch($type) {
            case 'list': 
                $type = $fields;
                break;
            case 'count':
                $type = 'COUNT(*)';
                break;
            case 'all':
            default: 
                $type = '*';
                break;
        }
        
        $query = sprintf("SELECT %s FROM `%s`%s %s", $type, $this->__getmodelname(), array_shift($conditions), $options);

        return $this->__prepared($query, $conditions);
    }
    
    public function save($array) {
        // UPDATE `users` SET `test`=? WHERE `test`=?
        // INSERT INTO `users` (test,test,test,test) VALUES(?,?,?,?)
        
        if(!array_key_exists('values', $array)) return false;
        
        if(($array = $this->beforeSave($array)) === false) return false;

        if(array_key_exists('conditions', $array)) {
            
            $conditions = $this->__conditions($array);
            
            $values = array('');
            $i = 0;
            foreach($array['values'] as $key => $val) {
                $values[0] .= sprintf('`%s` = ?', $key);
                $values[] = $val;
                if($i++ != count($array['values']) - 1) {
                    $values[0] .= ', ';
                }
            }
            
            $query = sprintf("UPDATE `%s` SET %s%s", $this->__getmodelname(), array_shift($values), array_shift($conditions));
            
            $values = array_merge($values, $conditions);
            
            return $this->__prepared($query, $values, false);
            
        } else {
            
            $values = array('', '');
            $i = 0;
            foreach($array['values'] as $key => $val) {
                $values[0] .= $key;
                $values[1] .= '?';
                $values[] = $val;
                if($i++ != count($array['values']) - 1) {
                    $values[0] .= ',';
                    $values[1] .= ',';
                }
            }
            
            $query = sprintf("INSERT INTO `%s` (%s) VALUES(%s)", $this->__getmodelname(), array_shift($values), array_shift($values));
            
            return $this->__prepared($query, $values, false);
        }
        
        return true;
    }
    
    public function delete($array = array()) {
        
        if(!array_key_exists('conditions', $array) || count($array['conditions']) <= 0) return false;
        
        $conditions = $this->__conditions($array);
        
        $query = sprintf("DELETE FROM `%s` %s", $this->__getmodelname(), $conditions);
        
        $this->__prepared($query, $conditions, false);
        
        return true;
    }
    
    public function query($sql, $array = array(), $return = false) {
        return $this->__prepared($sql, $array, $return);
    }
    
    public function exists($arr) {
        $value = $this->find('all', array('conditions' => $arr));
        return ($value !== false && count($value) > 0);
    }

    public function rawquery($sql) {
        $result = $this->dbinst->query($sql, PDO::FETCH_ASSOC);
        if(!$result || $result->rowCount()) return array();
        if(!($output = $this->afterFind($result))) return false;

        return $output;
    }
    
    public function geterror() {
        return $this->dbinst->errorInfo();
    }
}

$GLOBALS['__mydb'] = new Database();