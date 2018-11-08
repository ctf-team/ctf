<?php

class ScriptsModule extends Module {
    private $scripts;
    private $localize;
    private $config;
    
    public function __construct() {
        $this->scripts = array();
        $this->config = array(
            'url' => SITE_URL
        );
    }

    
    public function enqueue($name, $path, $priority = -1) {
        //find the proper spot in the array to enqueue.
        if(strstr($path, "://") === FALSE) {
            //let's use relative paths.
            $path = rtrim($this->config['url'], "/")."/".ltrim($path, "/");
        }
        if($priority == -1) {
            $priority = count($this->scripts) + 1;
        }
        $toinsert = array("priority" => $priority, "path" => $path, "name" => $name);
        if(count($this->scripts) <= 0) { $this->scripts[0] = $toinsert; return; }
        for($i = 0; $i < count($this->scripts); $i++) {
            if($priority <= $this->scripts[$i]["priority"]) {
                //insert before.
                $this->scripts = $this->insertBefore($this->scripts, $i, $toinsert);
                return;
            }
        }
        //if they haven't added it yet, then insert it at the end.
        array_push($this->scripts, $toinsert);
    }
    
    public function dequeue($name) {
        for($i = 0; $i < count($this->scripts); $i++) {
            if($this->scripts[$i]['name'] == $name) {
                //remove from array.
                $this->scripts = array_diff($this->scripts, array($this->scripts[$i]));
            }
        }
    }
    
    public function execute() {
        //output script stuff.
        foreach($this->scripts as $script) {
            if(!strstr($script['path'], '.css')) {
                ?>
                <script src="<?php echo $script['path']; ?>"></script>
                <?php
            } else {
                ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $script['path']; ?>"> 
                <?php
            }
        }
        if(count($this->localize) > 0) {
            echo "<script>\n";
            foreach($this->localize as $key => $value) {
                echo 'var '.$key." = {};\n";
                foreach($value as $k => $v) {
                    echo $key.'.'.$k.' = "'.$v.'";'."\n";
                }
            }
            echo "</script>\n";
        }
    }

    public function localize($name, $varname, $arry) {
        $this->localize[$varname] = $arry;
    }
    
    private function insertBefore($input, $index, $element) {
        if (!array_key_exists($index, $input)) {
            throw new Exception("Index not found");
        }
        $tmpArray = array();
        $originalIndex = 0;
        foreach ($input as $key => $value) {
            if ($key === $index) {
                $tmpArray[] = $element;
                break;
            }
            $tmpArray[$key] = $value;
            $originalIndex++;
        }
        array_splice($input, 0, $originalIndex, $tmpArray);
        return $input;
    }
}