<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Hour {

    public function getTemperatureData() {
        // SELECT * FROM ( SELECT * FROM temperature ORDER BY id_temperature DESC LIMIT 1 ) sub ORDER BY id_temperature ASC
        $query = DB::query(Database::SELECT, "SELECT datetime, room_temperature, tank_temperature FROM ( SELECT * FROM hour ORDER BY id_hour DESC LIMIT 40 ) sub ORDER BY id_hour ASC");

        //$query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->order_by('datetime', 'DESC')->limit(20)->offset(0);
        return $query->execute()->as_array();
    }

    public function getLastTemperatureData() {
        $query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }
    
    public function getLastDaysTempAverage() {
        $query = DB::query(Database::SELECT,
                "SELECT DATE(datetime) AS `date`, AVG(room_temperature) AS avg_room_temp, AVG(tank_temperature) AS avg_tank_temp"
                . " FROM hour"
                . " WHERE DATE(datetime) <= DATE(SUBDATE(current_date, 2))"
                . " GROUP BY DATE(datetime)");
        return $query->execute()->as_array();
    }
    
    public function insertHour($datetime, $roomTemperature, $tankTemperature) {
        $model_day = new Model_Day();
        $currentDayId = $model_day->getCurrentDayId();
        $query = DB::insert('hour', array(
            'id_day', 'datetime', 'room_temperature', 'tank_temperature'
            ))->values( array(
                $currentDayId, gmdate("Y-m-d H:i:s", $datetime), $roomTemperature, $tankTemperature
            ) );
        $query->execute();
    }
    
    public function deleteHours($date) {
        $query = DB::delete('hour')->where(DB::expr('DATE(datetime)'), '=', $date);
        $query->execute();
    }
}