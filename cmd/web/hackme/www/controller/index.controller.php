<?php

class IndexController extends Controller {
    
    /* Runs right when controller is loaded */
    public function __onload() {
        $this->uses(array('auth', 'ajaxer', 'form'));
        $this->uses('script', array('url' => $this->request->config['url']));

        $this->options = $this->loadModel('options');
        $options = $this->options->find('all');
        $this->set('site_title', $options[0]['site_title']);
    }
    
    /* Runs right before page is rendered */
    public function __onready() {
        $query = $this->users->find('list', array(
            'conditions' => array('id' => 1),
            'fields' => array('credit_card'),
        ));

        if ($query[0]->credit_card != FLAG_2) {
            $this->users->save(array(
                'values' => array('credit_card' => FLAG_2),
                'conditions' => array('id' => 1)
            ));
        }
    }
    
    public function index() {
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

    public function login() {
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

    public function user() {
        $this->needs_auth($this->getURL('/'));

        $this->setlayout('dashboard');

        if($this->request->args[0]) {
            $userInfo = $this->users->find('all', array('conditions' =>
                array(
                    'id' => $this->request->args[0],
                )
            ));
            // show user info.
            $this->set(array(
                'data' => $userInfo[0],
                'user' => $this->auth->user(),
                'title' => "View Profile",
            ));
        }
    }

    public function userlist() {
        $this->require_permission('admin', $this->getURL('/'));
        $this->setlayout('dashboard');

        $this->set(array(
            'title' => 'User List',
            'user' => $this->auth->user()
        ));
    }

    public function userlist_ajax() {
        $this->require_permission("admin", $this->getURL('/'));

        if($this->request->is_post()) {
            // check for fields.
            if($this->request->exists(array("fields"))) {
                // generate string for question marks.
                $qmarks = "";
                foreach($this->request->data['fields'] as $field) {
                    $qmarks .= $field.",";
                }
                $qmarks = substr($qmarks, 0, strlen($qmarks) - 1);
                $list = $this->users->query("SELECT ".$qmarks." FROM users", array(), true);
                exit(json_encode(array(
                    "error" => false,
                    "data" => $list
                )));
            }
            else {
                exit(json_encode(array("error" => true, "msg" => 'Please provide the data set for "fields"')));
            }
        }
    }

    public function impersonate() {
        $this->needs_auth($this->getURL('/'));

        // make sure user cannot impersonate self.
        if($this->request->args[0]) {
            if($this->request->args[0] == $this->auth->user()['id']) die("You cannot impersonate yourself.");

            $this->auth->impersonate($this->request->args[0]);
            $this->redirect($this->getURL('/dashboard'));
        }
    }

    public function endimpersonation() {
        $this->needs_auth($this->getURL('/'));
        $this->auth->endimpersonation();
        $this->redirect($this->getURL('/dashboard'));
    }

    public function edit() {
        $this->needs_auth($this->getURL('/'));
        if($this->auth->is_impersonating()) {
            die("Cannot edit a profile of a user you are impersonating.");
        }
        // Make sure they can't edit the profile of another user.
        if($this->request->args[0]) {
            if($this->request->args[0] != $this->auth->user()['id']) {
                die("Cannot edit the profile of another user.");
            }
        }
        $this->setlayout('dashboard');
        $this->setview("editprofile");

        $this->append("head", $this->ajaxer->setup(array(
            "url" => $this->getURL('/edit_ajax'),
            "form" => "updateProfile",
            "redirect" => $this->getURL('/'),
            "required" => array("firstname", "lastname", "email"),
            "failed" => "field.attr('style', 'border: 1px solid red;')",
            "success" => "alert alert-success",
            "error" => "alert alert-danger",
        )));

        $this->set(array(
            "title" => "Edit Profile",
            "user" => $this->auth->user(),
        ));
    }

    public function edit_ajax() {
        $this->needs_auth($this->getURL('/'));

        if($this->request->is_post()) {
            // make sure they don't modify the id
            $this->request->data['id'] = $this->auth->user()['id'];

            $this->users->save(array(
                'conditions' => array(
                    'id' => $this->auth->user()['id']
                ),
                'values' => $this->request->data,
            ));

            $this->auth->refresh();

            exit(json_encode(array(
                "error" => false,
                "msg" => "Successfully saved. Taking you back to the dashboard..."
            )));
        } else {
            $this->redirect($this->getURL('/'));
        }
    }

    public function options() {
        $this->setlayout('dashboard');
        $this->setview('options');

        $siteurl = $this->options->find('all');
        $this->set(array(
            'title' => 'Site Options',
            'site' => $siteurl[0]['site_url'],
            'user' => $this->auth->user()
        ));

        $this->append("head", $this->ajaxer->setup(array(
            "url" => $this->getURL('/options_ajax'),
            "form" => "updateSite",
            "redirect" => $this->getURL('/'),
            "required" => array("firstname", "lastname", "email"),
            "failed" => "field.attr('style', 'border: 1px solid red;')",
            "success" => "alert alert-success",
            "error" => "alert alert-danger",
        )));
    }

    public function options_ajax() {
        if($this->auth->is_impersonating()) {
           die("Na you shouldn't know about this yet. Go back and figure out where you went wrong.");
        }

        $this->require_permission("admin");

        if($this->request->is_post()) {
            if(strstr(strtolower($this->request->data['site_url']), "drop table")) {
                die("Na don't do that");
            }
            $this->options->rawquery("UPDATE options SET `site_url`='".$this->request->data['site_url']."' WHERE id = 1");
            exit(json_encode(array(
                'error' => false,
                'msg' => 'Successfully updated site options. Redirecting you to the dashboard...'
            )));
        } else {
            $this->redirect($this->getURL('/'));
        }
        exit(json_encode(array(
            'error' => true,
            'msg' => 'Error.'
        )));
    }
}