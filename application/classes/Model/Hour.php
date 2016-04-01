<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Hour {

    private function getOffset() {
        $time = new DateTime('now', new DateTimeZone(date_default_timezone_get()) );
        return $time->format('P');
    }

    public function getHourData($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT CONVERT_TZ(datetime, '+00:00', '". $this->getOffset() ."') AS datetime, humidity, room_temperature, tank_temperature FROM ( SELECT * FROM hour WHERE id_instance = ". $idInstance ." ORDER BY datetime DESC LIMIT 40 ) sub ORDER BY datetime ASC"
        );

        return $query->execute()->as_array();
    }

    public function getLastTemperatureData($idInstance) {
        $query = DB::select(DB::expr("CONVERT_TZ(datetime, '+00:00', '". $this->getOffset() ."') AS datetime"), 'humidity', 'room_temperature', 'tank_temperature')->from('hour')->where('id_instance', '=', $idInstance)->order_by('datetime', 'DESC')->limit(1)->offset(0);
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
    
    public function insertHour($idInstance, $idCurrentDay, $datetime, $humidity, $roomTemperature, $tankTemperature) {
        if( $datetime == null ) {
            $datetime = 'NOW()';
        }
        $query = DB::insert('hour', array(
            'id_hour', 'id_instance', 'id_day', 'datetime', 'humidity', 'room_temperature', 'tank_temperature'
        ))->values( array(
            DB::expr("UNHEX(REPLACE(UUID(),'-',''))"), $idInstance, $idCurrentDay, /*gmdate("Y-m-d H:i:s",*/ $datetime/*)*/, $humidity, $roomTemperature, $tankTemperature
        ) );
        $query->execute();
    }
    
    public function deleteHours($date) {
        $query = DB::delete('hour')->where(DB::expr('DATE(datetime)'), '=', $date);
        $query->execute();
    }
}