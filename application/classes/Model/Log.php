<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Log {
    public function log($type, $message) {
        $query = DB::insert('log', array(
            'type', 'message'
            ))->values( array(
                $type, $message
            ) );
        $query->execute();
    }
}