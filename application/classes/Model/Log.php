<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Log {
    public function getLogs() {
        $query = DB::select('*')->from('log')->order_by('datetime', 'DESC');
        return $query->execute()->as_array();
    }

    public function getInstanceLastLogs($id_instance) {
        $query = DB::select('*')->from('log')->where('id_instance', '=', $id_instance)->order_by('datetime', 'DESC')->limit(15);
        return $query->execute()->as_array();
    }

    public function getLastLogs($id_user) {
        $query = DB::select('log.id', 'log.id_instance', 'log.type', 'log.datetime', 'log.message')->from('log')
            ->join('instance')->on('instance.id', '=', 'log.id_instance')
            ->where('instance.id_user', '=', $id_user)
            ->order_by('log.datetime', 'DESC')->limit(15);
        return $query->execute()->as_array();
    }

    public function log($idInstance, $type, $message) {
        $query = DB::insert('log', array(
            'id_instance', 'type', 'message'
        ))->values( array(
            $idInstance, $type, $message
        ));
        $query->execute();
    }
}