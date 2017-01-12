<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_ParamGroups extends Controller_REST {

    protected $entityName = 'param_group';

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mParamGroup = new Model_ParamGroup();

        if( ! empty($id_instance) && ! empty($id)) {
            // TODO
            //echo json_encode( $mData->getTodo($id) );
        } else if( ! empty($id_instance)) {
            $this->respond($mParamGroup->getInstanceParamGroups($id_instance));
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
