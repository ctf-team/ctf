<?php

class NotFoundController extends Controller {
    
    public function __onload() {
        $this->uses('auth');
        $this->set(array(
            'title' => "Page Not Found",
        ));
        $this->setview('index');
    }
    
    public function index() {
        
    }
}