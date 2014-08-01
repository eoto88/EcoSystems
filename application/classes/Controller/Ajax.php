<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

    public function before() {
        if(!$this->request->is_ajax())
            throw new HTTP_Exception_403;
        parent::before();
    }

    public function after() {
        parent::after();
    }

    public function action_index() {
        $view = View::factory( "index" )->render();
        $this->template->content = $view;
    }

} // End Welcome
