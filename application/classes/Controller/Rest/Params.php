<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Params extends Controller_REST {

    protected $entityName = 'param';

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mParam = new Model_Param();

        if(empty($id_instance)) {
            $this->respond($mParam->getParams());
        } else {
            if($this->request->query('header') == "false") {
                $this->respond($mParam->getGroupParamsWithData($id_instance, false));
            } else {
                $this->respond($mParam->getInstanceParams($id_instance));
            }
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
