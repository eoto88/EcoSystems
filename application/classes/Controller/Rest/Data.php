<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Data extends Controller_REST {

    public function action_index() {

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
