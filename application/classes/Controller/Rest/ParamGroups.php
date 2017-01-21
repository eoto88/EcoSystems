<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_ParamGroups extends Controller_REST {

    protected $entityName = 'param_group';

    public function action_index() {
        $idInstance = $this->request->param('id_instance');
        $idGroup = $this->request->param('id');
        $mParamGroup = new Model_ParamGroup();

        if( ! empty($idInstance) && ! empty($idGroup)) {
            die();
//            $this->respond($mParamGroup->getInstanceParamGroups($idInstance));
        } else if( ! empty($idInstance) && empty($idGroup)) {
            $this->respond($mParamGroup->getInstanceParamGroups($idInstance));
        } else {
            $this->response->status(406)->body();
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
