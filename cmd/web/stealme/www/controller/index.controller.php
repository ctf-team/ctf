<?php

class IndexController extends Controller {
    
    /* Runs right when controller is loaded */
    public function __onload() {
        $this->uses(array('auth'));
    }
    
    /* Runs right before page is rendered */
    public function __onready() {

    }
    
    public function index() {
        $this->needs_guest($this->getURL('/dashboard'));
        $this->needs_auth($this->getURL('/auth/login'));
    }


    public function dashboard() {
        $this->needs_auth($this->getURL('/'));

        $this->setlayout('dashboard');

        $this->set(array(
            'username' => $this->auth->user()['firstname'].' '.$this->auth->user()['lastname'],
            'user' => $this->auth->user(),
            'permissions' => $this->auth->user()['rank'],
            'title' => 'Dashboard',
        ));
    }
}