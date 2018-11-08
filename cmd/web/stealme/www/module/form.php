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

class Formify extends Module {
    private $form;
    private $prerender = true;
    
    public function create($id, $values = array()) {
        /*
        id, class, method = post, prerender = true, action = current url
        */
        $this->form = "";
        $this->prerender = true;
        
        if(array_key_exists('prerender', $values))
            $this->prerender = $values['prerender'];
            
        if(!array_key_exists('method', $values))
            $values['method'] = 'post';
            
        $out = "";
        foreach($values as $key => $val) {
            $out .= sprintf('%s="%s" ', $key, $val);
        }
        $out = rtrim($out);

        $form = sprintf('<form id="%s" %s>', $id, $out);
        
        if($this->prerender) {
            echo $form;    
        } else {
            $this->form .= $form;
        }
    }
    
    public function input($name, $values = array()) {
        /*
        id; class; placeholder; type = text/email/password/tel,checkbox,radio,file,number
        coming soon: type = range,date,color,reset,month,time,url,week,
        */
        if(!array_key_exists('type', $values))
            throw new Exception('No type specified for form field.');
        if(empty($name))
            throw new Exception('No name specified for form field.');

        $out = "";
        foreach($values as $key => $val) {
            $out .= sprintf('%s="%s" ', $key, $val);
        }
        $out = rtrim($out);
        
        $forminput = sprintf('<input name="%s" %s>', $name, $out);
        
        if($this->prerender) {
            echo $forminput;
        } else {
            $this->form .= $forminput;
        }
    }
    
    public function submit($name, $values = array()) {
        /* 
        id, class, text,
        */
        $out = "";
        foreach($values as $key => $val) {
            $out .= sprintf('%s="%s" ', $key, $val);
        }
        $out = rtrim($out);
        
        $formsubmit = sprintf('<input value="%s" type="submit" %s>', $name, $out);
        if($this->prerender) {
            echo $formsubmit;
        } else {
            $this->form .= $formsubmit;
        }
    }

    public function button($name, $values = array()) {
        /* 
        id, class, text,
        */
        $out = "";
        foreach($values as $key => $val) {
            $out .= sprintf('%s="%s" ', $key, $val);
        }
        $out = rtrim($out);
        
        $formsubmit = sprintf('<button %s>%s</button>', $out, $name);
        if($this->prerender) {
            echo $formsubmit;
        } else {
            $this->form .= $formsubmit;
        }
    }

    public function insert($content) {
         if($this->prerender) {
            echo $content;
         }
         else {
            $this->form .= $content;
         }
    }
    
    public function hidden($name, $values = array()) {
        $values['type'] = 'hidden';
        $this->input($name, $values);
    }
    
    public function end() {
        $formend = "</form>";
        
        if($this->prerender) {
            echo $formend;
        } else {
            $this->form .= $formend;
        }
    }
    
    public function render() {
        $this->end();
        
        echo $this->form;
    }
}