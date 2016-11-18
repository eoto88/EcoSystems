<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller_Template {

    public $template = 'login'; // Default template

    public function before() {
        parent::before();

        $this->template->title = "Login EcoSystem";
        $this->template->login_messages = "";
        $this->template->create_account_messages = "";
    }

    public function after() {
        if ($this->auto_render) {
            $styles = array(
                URL::base(TRUE, TRUE) ."assets/css/login.css" => "screen",
                URL::base(TRUE, TRUE) ."assets/css/normalize.css" => "screen"
            );
            $scripts = array(
                "assets/js/login.js"
            );

            $this->template->styles = array_reverse(
                    $styles // array_merge( $this->template->styles, $styles )
            );
            $this->template->scripts = array_reverse(
                    $scripts // array_merge( $this->template->scripts, $scripts )
            );
        }
        parent::after();
    }

    public function action_index() {
        if ( Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE));
        }

        $post = $this->request->post();
        $messages = null;
        if ($post && $post['action']) {
            // TODO isset
            if ($post['action'] == 'create-account' && $post['username'] && $post['name'] && $post['email'] && $post['password']) {

                $mUser = new Model_ESUser();
                $messages = $mUser->insertUser($post['username'], $post['name'], $post['email'], $post['password']);

                if ($messages) {
                    $this->template->create_account_messages = $messages;
                }
            }

            $remember = false;
            if(isset($post['remember'])) {
                $remember = true;
            }

            if( !$messages ) {
                $success = Auth::instance()->login($post['username'], $post['password'], $remember);

                if ($success) {
                    HTTP::redirect(URL::base(TRUE, TRUE));
                } else {
                    $this->template->login_messages = array('password' => 'Wrong password');
                }
            }
        }
    }

    public function action_logout() {
        Auth::instance()->logout();
        HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
    }
}

// End Login
