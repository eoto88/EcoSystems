<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_logs extends Controller_REST {

    protected $entityName = 'log';

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mLog = new Model_Log();

        if( ! empty($id_instance) && ! empty($id)) {
            // TODO
            //echo json_encode( $mData->getTodo($id) );
        } else if( ! empty($id_instance)) {
            $this->respond( $mLog->getInstanceLastLogs($id_instance) );
        } else {
            $this->respond( $mLog->getLastLogs($this->_user['id_user']) );
        }
    }

    public function action_update() {

    }

    public function action_create() {
        $id_instance = $this->request->param('id_instance');
        $mData= new Model_Data();

//        $data = json_decode(file_get_contents('php://input'), true);
        $result = $mData->insertData($id_instance, $this->request->post());

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
