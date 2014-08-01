<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Live {

    public function getLastCommunication() {
        $query = DB::select('last_communication')->from('live')->where('id_live', '=', '1');
        return $query->execute()->current();
    }
}