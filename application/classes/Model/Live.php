<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Live {

    public function getLastCommunication() {
        $query = DB::query(Database::SELECT, "SELECT last_communication, DATE_SUB(CURDATE(),INTERVAL 2 MINUTE) >= last_communication AS still_alive FROM live WHERE id_live = 1");
        //$query = DB::select('last_communication')->from('live')->where('id_live', '=', '1');
        return $query->execute()->current();
    }
}