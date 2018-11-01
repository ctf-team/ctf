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
    
    @version 0.9.7a
*/

ob_start();
require_once("routing.inc.php");

// Routing
$router = new Router();

require_once("../config/config.inc.php");

if(USE_MEMCACHED) {
    $GLOBALS['_memcached'] = new Memcached(MEMCACHED_HOST.":".MEMCACHED_PORT);
    ini_set('memcached.sess_lock_expire', 3600);
    ini_set("session.save_handler", "memcached");
    ini_set("session.save_path", MEMCACHED_HOST.":".MEMCACHED_PORT);
}

// Load all init files in the current directory
$included_files = get_included_files();
foreach(glob(dirname(__FILE__)."/*.inc.php") as $filename)
    if(!in_array($filename, $included_files)) 
        require_once($filename);

$router->DoRoute();