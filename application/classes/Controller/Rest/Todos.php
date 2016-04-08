<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Todos extends Controller_REST {

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id_todo = $this->request->param('id');
        $mTodo = new Model_Todo();

        if( isset($id_todo) ) {
            echo json_encode( $mTodo->getTodo($id_todo) );
        } else if( isset($id_instance) ) {
            echo json_encode( $mTodo->getTodos($id_instance) );
        }
    }

    public function action_update() {
        $id_instance = $this->request->param('id_instance');
        $id_todo = $this->request->param('id');
        $mTodo = new Model_Todo();

        $data = json_decode(file_get_contents('php://input'), true);

        $validation = Validation::factory($data);
        $validation->rule('title', 'max_length', array(':value', '50'));

        if( $validation->check() ) {
            if( isset($data['done']) ) {
                $result = $mTodo->checkTodo($id_todo, $data['done']);
            } else if( isset($data['title']) ) {
                $result = $mTodo->updateTodo($id_todo, $data['title'], $data['time_unit'], $data['interval_value']);
            }

            echo json_encode( $result );
        } else {
            $this->response->status(406)
                ->body(json_encode(array(
                    'messages' => array('')
                )));

        }
    }

    public function action_create() {
        $mTodo = new Model_Todo();

        $data = json_decode(file_get_contents('php://input'), true);

        $result = $mTodo->insertTodo($data);

        if( $result['success']) {
            echo json_encode( $result );
        } else {
            $this->response->status(406)
                ->body(json_encode($result));
        }
    }

    public function action_delete() {
        $id_todo = $this->request->param('id');
        $mTodo = new Model_Todo();

        echo json_encode( $mTodo->deleteTodo($id_todo) );
    }
}
