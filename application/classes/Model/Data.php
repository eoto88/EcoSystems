<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Data {
    public function getLastData($idInstance) {
        $query = DB::select(
            DB::expr($this->getExprDatetime().'  AS datetime'),
            'humidity',
            'room_temperature',
            'tank_temperature'
        )->from('data')->where('id_instance', '=', $idInstance)->order_by('timestamp', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }

    public function getData($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT " . $this->getExprDatetime() .'  AS datetime'.
            ", humidity, room_temperature, tank_temperature FROM ( SELECT * FROM data WHERE id_instance = ". $idInstance ." ORDER BY timestamp DESC LIMIT 40 ) sub ORDER BY datetime ASC"
        );

        return $query->execute()->as_array();
    }

    // TODO Use this to get data average (Never used yet...)
    public function getDataAverageByDay($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT DATE(". $this->getExprDatetime() .") AS `date`, ROUND(AVG(room_temperature), 1) AS avg_room_temp, ROUND(AVG(tank_temperature), 1) AS avg_tank_temp, ROUND(AVG(humidity), 1) AS avg_humidity ".
            "FROM data ".
            "WHERE DATE(". $this->getExprDatetime() .") <= DATE(SUBDATE(current_date, 2)) AND id_instance = ". $idInstance ." ".
            "GROUP BY `date`"
        );
        return $query->execute()->as_array();
    }

    function insertData($idInstance, $data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('roomTemperature', 'decimal', array(':value', '1'));
        $validation->rule('tankTemperature', 'decimal', array(':value', '1'));
        $validation->rule('humidity', 'decimal', array(':value', '1'));

        // FIXME Validate code
        // FIXME Validate datetime

        if( $validation->check() ) {
            if( ! isset($data['datetime']) ) {
                $data['datetime'] = null;
            }
            if( ! isset($data['humidity']) ) {
                $data['humidity'] = null;
            }

            $query = DB::insert('data', array(
                'id',
                'id_instance',
                'datetime',
                'humidity',
                'room_temperature',
                'tank_temperature'
            ))->values( array(
                DB::expr("UUID()"),
                $idInstance,
                $data['datetime'],
                $data['humidity'],
                $data['roomTemperature'],
                $data['tankTemperature']
            ) );
            $query->execute();

            $return['success'] = true;
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }

    private function getExprDatetime() {
        return "IF(datetime IS NOT NULL, CONVERT_TZ(datetime, '+00:00', '". $this->getOffset() ."'), timestamp)";
    }

    private function getOffset() {
        $time = new DateTime('now', new DateTimeZone(date_default_timezone_get()) );
        return $time->format('P');
    }
}