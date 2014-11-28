<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Hour {

    public function getTemperatureData($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT datetime, room_temperature, tank_temperature FROM ( SELECT * FROM hour WHERE id_instance = ". $idInstance ." ORDER BY id_hour DESC LIMIT 40 ) sub ORDER BY id_hour ASC"
        );

        return $query->execute()->as_array();
    }

    public function getLastTemperatureData($idInstance) {
        $query = DB::select('datetime', 'room_temperature', 'tank_temperature')->from('hour')->where('id_instance', '=', $idInstance)->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }
    
    public function getLastDaysTempAverage($idInstance) {
        $query = DB::query(Database::SELECT,
                "SELECT DATE(datetime) AS `date`, AVG(room_temperature) AS avg_room_temp, AVG(tank_temperature) AS avg_tank_temp"
                . " FROM hour"
                . " WHERE DATE(datetime) <= DATE(SUBDATE(current_date, 2)) AND id_instance = ". $idInstance
                . " GROUP BY DATE(datetime)");
        return $query->execute()->as_array();
    }
    
    public function insertHour($idInstance, $idCurrentDay, $datetime, $roomTemperature, $tankTemperature) {
        $query = DB::insert('hour', array(
            'id_instance', 'id_day', 'datetime', 'room_temperature', 'tank_temperature'
        ))->values( array(
            $idInstance, $idCurrentDay, gmdate("Y-m-d H:i:s", $datetime), $roomTemperature, $tankTemperature
        ) );
        $query->execute();
    }
    
    public function deleteHours($date) {
        $query = DB::delete('hour')->where(DB::expr('DATE(datetime)'), '=', $date);
        $query->execute();
    }
}