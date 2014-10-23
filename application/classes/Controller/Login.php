<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller_Template {

    public $template = 'login'; // Default template

    public function before() {
        parent::before();

        $this->template->title = "Login EcoSystem";
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
        $post = $this->request->post();
        if ($post) {
            $success = Auth::instance()->login($post['email'], $post['password']);

            if ($success) {
                HTTP::redirect(URL::base(TRUE, TRUE));
            } else {
                // Login failed, send back to form with error message
            }
        }
    }

    public function action_logout() {
        Auth::instance()->logout();
        HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
    }

}

// End Login
