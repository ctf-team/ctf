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

class Router {
    private $routes_list;
    private $index;
    private $indexname;
    
    public function __construct() {
        $this->routes_list = array();
        register_shutdown_function(array($this, "ErrorHandler"));
    }
    
    /**
     *
     * Connects routes to controllers and actions.
     * 
     * Example: 
     * $router->Connect('/', array('controller' => 'index'));
     * $router->Connect(array('/' => array('controller' => 'index')));
     * 
     * @param string|array $routes 
     *      The name of your route or an array of routes to create.
     * @param array $routeparams
     *      Optional if you used an array of routes instead of string for the name. Array of options for the route.
     * 
     * @returns string
     *      Content of the currently loaded view
     * 
     */
    public function Connect() {
        $num = func_num_args();
        if($num == 1) {
            $arr = func_get_args()[0];
            foreach($arr as $key => $value) {
                
                $this->routes_list[$key] = $value;
            }        
        } else if ($num == 2) {
            $source = func_get_args()[0];
            $dest = func_get_args()[1];
            $this->routes_list[$source] = $dest;    
        }
        
        
    }
    
    public function ErrorHandler() {
        $error = error_get_last();
        if($error !== null) {
            ?>
            <html>
            <body>
                <h2 style="color:red;">Error!</h2>
                <pre><?php echo $error['message']; ?><br />On: Line <?php echo $error['line']; ?><br />Path: <?php echo $error['file']; ?></pre>
            </body>
            </html>
            <?php
        }
    }
    
    private function GetRoute($source) {
        if(strlen($source) > 1) {
            $source = rtrim($source, "/");
        }
        //first, grab all regex entries from routes list
        foreach($this->routes_list as $key => $value) {
            if(array_key_exists("regex", $value)) {
                //this is a regex, compare first?
                $matches = array();
                preg_match($key, $source, $matches);
                
                if(count($matches) > 0 && $matches[0] == $source && !array_key_exists($source, $this->routes_list)) {
                    //this is our route.
                    return $this->routes_list[$key];
                }
            }
        }
        if(!array_key_exists($source, $this->routes_list)) { 
            //first check if / route contains this function.
            $nroute = ltrim($source, "/");
            if(strstr($nroute, '/')) {
                $first = strpos($nroute, '/');
                $nroute = substr($nroute, 0, $first);
            }

            if(is_null($this->index)) {
                //load index controller.
                $this->indexname = $this->LoadClass($this->routes_list["/"]);
                $this->index = new $this->indexname[0](array(), $nroute, true);
            }
            if(!is_callable(array($this->index, $nroute))) {
                return null;
            } else {
                return array('controller' => $this->routes_list['/']['controller'], 'action' => $nroute, 'precreated' => true);
            }
            return null;
        }
        return $this->routes_list[$source];
    }
    
    private function GetRouteByProperty($property_name, $property_value) {
        foreach($this->routes_list as $route) {
            //search for property in array ayyy lmao
            if(array_key_exists($property_name, $route)) {
                //does it equal?
                if($route[$property_name] != $property_value) continue;
                //return it ayy lmao           
                return $route;
            }
        }
        return null;
    }
    
    public function LoadClass($route) {
        if($route['controller'] == 'index' && !is_null($this->index)) return $this->indexname;
        $preclasses = get_declared_classes();

        if(!LoadFile(array("controller", $route["controller"].".controller.php"))) {
            throw new Exception(sprintf("Controller not found: %s/%s", "controller", $route["controller"]));
        }

        $postclasses = get_declared_classes();
        $class = array_diff($postclasses, $preclasses);
        $class = array_values($class);
        return $class;
    }
       
    public function DoRoute() {
        $source = $_SERVER['REQUEST_URI'];
        if(strstr($source, "?") !== false) {
            $source = explode("?", $source)[0];
        }

        $route_piece = "";
        $route = null;
        $pieces = array();
        $source .= ($source[strlen($source) - 1] == "/" ? "" : "/");
        
        if($source !== "/") {
            $tmp = strlen($source) - 1;
            while(strrpos($source, "/", $tmp) !== FALSE)
            {
                $route_piece = rtrim(substr($source, 0, $tmp), "/");
                if (strlen($route_piece) == 0) break;
                $route = $this->GetRoute($route_piece);   
                if (!is_null($route)) break;
                $tmp = strrpos($route_piece, "/");
            }   
        }
        else {
            //our route is probably /? let's just keep it as /
            $route = $this->GetRoute("/");
        }

        if (is_null($route)) {
            //ay we don't have a valid route. return our 404 route instead
            //look for our 404 route
            $error_route = $this->GetRouteByProperty("error_page", "404");
            if(!is_null($error_route)) {
                $route = $error_route;
            }
            else {
                exit("404 page controller not found");
            }
        }
        if(array_key_exists('precreated', $route)) {
            $args_piece = ltrim(substr($source, 0, strlen($route_piece)), "/");
        } else {
            $args_piece = ltrim(substr($source, strlen($route_piece)), "/");
        }
        
        $action = (array_key_exists("action", $route) ? $route["action"] : "index");
        
        $args = array();

        $search = "/^(.+?)\/(.*)$/i";
        $matches = array();
        
        if (preg_match($search, $args_piece, $matches)) {
            $action = $matches[1];
            $args = explode("/", rtrim($matches[2], "/"));
        }
        $args = array_filter($args, create_function('$a', 'return $a !== "";'));

        $class = $this->LoadClass($route);
        if($this->index == null) {
            $instance = new $class[0]($args, $action);
        }
        else {
            $instance = $this->index;
            $instance->__setargs($args);
        }

        $call_action = str_replace("-", "_", $action);
        
        if (!is_callable(array($instance, $call_action)))
        {
            /*array_unshift($args, $action);
            $args = array_filter($args, create_function('$a', 'return $a !== "";'));
            $call_action = (array_key_exists("action", $route) ? $route["action"] : "index");
            $instance->setArgs($args, $action);*/
            if(!array_key_exists('error_page', $route)) {
            //load 404 instead?
                $error_route = $this->GetRouteByProperty("error_page", "404");
                if(!is_null($error_route)) {
                    $route = $error_route;
                }
                else {
                    exit("404 page controller not found");
                }
                $class = $this->LoadClass($route);   
            }
            $call_action = (array_key_exists("action", $route) ? $route["action"] : "index");
            $instance = new $class[0]($args, $action);
        }

        if(is_callable(array($instance, "__onload"))) {
            $instance->{"__onload"}();
        }
        
        $instance->{$call_action}();
        
        //based on action - we need to call the template
        $instance->{"__render"}($class[0], $action, $route["controller"]);
    }
}