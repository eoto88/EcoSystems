<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Data extends Controller_REST {

    protected $entityName = 'data';

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mData = new Model_Data();

        if( isset($id) ) {
            // TODO
            //echo json_encode( $mData->getTodo($id) );
        } else if( isset($id_instance) ) {
            $this->respond( $mData->getLastData($id_instance) );
        }
    }

    public function action_update() {

    }

    public function action_create() {
        $id_instance = $this->request->param('id_instance');
        $mData= new Model_Data();

        $data = json_decode(file_get_contents('php://input'), true);

        $result = $mData->insertParamsData($id_instance, $data);

        if( $result['success']) {
            echo json_encode($result);
        } else {
            $this->response->status(406)
                ->body(json_encode($result));
        }
    }

    public function action_delete() {

    }
}
