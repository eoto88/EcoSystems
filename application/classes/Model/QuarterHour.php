<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_QuarterHour {

    public function getSunlightData() {
        $query = DB::select('datetime', 'sunlight')->from('quarter_hour')->order_by('datetime', 'ASC')->limit(20)->offset(0);
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
                '1', date("Y-m-d H:i:s", $datetime), $sunlight
            ) );
        $query->execute();
    }
}