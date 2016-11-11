<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Instances extends Controller_REST {

    public function action_index() {
        $id = $this->request->param('id');
        $mInstance = new Model_Instance();

        if( isset($id) ) {
            echo json_encode( array($mInstance->getInstance($id, $this->_user['id_user'])) );
        } else {
            echo json_encode( $mInstance->getInstances($this->_user['id_user']) );
        }
    }

    public function action_update() {
        $id = $this->request->param('id');
        $mInstance = new Model_Instance();

        if( isset($id) ) {
            echo json_encode( $mInstance->getInstance($id, $this->_user['id_user']) );
        } else {
            echo json_encode( $mInstance->getInstances($this->_user['id_user']) );
        }
    }

    public function action_create() {
        $user = $this->_user;
        $mInstance = new Model_Instance();
        $post = $this->request->post();

        $data = json_decode(file_get_contents('php://input'), true);

        $validation = Validation::factory($data);
        $validation->rule('title', 'not_empty')->rule('title', 'max_length', array(':value', '25'));
        $validation->rule('type', 'not_empty')->rule('type', 'digit');

        if( $validation->check() ) {
            $title = filter_input(INPUT_POST, 'datetime', FILTER_SANITIZE_SPECIAL_CHARS);
            var_dump($title);

//                $mInstance->insertInstance();
        } else {
            $this->response->status(406)
                ->body(json_encode(array(
                    'messages' => array('')
                )));

        }
    }

    public function action_delete() {

    }
}
