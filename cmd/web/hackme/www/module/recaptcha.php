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

class ReCaptcha extends Module {
    public function get_html() {
        return "<script src='".RECAPTCHA_URL."/api.js'></script>";
    }
    
    public function render() {
        return '<div class="g-recaptcha" data-sitekey="'.RECAPTCHA_SITE_KEY.'"></div>';
    }
    
    public function verify($response) {
        $ch = curl_init();
        
        $values = array(
            "secret" => RECAPTCHA_PRIVATE_KEY,
            "remoteip" => $_SERVER["REMOTE_ADDR"],
            "response" => $response
        );
        
        $values_string = "";
        
        foreach($values as $key=>$value) { $values_string .= $key.'='.$value.'&'; }
        rtrim($values_string, '&');
        
        curl_setopt($ch,CURLOPT_URL, RECAPTCHA_URL."/api/siteverify");
        curl_setopt($ch,CURLOPT_POST, count($values));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $values_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        $result = json_decode($result);

        return $result->success;
    }
}