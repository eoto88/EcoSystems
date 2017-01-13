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
        if ( isset($_POST['pass']) && isset($_POST['action']) ) {
            $idInstance = $this->getInstanceId(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS));
            $datetime = null;
            $action = $_POST['action'];
            $mParam = new Model_Param();
            $idParam = 0;
            $param = $mParam->getParamByAlias($idInstance, $action);

            if($param) {
                $idParam = $param['id'];
            }

            switch ($action) {
                case 'heartbeat':
                    // Do nothing here!
                    break;
                case 'data':
                    $mData = new Model_Data();
                    $mData->insertData($idInstance, array(
                        'id_param' => $idParam,
                        'roomTemperature' => filter_input(INPUT_POST, 'roomTemperature', FILTER_SANITIZE_SPECIAL_CHARS),
                        'tankTemperature' => filter_input(INPUT_POST, 'tankTemperature', FILTER_SANITIZE_SPECIAL_CHARS),
                        'humidity' => filter_input(INPUT_POST, 'humidity', FILTER_SANITIZE_SPECIAL_CHARS)
                    ));
                    break;
                case 'log':
                    $mLog = new Model_Log();
                    $mLog->log($idInstance, filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS), filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));

                    break;
                case 'pumpState':
                    $this->savePumpState($idInstance);
                    break;
                case 'lightState':
                    $this->saveLightState($idInstance);
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
