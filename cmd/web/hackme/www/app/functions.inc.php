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

function LoadFile($args, $data = false) {
    $path = sprintf(dirname(__FILE__)."/../%s", implode("/", $args));
    if(!is_file($path)) return false;
    if($data)
        ob_start();
    require_once($path);
    if($data)
        $obb = ob_get_clean();
    
    if($data) return $obb;
    return true;
}