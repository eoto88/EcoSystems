<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Hour {

    public function getTemperatureData() {
        // SELECT * FROM ( SELECT * FROM temperature ORDER BY id_temperature DESC LIMIT 1 ) sub ORDER BY id_temperature ASC
        $query = DB::query(Database::SELECT, "SELECT datetime, room_temperature, tank_temperature FROM ( SELECT * FROM hour ORDER BY id_hour DESC LIMIT 20 ) sub ORDER BY id_hour ASC");

        //$query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->order_by('datetime', 'DESC')->limit(20)->offset(0);
        return $query->execute()->as_array();
    }

    public function getLastTemperatureData() {
        $query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }
    
    public function insertHour($datetime, $roomTemperature, $tankTemperature) {
        $query = DB::insert('hour', array(
            'id_day', 'datetime', 'room_temperature', 'tank_temperature'
            ))->values( array(
                '1', gmdate("Y-m-d H:i:s", $datetime), $roomTemperature, $tankTemperature
            ) );
        $query->execute();
    }
}