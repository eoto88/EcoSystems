<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Todo {

    public function getTodos() {
        $query = DB::select('*')->from('todo')->order_by('title', 'ASC');
        return $query->execute()->as_array();
    }
    
    public function insertQuarterHour($datetime, $sunlight) {
        $query = DB::insert('quarter_hour', array(
            'id_day', 'datetime', 'sunlight'
            ))->values( array(
                '1', gmdate("Y-m-d H:i:s", $datetime), $sunlight
            ) );
        $query->execute();
    }

    public function getTodosWithState() {
        $query = DB::query(Database::SELECT,
            "SELECT *, IF(DATE(last_check) > CASE time_unit
                WHEN \"DAY\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` DAY)
                WHEN \"MONTH\" THEN DATE_SUB(NOW(), INTERVAL `interval_value` MONTH)
                END, 1, 0) AS checked
            FROM todo;");
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
    
    public function updateTodo($id, $done) {
        $query = null;
        if( $done ) {
            $query = DB::update('todo')->set(array('last_check' => DB::expr('NOW()')))->where('id_todo', '=', $id);
        } else {
            $query = DB::update('todo')->set(array('last_check' => '0000-00-00 00:00:00'))->where('id_todo', '=', $id);
        }
        $query->execute();
    }
}