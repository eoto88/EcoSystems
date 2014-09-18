<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Todo {

    public function getLastSunlightData() {
        $query = DB::select('datetime', 'sunlight')->from('quarter_hour')->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }
    
    public function insertQuarterHour($datetime, $sunlight) {
        $query = DB::insert('quarter_hour', array(
            'id_day', 'datetime', 'sunlight'
            ))->values( array(
                '1', gmdate("Y-m-d H:i:s", $datetime), $sunlight
            ) );
        $query->execute();
    }
    
    public function checkTodos() {
        // SELECT * FROM `todo` WHERE DATE_SUB(NOW(), INTERVAL `nb_month` MONTH) > `last_check` OR `last_check` = '0000-00-00 00:00:00.000000'
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
    
    public function updateTodo($id) {
        $query = DB::update('todo')->set(array('last_check' => DB::expr('NOW()')))->where('id_todo', '=', $id);
        $query->execute();
    }
}