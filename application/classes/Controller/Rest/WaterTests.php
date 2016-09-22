<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_WaterTests extends Controller_REST {

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mWater = new Model_WaterTest();

        if( isset($id) ) {
            echo json_encode( $mWater->getWaterTest($id) );
        } else if( isset($id_instance) ) {
            echo json_encode( $mWater->getWaterTests($id_instance) );
        }
    }

    public function action_update() {
        $mWater = new Model_WaterTest();

        $data = json_decode(file_get_contents('php://input'), true);


        $result = $mWater->updateWaterTest($data);

        if( $result['success']) {
            echo json_encode( $result );
        } else {
            $this->response->status(406)
                ->body(json_encode($result));
        }
    }

    public function action_create() {
        $id_instance = $this->request->param('id_instance');
        $mWater = new Model_WaterTest();

        $data = json_decode(file_get_contents('php://input'), true);

        $result = $mWater->insertWaterTest($id_instance, $data);

        if( $result['success']) {
            echo json_encode( $result );
        } else {
            $this->response->status(406)
                ->body(json_encode($result));
        }
    }

    public function action_delete() {
        $id = $this->request->param('id');
        $mWater = new Model_WaterTest();

        echo json_encode( $mWater->deleteTodo($id) );
    }
}
