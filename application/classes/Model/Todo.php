<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Todo {

    public function getTodo($id) {
        $query = DB::select('todo.id', 'todo.title', 'todo.time_unit', 'todo.interval_value', array('instance.id', 'id_instance'), array('instance.title', 'instance_title'))
            ->from('todo')
            ->join('instance')->on('todo.id_instance', '=', 'instance.id')
            ->where('todo.id', '=', $id);
        return $query->execute()->as_array();
    }

    public function getTodos() {
        $query = DB::query(Database::SELECT,
            "SELECT t.id AS id, t.title, id_instance, i.title AS instance_title, IF(DATE(last_check) > CASE time_unit
                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
                END, 1, 0) AS checked
            FROM todo AS t
            JOIN instance AS i ON i.id = t.id_instance
            ORDER BY t.id_instance ASC, i.title ASC;");
        return $query->execute()->as_array();
    }

    public function getTodosByIdInstance($id_instance) {
        $query = DB::query(Database::SELECT, "SELECT t.id, t.title, id_instance, i.title AS instance_title, IF(DATE(last_check) > CASE time_unit ".
                "WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY) ".
                "WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH) ".
                "END, 1, 0) AS checked ".
            "FROM todo AS t ".
            "JOIN instance AS i ON i.id = t.id_instance ".
            "WHERE i.id = :id_instance;");
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();

//        $query = DB::query(Database::SELECT,
//            "SELECT id, title, id_instance, IF(DATE(last_check) > CASE time_unit
//                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
//                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
//                END, 1, 0) AS checked
//            FROM todo
//            JOIN instance AS i ON i.id = t.id_instance
//            WHERE id_instance = ". $id_instance .";");
//        return $query->execute()->as_array();
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
    
    public function insertTodo($data) {
        $return = array();
        $config = Kohana::$config->load('app');
        $validation = Validation::factory($data);
        $validation->rule('id_instance', 'not_empty')->rule('id_instance', 'digit');
        $validation->rule('title', 'not_empty')->rule('title', 'max_length', array(':value', '50'));
        $validation->rule('interval_value', 'not_empty')->rule('interval_value', 'digit');
        $validation->rule('time_unit', 'not_empty')->rule('time_unit', 'digit');

        if( $validation->check() ) {
            // TODO Validate if time unit exist
            $data['time_unit'] = $config['time_units'][$data['time_unit']];

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

    public function updateTodo($data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('id', 'not_empty')->rule('id', 'digit');
        $validation->rule('title', 'not_empty')->rule('title', 'max_length', array(':value', '50'));
        $validation->rule('interval_value', 'not_empty')->rule('interval_value', 'digit');

        if( $validation->check() ) {

            $updateData = array(
                'title' => $data['title'],
                'interval_value' => $data['interval_value']
            );

            if(isset($data['time_unit'])) {
                $updateData['time_unit'] = $data['time_unit'];
            }

            $query = DB::update('todo')->set($updateData)->where('id', '=', $data['id']);
            $query->execute();

            $return['success'] = true;
            $return['entities'] = $this->getTodo($data['id']);
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }

    public function checkTodo($data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('id', 'not_empty')->rule('id', 'digit');
        $validation->rule('done', 'not_empty')->rule('done', 'digit');

        if( $validation->check() ) {
            $query = null;
            if( $data['done'] ) {
                $query = DB::update('todo')->set(array('last_check' => DB::expr('NOW()')))->where('id', '=', $data['id']);
            } else {
                $query = DB::update('todo')->set(array('last_check' => '0000-00-00 00:00:00'))->where('id', '=', $data['id']);
            }
            $query->execute();

            $return['success'] = true;
            $return['entities'] = $this->getTodo($data['id']);
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }

    public function deleteTodo($id) {
        $query = DB::delete('todo')->where('id', '=', $id);
        $query->execute();

        return array('success' => true);
    }
}