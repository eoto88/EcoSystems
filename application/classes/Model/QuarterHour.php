<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_QuarterHour {

    public function getSunlightData($idInstance) {
        $query = DB::query(Database::SELECT, "SELECT datetime, sunlight FROM ( SELECT * FROM quarter_hour WHERE id_instance = ". $idInstance ." ORDER BY id_quarter_hour DESC LIMIT 80 ) sub ORDER BY id_quarter_hour ASC");

        //$query = DB::select('datetime', 'sunlight')->from('quarter_hour')->order_by('datetime', 'DESC')->limit(20)->offset(0);
        return $query->execute()->as_array();
    }

    public function getLastSunlightData($idInstance) {
        $query = DB::select('datetime', 'sunlight')->from('quarter_hour')->where('id_instance', '=', $idInstance)->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }
    
    public function insertQuarterHour($idInstance, $idCurrentDay, $datetime, $sunlight) {
        $query = DB::insert('quarter_hour', array(
                'id_instance', 'id_day', 'datetime', 'sunlight'
            ))->values( array(
                $idInstance, $idCurrentDay, gmdate("Y-m-d H:i:s", $datetime), $sunlight
            ) );
        $query->execute();
    }

    public function deleteQuarterHours($date) {
        $query = DB::delete('quarter_hour')->where(DB::expr('DATE(datetime)'), '=', $date);
        $query->execute();
    }
}