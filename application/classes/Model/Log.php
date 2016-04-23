<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Log {
    public function getLogs() {
        $query = DB::select('*')->from('log')->order_by('timestamp', 'DESC');
        return $query->execute()->as_array();
    }

    public function getLastLogs() {
        $query = DB::select('*')->from('log')->order_by('timestamp', 'DESC')->order_by('id', 'DESC')->limit(10);
        return $query->execute()->as_array();
    }

    public function log($type, $message) {
        $query = DB::insert('log', array(
            'type', 'message'
            ))->values( array(
                $type, $message
            ) );
        $query->execute();
    }
}