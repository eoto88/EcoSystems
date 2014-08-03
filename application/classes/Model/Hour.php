<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Hour {

    public function getTemperatureData() {
        $query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->order_by('datetime', 'ASC')->limit(20)->offset(0);
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
                '1', date("Y-m-d H:i:s", $datetime), $roomTemperature, $tankTemperature
            ) );
        $query->execute();
    }
}