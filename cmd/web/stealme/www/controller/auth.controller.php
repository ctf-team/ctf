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
        $this->redirect($this->getURL("/auth/login"));
    }

    public function login() {
        $this->needs_guest($this->getURL('/dashboard'));

        $this->set(array(
            'title' => "Login",
        ));

        $this->append("head", $this->ajaxer->setup(array(
            "url" => $this->getURL('/login'),
            "form" => "ajaxform",
            "redirect" => $this->getURL('/dashboard'),
            "required" => array("email", "password"),
            "failed" => "field.attr('style', 'border: 1px solid red;')",
            "success" => "alert alert-success",
            "error" => "alert alert-danger",
        )));
    }

    public function register() {
        $this->needs_guest($this->getURL('/dashboard'));

        $this->set(array(
            'title' => "Register",
        ));

        $this->append("head", $this->ajaxer->setup(array(
            "url" => $this->getURL('/register_ajax'),
            "form" => "ajaxform",
            "redirect" => $this->getURL('/'),
            "required" => array("firstname", "lastname", "email", "password", "confirmpassword"),
            "failed" => "field.attr('style', 'border: 1px solid red;')",
            "success" => "alert alert-success",
            "error" => "alert alert-danger",
        )));
    }

    public function register_ajax() {
        //check for our ajax form test POST.
        if($this->request->is_post()) {
            if($this->request->data['password'] == $this->request->data['confirmpassword']) {
                unset($this->request->data['confirmpassword']);
            } else {
                exit(json_encode(array("error" => true, "error_msg" => "The passwords you specified do not match")));
            }

            if($this->auth->register($this->request->data)) {
                exit(json_encode(array("error" => false, "msg" => 'Successfully registered. Redirecting in 5 seconds...')));
            } else {
                exit(json_encode(array("error" => true, "error_msg" => "That email was already taken.")));
            }
        }
    }

    public function login_ajax() {
        //check for our ajax form test POST.
        if($this->request->is_post()) {
            if($this->auth->attempt($this->request->data)) {
                exit(json_encode(array("error" => false, "msg" => 'Successfully logged in. Redirecting in 5 seconds...')));
            } else {
                exit(json_encode(array("error" => true, "error_msg" => "Incorrect username or password.")));
            }
        }
    }

    public function logout() {
        $this->needs_auth($this->getURL('/'));

        $this->auth->logout();

        $this->redirect($this->getURL('/'));
    }
}