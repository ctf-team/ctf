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

class Request {
    public $args;
    public $url;
    public $data;
    public $path;
    public $config;
    
    public function __construct($args) {
        $this->args = $args;
        $this->data = $_POST;
        $this->url = $_SERVER['REQUEST_URI'];
        $this->config = array(
          "url" => SITE_URL,
        );
    }

    public function _set_current($var) {
        $this->path = $var;
    }
    
    /************************
    * User Callable Functions 
    *************************/

    /**
     *
     * Checks to see if a list of POST variables are set
     * 
     * Example: 
     * $this->exists(array(
     *  "firstname",
     *  "lastname"
     * ));
     * 
     * This will check to see if firstname and lastname were set when POSTing
     * 
     * @param array $name
     *      Array of names to check for.
     * 
     */
    public function exists($name) {
        foreach($name as $value) {
            if(!array_key_exists($value, $this->data)) return false;
        }
        return true;
    }
    
    /**
     * 
     * Checks to see if the current page was loaded via POST
     * 
     * @returns boolean
     *      True if the request method was POST, false if the request method was not POST
     * 
     */
    public function is_post() {
        return strtolower($_SERVER['REQUEST_METHOD']) == "post";
    }
    
    /**
     * 
     * Checks to see if the current page was loaded via GET
     * 
     * @returns boolean
     *      True if the request method was GET, false if the request method was not GET
     * 
     */
    public function is_get() {
        return strtolower($_SERVER['REQUEST_METHOD']) == "get";
    }
}

class Controller {
    public $request;
    private $variables;
    public $layout = "default";
    public $view = "_";
    public $url;
    
    private $pre_defines;
    private $appends = array();
    private $prepends = array();
    
    /**
     *
     * Starts up controller class
     *
     * @param array $args URL arguments i.e http://mysite.com/class/action/args/args1
     *
     */
    public function __construct($args, $view) {
        $this->request = new Request($args);
        $this->variables = array();
        
        $this->view = $view;

        //create a model based on our model name.
        //controller name is the name of the model?
        $instance = $this->__loadmodel();
    }

    public function __setargs($args) {
        $this->request = new Request($args);
    }
    
    private function __loadmodel($name = "") {
        $modelname = "";
        $return = false;
        if(empty($name)) {
            $name = str_replace("controller", "", strtolower($this->__getname())).".model.php";
            $modelname = strtolower($this->__getname());    
        } else {
            $return = true;
        }
            
        $preclasses = get_declared_classes();
        $instance = null;
        if(!$this->__loadfile(array("model", $name))) {
            //our model isn't found, let's create one?
            $instance = new Model(str_replace("controller", "", ($modelname != "" ? $modelname : $name)));
        } else {
            $postclasses = get_declared_classes();
            
            $class = array_diff($postclasses, $preclasses);
            $class = array_values($class);

            $instance = new $class[0]();
        }

        if(!$return) {
            $name = $instance->__getmodelname();
            $this->$name = $instance;
        } else {
            return $instance;
        }
    }
    
    private function __getname() {
        return get_class($this);
    }
    
    private function __loadfile($args, $_data = false) {
        $path = sprintf(dirname(__FILE__)."/../%s", implode('/', $args));
        if(!is_file($path)) {
            return false;
        }
        
        extract($this->variables);

        try {
            if($_data) {
                ob_start();
            }
            require($path);
            if($_data) {
                $obb = ob_get_clean();
            }
        }
        catch (Exception $e) {
            throw $e;
        }
        if($_data) return $obb;
        return true;
    }
    
    public function setArgs($args, $view) {
        $this->request = new Request($args);
        $this->view = $view;
    }

    /**
     *
     * Renders current layout and view. Gets called from router. This method should not be called manually.
     *
     * @param string $classname  
     *      Name of Current Class
     * @param string $viewname   
     *      Name of View
     * @param string $controller 
     *      Name of Page
     * 
     */
    public function __render($classname, $viewname, $controller) {
        if(!$this->view) return;
        //now we can use $this->view to set a custom view
        $viewname = $this->view == "_" ? $viewname : $this->view;
        
        $this->request->_set_current(array("controller" => $classname, "view" => $viewname, "page" => $controller));
        
        if(is_callable(array($this, "__onready"))) $this->__onready();
        
        $returned = $this->__loadfile(array("views", $classname, $viewname.".view.php"), !is_null($this->layout));
        
        if( $returned === false ) {
            throw new Exception(sprintf("View does not exist: views/%s/%s.view.php", $classname, $viewname));
        }

        if(!is_null($this->layout) && $this->layout) {
            //load view.
            $this->pre_defines["content"] = $returned;
            if(!$this->__loadfile(array("layouts", $this->layout.".layout.php"))) {
                throw new Exception(sprintf("Layout does not exist: %s", $this->layout));
            }
        }
    }
    
    /************************
    * User Callable Functions 
    *************************/
    
    public function loadFile($args, $data = false) {
        return $this->__loadfile($args, $data);
    }
    
    public function getVars() {
        return $this->variables;
    }
    
    /**
     *
     * Loads in a module defined by the modules folder.
     * 
     * Example: $this->uses("auth");
     * Will set $this->auth to new instance of the auth module.
     *
     * @param string|array $name 
     *      Name or array of names of modules to load (without .php)
     * @param array $array
     *      Optional values to pass to the construct parameter of the module
     * 
     */
    public function uses($module, $array = array()) {
        //attempt to load library
        $names = array();
        if(is_array($module))
            $names = $module;
        else
            $names[] = $module;
            
        foreach($names as $name) {
            $preclasses = get_declared_classes();
            $instance = null;
            if(!$this->__loadfile(array("module", $name.".php"))) {
                //err.
                throw new Exception("Module not found: ".$name." in modules/".$name.".php.");
            } else {
                $postclasses = get_declared_classes();
                
                $class = array_diff($postclasses, $preclasses);
                $class = array_values($class);
                
                if(count($array) <= 0)
                    $instance = new $class[0]();
                else
                    $instance = new $class[0]($array);
                    
                //get name of instance. pass it our model?
                if(isset($instance->uses))
                    if(array_key_exists("model", $instance->uses)) {
                        //we need to make a model loader so modules can use them
                        foreach($instance->uses["model"] as $value) {
                            $model = $this->__loadmodel($value);
                            $instance->$value = $model;
                        }
                    }
            }
            if(is_callable(array($instance, "__onload")))
                $instance->__onload();
                
            $this->$name = $instance;
        }
    }
    
    public function loadModel($name) {
        //load our model.
        return $this->__loadmodel($name);
    }
    
    /**
     *
     * Prepends content to an element before it's loaded in.
     * 
     * Example: $this->prepend("head", $content);
     * Will prepend $content to "head.element.php" when loaded in.
     * 
     * @param string $name 
     *      Name of element to prepend to (without .element.php)
     * @param string $content
     *      Content to load before element is loaded.
     * 
     */
    public function prepend($name, $content) {
        if(!array_key_exists($name, $this->prepends)) {
            $this->prepends[$name] = $content;    
        } else {
            $this->prepends[$name] .= $content;
        }
    }
    
    
    /**
     *
     * Appends content to an element before it's loaded in.
     * 
     * Example: $this->append("head", $content);
     * Will append $content to "head.element.php" when loaded in.
     * 
     * @param string $name 
     *      Name of element to append to (without .element.php)
     * @param string $content
     *      Content to load after element is loaded.
     * 
     */
    public function append($name, $content) {
        if(!array_key_exists($name, $this->appends)) {
            $this->appends[$name] = $content;    
        } else {
            $this->appends[$name] .= $content;
        }
    }

    /**
     *
     * Sets a variable to be used by layouts, views, and elements.
     * 
     * Examples: 
     * $this->set("title", "Page Title");
     * $this->set(array(
     *  "title" => "Page Title",
     * ));
     * 
     * $title can be accessed in the layout, view, and element with this
     * 
     * @param string|array $arr 
     *      A string with name of variable to set or an array of key => values to be set.
     * @param string $value
     *      Optional value if $arr was set with a string
     * 
     */
    public function set($arr, $value = null) {
        if($value == null) {
            //loop through array and setup key values
            foreach($arr as $key => $val) {
                $this->variables[$key] = $val;
            }    
        }
        else {
            $this->variables[$arr] = $value;       
        }
    }
    
    /**
     *
     * Gets view content within a layout
     * 
     * Example: 
     * $this->get("content");
     * 
     * This will output the view within your layout wherever this is placed.
     * 
     * @param string $name 
     *      Should always be "content", will be expanded on in the future for modules.
     * 
     * @returns string
     *      Content of the currently loaded view
     * 
     */
    public function get($name) {
        echo $this->pre_defines[$name];
    }

    /**
     *
     * Gets a full URL based on current path.
     * 
     * Example: 
     * $this->getURL("/site");
     * 
     * This will output http://mysite.com/site
     * 
     * $this->getURL("/site/page");
     * 
     * This will output http://mysite.com/site/page
     * 
     * @param string $path 
     *      The name of the page to get the URL for.
     * 
     * @returns string
     *      Full URL based on $path.
     * 
     */
    public function getURL($path = "") {
        $url = $this->request->config['url'];
        if(strlen($path) > 0) if($path[0] == '/') $url = rtrim($url, "/");
        return $url.$path;
    }
    
    /**
     *
     * Sets the current layout.
     * 
     * Example:
     * $this->setlayout("default");
     * 
     * This will make the loaded layout the "layouts/default.layout.php" file.
     * 
     * @param string $args 
     *      The name of the layout (without .layout.php)
     * 
     */
    public function setlayout($args) {
        $this->layout = $args;
    }
    
    /**
     *
     * Gets a full URL based on current path.
     * 
     * Example: 
     * $this->setview("index");
     * 
     * This will make the loaded view the "views/%CONTROLLER%/index.view.php"
     * 
     * @param string $args
     *      The name of the view (without .view.php)
     * 
     */
    public function setview($args) {
        $this->view = $args;
    }
           
           
    /**
     *
     * Redirects the user to a page. Commonly used with $this->getURL
     * 
     * Example: 
     * $this->redirect($this->getURL("site"));
     * 
     * This will redirect the user to http://mysite.com/site
     * 
     * @param string $page
     *      If $page has http:// within it, it will treat it as a full URL and redirect based on that. If not, it will redirect relative to SITE_URL
     * 
     */
    public function redirect($page) {
        if(stristr($page, "://") !== false) {
            header("Location: ".$page);
        }
        else {
            if($page[0] == '/') $url = rtrim($this->request->config['url'], "/");
            header("Location: ".$url.$page);
        }
        die;
    }
    
    
    /**
     *
     * Fetches an element
     * 
     * Example: 
     * $this->fetch("elementname");
     * 
     * This will return the content of the element wherever this call is placed.
     * 
     * @param string $ele
     *      The name of the element (without .element.php)
     * 
     */
    public function fetch($ele) {
        //fetch a layout.
        if(array_key_exists($ele, $this->prepends)) {
            echo $this->prepends[$ele];
        }
        
        if(!$this->__loadfile(array("views", "elements", $ele.".element.php"))) {
            //error.
            throw new Exception("Couldn't load the specified element.");
        }
        //load any appends?
        if(array_key_exists($ele, $this->appends)) {
            echo $this->appends[$ele];
        }
    }
    
    /************************
    * Only if you are using the built-in auth module
    *************************/
    
    /**
     *
     * Forces a visitor to be logged in to view the page that this function is executed within.
     * 
     * @param string $url
     *      Optional redirect URL
     * 
     */
    public function needs_auth($url = "") {
        if (!$this->auth->check())
            $this->redirect((!empty($url) ? $url : "/"));
    }
    
    /**
     *
     * Forces a visitor to be NOT logged in to view the page that this function is executed within.
     * 
     * @param string $url
     *      Optional redirect URL
     * 
     */
    public function needs_guest($url = "") {
        if ($this->auth->check())
            $this->redirect((!empty($url) ? $url : "/"));
    }
    
    
    /**
     *
     * Requires that a user has a permission with a rank to view the page that this function is executed within.
     * 
     * @param string $perm
     *      Permission name as defined in the permissions table in the database
     * @param string $url
     *      Optional redirect URL
     * 
     */
    public function require_permission($perm, $url = "") {
        if (!$this->auth->has_permission($perm))
            $this->redirect((!empty($url) ? $url : "/"));
    }
    
    /**
     *
     * Returns a user's information as specified in the users table within the database.
     * 
     * @returns array 
     *      Filled with the columns within the users table.
     * 
     */
    public function session() {
        return $this->auth->user();
    }
    
    /**
     *
     * Requires that a user has one of the passed in permissions to be able to view the page that this function is executed within.
     * 
     * Example:
     * $this->require_either_permission("permission1", "permission2");
     * 
     * @param string $perm
     *      Name of permission to check for.
     * 
     */
    public function require_either_permission() {
        $perms = func_get_args();
        $found = false;
        foreach($perms as $perm) {
            if ($this->auth->has_permission($perm)) {
                $found = true;
                break;
            }
        }
        
        if (!$found)
            $this->redirect("/");
    }
}

