<?php

class AuthController extends Controller
{
    /* Runs right when controller is loaded */
    public function __onload()
    {
        $this->uses(array('auth', 'ajaxer', 'form'));
    }

    /* Runs right before page is rendered */
    public function __onready()
    {

    }

    public function index()
    {

    }

    public function login() {

    }

    public function register() {

    }
}