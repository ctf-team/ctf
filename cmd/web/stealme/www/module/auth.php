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

class Auth {
    public $uses = array("model" => array("users", "permissions"));

    private $_user_info = array('_filled' => false);
    private $_old_user_info = null;
    
    public function __construct() {
    }
    
    public function __onload() {
        if(USE_MEMCACHED) {
            if (!isset($_SESSION['auth'])) {
                $_SESSION['auth'] = $this;
            } else {
                $this->_user_info = $_SESSION['auth']->_user_info;
            }
        } else {
            if(!isset($_SESSION['auth'])) { return; }
            $row = $this->users->find("all", array("conditions" => array(
                "email" => $_SESSION['auth']['email'],
                "id" => $_SESSION['auth']['id'],
            )));
            if(count($row) > 0) {
                $this->_user_info = $row[0];
                $this->_user_info['_filled'] = true;
                $this->_old_user_info = $_SESSION['oldauth'];
            }
        }
    }
    
    private function save() {
        if(USE_MEMCACHED) {
            $_SESSION['auth'] = $this;
        } else {
            $_SESSION['oldauth'] = $this->_old_user_info;
            $_SESSION['auth'] = array();
            $_SESSION['auth']['id'] = $this->_user_info['id'];
            $_SESSION['auth']['email'] = $this->_user_info['email'];
        }
    }
    
    public function __sleep()
    {
        return array('_user_info');
    }
    
    public function user()
    {
        return $this->_user_info;
    }
    
    public function check()
    {
        return $this->_user_info['_filled'];
    }
    
    public function logout()
    {
        $this->_user_info = array('_filled' => false);
        
        $params = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() - 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        
        $this->save();
    }

    public function remember_me()
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    } 

    public function refresh() {
        if (!$this->check()) return;
        
        $id = $this->user()['id'];
        $out = $this->users->find("all", array("conditions" => array("id" => $id), "options" => array("LIMIT" => 1)));
        $this->_user_info = $out[0];
        $this->_user_info['_filled'] = true;
        $this->save();
    }
    
    public function has_permission($permission) {
        if (!$this->check()) return false;
        
        $permData = $this->permissions->find("all", array("conditions" => array(
            "permission" => $permission,
        )));
        
        if(count($permData) > 0) {
            $permData = $permData[0];
            if ($permData['min_rank'] <= $this->user()['rank'])
                return true;
        }
    
        return false;
    }
   
    public function attempt($args)
    {
        if (!is_array($args)) return false;
        if (!isset($args['password'])) return false;
        
        $row = $this->users->find("all", array(
            "conditions" => array("email" => $args["email"]), 
            "options" => array("limit" => 1)
        ));
        
        if (!$row) {
        } else {
            if (password_verify($args['password'], $row[0]['password'])) {
                $this->_user_info = $row[0];
                $this->_user_info['_filled'] = true;
                $this->save();
                return true;
            }
            return false;
        }
        return false;
    }

    public function register($args) {
        $out = $this->users->find("all", array("conditions" => array("email" => $args["email"])));
        if(!$out) {
            //create a new user record
            //setup password.
            $args["password"] = $this->bcrypt($args["password"]);
            
            return $this->users->save(array("values" => $args));
            
        } else {
            return false;
        }
    }

    public function is_impersonating() {
        return $this->_old_user_info !== null;
    }

    public function impersonate($userid) {
        $row = $this->users->find("all", array(
            "conditions" => array("id" => $userid)
        ));
        $this->_old_user_info = $this->_user_info;
        $this->_user_info = $row[0];
        $this->_user_info['_filled'] = true;
        $this->save();
    }

    public function endimpersonation() {
        if($this->_old_user_info !== null) {
            $this->_user_info = $this->_old_user_info;
            $this->_old_user_info = null;
            $this->save();
        }
    }
    
    function bcrypt($value, $rounds = 10) {
        return password_hash($value, PASSWORD_BCRYPT, array('cost' => $rounds));
    }
}

session_name(SESSION_NAME);
session_start();