<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_ApiInstance extends Controller {

    public function before() {
        //if(!$this->request->is_ajax()) // && Request::$client_ip != '255.255.255.255'
        //    throw new HTTP_Exception_403;
        parent::before();
        header('Content-Type: application/json');
    }

    public function after() {
        parent::after();
    }

    public function action_chartLiveData() {
        $idInstance = $this->request->param('id');
        $mData = new Model_Data();
        $data = $mData->getLastData($idInstance);
        $tempDatetime = strtotime($data['datetime']) * 1000;

        echo json_encode(array(
            'roomTemperature' => array($tempDatetime, floatval($data['room_temperature'])),
            'tankTemperature' => array($tempDatetime, floatval($data['tank_temperature']))
        ));
    }

    public function action_getLiveData() {
        $idInstance = $this->request->param('id');
        $mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData($idInstance);

        if( $idInstance ) {
            echo json_encode( $this->prepareLiveData($idInstance, $liveData) );
        } else {
            $returnData = array();
            foreach($liveData as $instance) {
                $returnData[] = $this->prepareLiveData($instance['id_instance'], $instance);
            }
            echo json_encode( $returnData );
        }
    }

    private function prepareLiveData($idInstance, $instance) {
        $heartbeatStatus = $instance['heartbeat'] ? "heartbeat" : "dead"; // FIXME
        $pumpStatus = $instance['pump_on'] ? "on" : "off";
        $lightStatus = $instance['light_on'] ? "on" : "off";
        $fanStatus = $instance['fan_on'] ? "on" : "off";
        $heaterStatus = $instance['heater_on'] ? "on" : "off";

        return array(
            'idInstance' => $idInstance,
            'heartbeatStatus' => $heartbeatStatus,
            'lastCommunication' => $instance['last_communication'],
            'pumpStatus' => $pumpStatus,
            'lightStatus' => $lightStatus,
            'fanStatus' => $fanStatus,
            'heaterStatus' => $heaterStatus
        );
    }

    public function action_postData() {
        if( isset($_POST['code']) && isset($_POST['params']) ) {
            $idInstance = $this->getInstanceId(filter_input(INPUT_POST, 'code', FILTER_SANITIZE_SPECIAL_CHARS));

            if($idInstance != null) {
                $params = json_decode($_POST['params'], true);

                if($params) {
                    $mData = new Model_Data();
                    $mData->insertParamsData($idInstance, $_POST);
                } else {
                    $return['errors'] = "Not valid json.";
                }
            } else {
                $return['errors'] = "Unknown instance.";
            }
        }
        if ( isset($_POST['code']) && isset($_POST['action']) ) {
            $idInstance = $this->getInstanceId(filter_input(INPUT_POST, 'code', FILTER_SANITIZE_SPECIAL_CHARS));
            $datetime = null;
            $action = $_POST['action'];

            switch ($action) {
                case 'log':
                    $mLog = new Model_Log();
                    $mLog->log($idInstance, filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS), filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));

                    break;
            }
            $this->updateHeartbeat($idInstance);
        } elseif(isset($_GET['code'])) {
            echo gmdate("U");
        }else {
            throw new HTTP_Exception_403;
        }
    }

    private function getInstanceId($code) {
        $mInstance = new Model_Instance();
        return $mInstance->getInstanceId($code);
    }

    private function updateHeartbeat($idInstance) {
        $mInstance = new Model_Instance();
        $mInstance->updateHeartbeat($idInstance);
    }

    private function savePumpState($idInstance) {
        if (isset($_POST['pumpState'])) {
            $mInstance = new Model_Instance();

            $pumpState = filter_input(INPUT_POST, 'pumpState', FILTER_SANITIZE_SPECIAL_CHARS);
            $mInstance->updatePumpState($pumpState, $idInstance);
        } else {
            throw new HTTP_Exception_403;
        }
    }

    private function saveLightState($idInstance) {
        if (isset($_POST['lightState'])) {
            $mInstance = new Model_Instance();

            $lightState = filter_input(INPUT_POST, 'lightState', FILTER_SANITIZE_SPECIAL_CHARS);
            $mInstance->updateLightState($lightState, $idInstance);
        } else {
            throw new HTTP_Exception_403;
        }
    }
}
