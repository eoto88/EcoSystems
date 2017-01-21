<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_ParamTypes extends Controller_REST {

    protected $entityName = 'param_type';

    public function action_index() {
        $idParamType = $this->request->param('id');
        $mParamType = new Model_ParamType();

        if( isset($idParamType) ) {
            $this->respond($mParamType->getParamType($idParamType));
        } else {
            $this->respond($mParamType->getParamTypes());
        }
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
