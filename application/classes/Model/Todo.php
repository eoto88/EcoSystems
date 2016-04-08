<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Todo {

    public function getTodo($id_todo) {
        $query = DB::select('todo.id_todo', array('instance.title', 'instance_title'), 'todo.title', 'todo.time_unit', 'todo.interval_value')
            ->from('todo')
            ->join('instance')->on('todo.id_instance', '=', 'instance.id_instance')
            ->where('id_todo', '=', $id_todo);
        return $query->execute()->as_array();
    }

    public function getTodos($id_instance) {
        $query = DB::select('*')->from('todo')->where('id_instance', '=', $id_instance)->order_by('title', 'ASC');
        return $query->execute()->as_array();
    }
    
    public function insertTodo($data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('title', 'not_empty')->rule('title', 'max_length', array(':value', '50'));
        $validation->rule('interval_value', 'not_empty')->rule('interval_value', 'digit');

        if( $validation->check() ) {

            $query = DB::insert('todo', array(
                'id_instance', 'title', 'time_unit', 'interval_value'
            ))->values( array(
                $data['id_instance'], $data['title'], $data['time_unit'], $data['interval_value']
            ) );
            $result = $query->execute();

            $return['success'] = true;
            $return['entities'] = $this->getTodo($result[0]);;
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }

    public function getTodosWithState() {
        $query = DB::query(Database::SELECT,
            "SELECT id_todo, i.title AS instance_title, t.title, IF(DATE(last_check) > CASE time_unit
                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
                END, 1, 0) AS checked
            FROM todo AS t
            JOIN instance AS i ON i.id_instance = t.id_instance;");
        return $query->execute()->as_array();
    }

    public function getTodosByIdInstance($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT *, IF(DATE(last_check) > CASE time_unit
                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
                END, 1, 0) AS checked
            FROM todo
            WHERE id_instance = ". $id_instance .";");
        return $query->execute()->as_array();
    }
    
    public function getUncheckedTodos() {
        $query = DB::query(Database::SELECT,
            "SELECT *
            FROM todo
            WHERE DATE(last_check) < CASE time_unit
                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
                END
            OR `last_check` = '0000-00-00 00:00:00.000000';");
        return $query->execute()->as_array();
    }

    public function checkTodo($id, $done) {
        $query = null;
        if( $done ) {
            $query = DB::update('todo')->set(array('last_check' => DB::expr('NOW()')))->where('id_todo', '=', $id);
        } else {
            $query = DB::update('todo')->set(array('last_check' => '0000-00-00 00:00:00'))->where('id_todo', '=', $id);
        }
        $query->execute();
    }

    public function updateTodo($id, $title, $time_unit, $interval_value) {
        $query = DB::update('todo')->set(array(
            'title' => $title,
            'time_unit' => $time_unit,
            'interval_value' => $interval_value
        ))->where('id_todo', '=', $id);
        $query->execute();

        return $this->getTodo($id);
    }

    public function deleteTodo($id) {
        $query = DB::delete('todo')->where('id_todo', '=', $id);
        $query->execute();

        return array('success' => true);
    }
}