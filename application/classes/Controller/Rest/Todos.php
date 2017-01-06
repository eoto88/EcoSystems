<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Todos extends Controller_REST {

    public function action_index() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mTodo = new Model_Todo();

        if( isset($id) ) {
            echo json_encode( $mTodo->getTodo($id) );
        } else if( isset($id_instance) ) {
            echo json_encode( $mTodo->getTodosByIdInstance($id_instance) );
        }
    }

    public function action_update() {
        $id_instance = $this->request->param('id_instance');
        $id = $this->request->param('id');
        $mTodo = new Model_Todo();
        $result = null;

        $data = json_decode(file_get_contents('php://input'), true);

        if( isset($data['done']) ) {
            $result = $mTodo->checkTodo($data);
        } else if( isset($data['title']) ) {
            $result = $mTodo->updateTodo($data);
        }

        if( $result['success']) {
            echo json_encode( $result );
        } else {
            $this->response->status(406)->body(json_encode($result));
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
        $id = $this->request->param('id');
        $mTodo = new Model_Todo();

        echo json_encode( $mTodo->deleteTodo($id) );
    }
}
