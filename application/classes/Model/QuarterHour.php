<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_QuarterHour {

    public function getSunlightData() {
        $query = DB::query(Database::SELECT, "SELECT datetime, sunlight FROM ( SELECT * FROM quarter_hour ORDER BY id_quarter_hour DESC LIMIT 80 ) sub ORDER BY id_quarter_hour ASC");

        //$query = DB::select('datetime', 'sunlight')->from('quarter_hour')->order_by('datetime', 'DESC')->limit(20)->offset(0);
        return $query->execute()->as_array();
    }

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
}