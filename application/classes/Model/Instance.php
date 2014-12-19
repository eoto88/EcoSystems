<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Instance {

    public function getInstanceId($code) {
        $query = DB::select('id_instance')->from('instance')->where('code', '=', $code);
        $ins = $query->execute()->current();
        return $ins['id_instance'];
    }

    public function getInstance($idInstance) {
        $query = DB::select('*')->from('instance')->where('id_instance', '=', $idInstance);
        return $query->execute()->current();
    }

    public function getInstances() {
        $query = DB::select('*')->from('instance');
        return $query->execute()->as_array();
    }

    public function updateStillAlive($idInstance) {
        $query = DB::query(Database::UPDATE, "UPDATE instance SET last_communication = NOW() WHERE id_instance = :idInstance");
        $query->param(':idInstance', $idInstance);
        $query->execute();
    }

    public function getLiveData($idInstance = null) {
        if( $idInstance ) {
            $query = DB::query(Database::SELECT,
                "SELECT last_communication, pump_on, light_on, fan_on, heater_on, DATE_SUB(NOW(),INTERVAL 2 MINUTE) <= last_communication AS still_alive FROM instance WHERE id_instance = " . $idInstance
            );
            return $query->execute()->current();
        } else {
            $query = DB::query(Database::SELECT,
                "SELECT id_instance, last_communication, pump_on, light_on, fan_on, heater_on, DATE_SUB(NOW(),INTERVAL 2 MINUTE) <= last_communication AS still_alive FROM instance"
            );
            return $query->execute()->as_array();
        }
    }

    public function updateLightState($lightState, $idInstance) {
        $query = DB::update('instance')->set(array('light_on' => $lightState))->where('id_instance', '=', $idInstance);
        $query->execute();
    }

    public function updatePumpState($pumpStatus, $idInstance) {
        $query = DB::update('instance')->set(array('pump_on' => $pumpStatus))->where('id_instance', '=', $idInstance);
        $query->execute();
    }

    public function updateFanAndHeaterStatus($fanStatus, $heaterStatus) {
        $query = DB::update('instance')->set(array('fan_on' => $fanStatus, 'heater_on' => $heaterStatus))->where('id_instance', '=', '1');
        $query->execute();
    }
}