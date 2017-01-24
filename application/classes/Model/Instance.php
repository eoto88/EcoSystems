<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Instance {

    public function getInstanceId($code) {
        $query = DB::select('id')->from('instance')->where('code', '=', $code);
        $ins = $query->execute()->current();
        return $ins['id'];
    }

    public function getInstance($idInstance, $idUser) {
        $query = DB::query(Database::SELECT, "SELECT instance.id, code, instance.icon, instance.title, instance_type.title AS instance_type, type, last_communication, pump_on, light_on, fan_on, monitored, water_tests, DATE_SUB(NOW(),INTERVAL 1 MINUTE) <= last_communication AS heartbeat ".
            "FROM instance ".
            "JOIN instance_type ON type = instance_type.id ".
            "WHERE id_user = :id_user ".
            "AND instance.id = :id_instance");
        $query->param(':id_instance', $idInstance);
        $query->param(':id_user', $idUser);
        return $query->execute()->current();
    }

    public function getInstances($idUser) {
        $query = DB::query(Database::SELECT,
            "SELECT instance.id, instance.title, instance.icon, instance_type.title AS instance_type, type, last_communication, pump_on, light_on, fan_on, monitored, water_tests, DATE_SUB(NOW(),INTERVAL 1 MINUTE) <= last_communication AS heartbeat ".
            "FROM instance ".
            "JOIN instance_type ON type = instance_type.id ".
            "WHERE id_user = :id_user"
        );
        $query->param(':id_user', $idUser);
        return $query->execute()->as_array();
    }

    /**
     * @deprecated
     *
     * @param $idInstance
     */
    public function updateHeartbeat($idInstance) {
        $query = DB::query(Database::UPDATE, "UPDATE instance SET last_communication = NOW() WHERE id = :idInstance");
        $query->param(':idInstance', $idInstance);
        $query->execute();
    }

    public function getLiveData($idInstance = null) {
        if( $idInstance ) {
            $query = DB::query(Database::SELECT,
                "SELECT last_communication, pump_on, light_on, fan_on, heater_on, DATE_SUB(NOW(),INTERVAL 1 MINUTE) <= last_communication AS heartbeat FROM instance WHERE id = " . $idInstance
            );
            return $query->execute()->current();
        } else {
            $query = DB::query(Database::SELECT,
                "SELECT id, last_communication, pump_on, light_on, fan_on, heater_on, DATE_SUB(NOW(),INTERVAL 1 MINUTE) <= last_communication AS heartbeat FROM instance"
            );
            return $query->execute()->as_array();
        }
    }

    public function insertInstance($idUser, $data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('title', 'not_empty')->rule('title', 'max_length', array(':value', '25'));
        $validation->rule('icon', 'not_empty')->rule('title', 'max_length', array(':value', '25'));
        $validation->rule('type', 'not_empty')->rule('type', 'digit');

        $monitored = (isset($data['monitored']) ? $data['monitored'] : 0);
        $waterTests = (isset($data['water_tests']) ? $data['water_tests'] : 0);

        if( $validation->check() ) {
            $query = DB::insert('instance', array(
                'title', 'icon', 'id_user', 'code', 'type', 'monitored', 'water_tests'
            ))->values(array(
                $data['title'], $data['icon'], $idUser, DB::expr("UUID()"), $data['type'], $monitored, $waterTests
            ));
            $result = $query->execute();

            $return['success'] = true;
            $return['entities'] = $this->getInstance($result[0], $idUser);;
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }

    public function updateLightState($lightState, $idInstance) {
        $query = DB::update('instance')->set(array('light_on' => $lightState))->where('id', '=', $idInstance);
        $query->execute();
    }

    public function updatePumpState($pumpStatus, $idInstance) {
        $query = DB::update('instance')->set(array('pump_on' => $pumpStatus))->where('id', '=', $idInstance);
        $query->execute();
    }
}