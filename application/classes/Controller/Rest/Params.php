<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Params extends Controller_REST {

    protected $entityName = 'param';

    public function action_index() {
        $mParam = new Model_Param();

        $this->respond($mParam->getParams());
    }

    public function action_update() {
        die();
    }

    public function action_create() {
        die();
    }

    public function action_delete() {
        die();
    }
}
