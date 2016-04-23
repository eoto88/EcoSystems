<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

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
        $stillAliveStatus = $instance['still_alive'] ? "still-alive" : "dead";
        $pumpStatus = $instance['pump_on'] ? "on" : "off";
        $lightStatus = $instance['light_on'] ? "on" : "off";
        $fanStatus = $instance['fan_on'] ? "on" : "off";
        $heaterStatus = $instance['heater_on'] ? "on" : "off";

        return array(
            'idInstance' => $idInstance,
            'stillAliveStatus' => $stillAliveStatus,
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

            switch ($_POST['action']) {
                case 'still-alive':
                    // Do nothing here!
                    break;
                case 'heaterAndFanStatus':
                    $this->saveHeaterAndFanStatus($idInstance);
                    break;
                case 'pumpState':
                    $this->savePumpState($idInstance);
                    break;
            }
            $this->stillAlive($idInstance);
        } else {
            throw new HTTP_Exception_403;
        }
    }

    private function getInstanceId($code) {
        $mInstance = new Model_Instance();
        return $mInstance->getInstanceId($code);
    }

    private function stillAlive($idInstance) {
        $mInstance = new Model_Instance();
        $mInstance->updateStillAlive($idInstance);
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

    private function saveHeaterAndFanStatus($idInstance) {
        if (isset($_POST['fanStatus']) && isset($_POST['heaterStatus'])) {
            $mInstance = new Model_Instance();

            $fanStatus = filter_input(INPUT_POST, 'fanStatus', FILTER_SANITIZE_SPECIAL_CHARS);
            $heaterStatus = filter_input(INPUT_POST, 'heaterStatus', FILTER_SANITIZE_SPECIAL_CHARS);
            $mInstance->updateFanAndHeaterStatus($fanStatus, $heaterStatus);
        } else {
            throw new HTTP_Exception_403;
        }
    }
}
