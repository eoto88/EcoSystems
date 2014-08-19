<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Live {

    public function getLiveData() {
        $query = DB::query(Database::SELECT, "SELECT last_communication, pump_on, light_on, fan_on, DATE_SUB(NOW(),INTERVAL 2 MINUTE) <= last_communication AS still_alive FROM live WHERE id_live = 1");
        //$query = DB::select('last_communication')->from('live')->where('id_live', '=', '1');
        return $query->execute()->current();
    }
    
    public function updatePumpStatus($pumpStatus) {
        $query = DB::update('live')->set(array('pump_on' => $pumpStatus))->where('id_live', '=', '1');
        $query->execute();
    }
}