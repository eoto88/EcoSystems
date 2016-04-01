<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_QuarterHour {
    
    public function insertQuarterHour($idInstance, $idCurrentDay, $datetime, $sunlight) {
        $query = DB::insert('quarter_hour', array(
                'id_quarter_hour2', 'id_instance', 'id_day', 'datetime'
            ))->values( array(
                DB::expr("UNHEX(REPLACE(UUID(),'-',''))"), $idInstance, $idCurrentDay, /*gmdate("Y-m-d H:i:s",*/ $datetime/*)*/
            ) );
        $mLog = new Model_Log();
        $mLog->log( "debug", $query );
        $query->execute();
    }

    public function deleteQuarterHours($date) {
        $query = DB::delete('quarter_hour')->where(DB::expr('DATE(datetime)'), '=', $date);
        $query->execute();
    }
}