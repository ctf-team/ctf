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
    
    Original Author: Luminoslty
    Author Github: https://github.com/Luminoslty
    
*/

class TemplatingModule extends Module {
    private $controller;
	public function create($controller, $opts) {
	    $this->controller = $controller;
	    if(!($this->buf = $this->controller->loadFile(array("views", get_class($this->controller), $this->controller->view.".view.php"), true))) {
            throw new Exception(sprintf("View does not exist: views/%s/%s.view.php", get_class($this->controller), $this->controller->view));
        }
		$this->opt = $opts;
	}
	public function render() {	
	    $this->controller->setview(false);
		if($this->opt["cache"] == true) $render = $this->cache($this->buf);
		else $render = $this->parse($this->buf);
		echo $render;
	}
	private function parse($buf)
	{
		if($this->has_statement($buf) == true) $buf = $this->handle_statement($buf);
		if($this->has_foreach($this->buf) == true) $buf = $this->handle_foreach($buf);
		
		foreach($this->controller->getVars() as $var => $val) {
			$type = $this->get_type($var);
			if($type == "object") $buf = $this->handle_obj($buf, $var);
			if($type == "array") $buf = $this->handle_array($buf, $var);
			if($type == "var") $buf = str_replace("{{ " . $var . " }}", $val, $buf);
		}
		return $buf;
	}
	private function cache($buf) {
		$permission = fopen($this->opt["cache_dir"] . 'index.html', 'w') or die("CHMOD " . $this->opt["cache_dir"] . " to 777 asshole.");
		fclose($permission);
		for($i = 0; $i < count($this->opt["cache_files"]); $i++) {
			$type = pathinfo($this->opt["cache_files"][$i]);
			if($type["extension"] == "js") $buf = str_replace($this->opt["cache_files"][$i], $this->js($this->opt["cache_files"][$i]), $buf);
			elseif($type["extension"] == "css") $buf = str_replace($this->opt["cache_files"][$i], $this->css($this->opt["cache_files"][$i]), $buf);
			elseif($type["extension"] == "lumi") $buf = $this->lumi($this->opt["cache_files"][$i], $buf);
		}
		return $buf;
	}
	private function lumi($file) {
		$hash = $this->opt["cache_dir"] . md5($file) . '.lumi';
		if(file_exists($hash)) {
			$cached = fopen($hash, 'r');
			$this->buf = fread($cached, filesize($hash));
			fclose($cached);
			return $this->buf;
		}
		else {
			$this->buf = $this->parse($this->buf);
			$f = fopen($hash, 'w');
			fwrite($f, $this->buf);
			fclose($f);
			return $this->buf;
		}
	}
	private function js($file) {
		$hash = $this->opt["cache_dir"] . md5($file) . '.js';
		if(!file_exists($hash)) {
			$f = fopen($file, 'r');
			$c = fread($f, filesize($file));
			fclose($f);
			$c = preg_replace("/(\/\/[^\n\\/^,()'\"].*)/", "", $c);
			$c = preg_replace("/(\/\/)[^'\" ,.();A-z0-9]/", "", $c);
			$c = str_replace("\n", "", $c);
			$c = str_replace("\r", "", $c);
			$f = fopen($hash, "w");
			fwrite($f, $c);
			fclose($f);
		}
		return $hash;
	}
	private function css($file) {
		$hash = $this->opt["cache_dir"] . md5($file) . '.css';
		if(!file_exists($hash)) {
			$f = fopen($file, 'r');
			$c = fread($f, filesize($file));
			fclose($f);
			$c = preg_replace("/\s+/", '', $c);
			$c = str_replace("background-color:", 'background:', $c);
			$f = fopen($hash, "w");
			fwrite($f, $c);
			fclose($f);
		}
		return $hash;
	}
	private function get_type($val) {
	    switch(true) {
	        case is_object($val):
	            return "object";
	        break;
	        case is_array($val):
	            return "array";
	        break;
	        case is_int($val):
	        case is_bool($val):
	        case is_string($val):
	        default: {
	            return "var";
	        }
	    }
	}
	private function has_statement($buf) {
		if(preg_match("/{{ if.* }}/", $buf)) return true;
	}
	private function has_foreach($buf) {
		if(preg_match("/{{ foreach.* }}/", $buf)) return true;
	}
	private function handle_foreach($buf) {
		preg_match_all("/{{ foreach.+? }}.*?{{ endforeach }}/s", $buf, $matches, PREG_SET_ORDER);
		for($i = 0; $i < count($matches); $i++) {
			$stack = str_replace("{{ foreach ", "", stristr($matches[$i][0], " }}", true));
			$stack = explode(' ', $stack);
			$arr = $this->controller->getVars()[$stack[0]];
			$str = '';
			foreach($arr as $a) {
				$b = str_replace("{{ foreach " . $stack[0] . " " . $stack[1] . " " . $stack[2] . " }}", "", $matches[$i][0]);
				$b = stristr($b, "{{ endforeach }}", true);
				$b = str_replace("{{ " . $stack[2] . " }}", $a, $b);
				$str .= $b;
			}
			$buf = str_replace($matches[$i][0], $str, $buf);
		}
		return $buf;
	}
	private function handle_statement($buf) {
		preg_match_all("/{{ if.+? }}.*?{{ endif }}/s", $buf, $matches, PREG_SET_ORDER);
		for($i = 0; $i < count($matches); $i++) {
			$cond = str_replace('{{ if.', '', stristr($matches[$i][0], " }}", true));
			$oper = $this->get_oper($cond);
			$comp = str_replace($oper, "", stristr($cond, $oper));
			$break = $this->break_statement($matches[$i][0], $cond);
			$break["if"] = $this->parse($break["if"]);
			$break["else"] = $this->parse($break["else"]);
			$split = ["==" => "-","===" => "-","<" => "-",">" => "-","<=" => "-",">=" => "-",];
			$split = strtr($cond, $split);
			$split = explode("-", $split);
			if($obj = stristr($split[0], "::", true)) {
				if(stristr($split[0], "[\"", true)) $key = $this->handle_ob_array($split[0]);
				if(isset($key)) {
					$cond = stristr($split[0], "[\"", true);
					$cond = $this->exec_method($this->controller->getVars()[$obj], str_replace("::", "", stristr($cond, "::")));
					$cond = $cond[$key];
				}
				else $cond = $this->exec_method($this->controller->getVars()[$obj], str_replace($obj . "::", '', $split[0]));
			}
			elseif(strpos($split[0],"[\"")) {
				$key = $this->handle_ob_array($split[0]);
				$cond = $this->controller->getVars()[stristr($split[0], "[\"", true)][$key];
			}			
			else $cond = $this->controller->getVars()[$split[0]];
			if($this->compare_statement($cond, $comp, $oper) === true) {
				$buf = str_replace($matches[$i][0], $break["if"], $buf);
			}
			else $buf = str_replace($matches[$i][0], $break["else"], $buf);
		}
		return $buf;
	}
	private function get_oper($cond) {
		$ops = ["===","==","<=",">=",">","<"];
		foreach($ops as $op) {
			if(strpos($cond, $op)) return $op;
		}
		return false;
	}
	private function compare_statement($cond, $comp, $oper) {
		if($oper == "===" && $cond === $comp) return true;
		elseif($oper == "==" && $cond == $comp) return true;
		elseif($oper == "<" && $cond < $comp) return true;
		elseif($oper == ">" && $cond > $comp) return true;
		elseif($oper == "<=" && $cond <= $comp) return true;
		elseif($oper == ">=" && $cond >= $comp) return true;
		elseif($oper == false && !empty($cond)) return true;
		else return false;
	}
	private function break_statement($buf, $cond) {
		$if = stristr($buf, "{{ else }}", true);
		$if = str_replace("{{ if." . $cond . " }}", "", $if);
		$else = stristr($buf, "{{ else }}");
		$else = stristr($else, "{{ endif }}", true);
		$else = str_replace("{{ else }}", "", $else);
		return array(
			"if" => $if,
			"else" => $else,
		);
	}
	private function handle_obj($buf, $var) {
		preg_match_all("/{{ $var::.*? }}/", $buf, $matches, PREG_SET_ORDER);
		if(count($matches) > 0) {
			for($i = 0; $i < count($matches); $i++) {
				$format = $matches[$i];
				$format = str_replace("{{ ", "", $matches[$i][0]);
				$format = str_replace(" }}", "", $format);
				
				if(stristr($format, "[\"", true)) {
					$key = $this->handle_ob_array($format);
					$format = stristr($format, "[\"", true);
				}
				$exec_method = $this->exec_method($this->controller->getVars()[$var], str_replace("::", "", stristr($format, "::")));
				if(is_array($exec_method)) $buf = str_replace($matches[$i][0], $exec_method[$key], $buf);
				else $buf = str_replace($matches[$i][0], $exec_method, $buf);
			}
		}
		return $buf;
	}
	private function handle_ob_array($fmt) {
		$key = explode("[\"", $fmt);
		$key = str_replace("\"]", "", $key[1]);
		return $key;
	}
	private function handle_array($buf, $var) {
		preg_match_all("/{{ $var\[\".*?\"\] }}/", $buf, $matches, PREG_SET_ORDER);
		if(count($matches) > 0) {
			for($i =0; $i < count($matches); $i++) {
				$format = $matches[$i];
				$format = str_replace("{{ ", "", $matches[$i][0]);
				$format = str_replace(" }}", "", $format);
				$format = str_replace("[\"", "", $format);
				$format = str_replace("\"]", "", $format);
				$buf = str_replace($matches[$i][0], $this->controller->getVars()[$var][str_replace($var, "", $format)], $buf);
			}
		}
		return $buf;
	}
	private function exec_method($class, $method)
	{
		return call_user_func(array(
			$class,
			$method,
		));
	}
}