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

    public function action_chartLiveData($idInstance) {
        $idInstance = 1;
        $mHour = new Model_Hour();
        $tempData = $mHour->getLastTemperatureData($idInstance);
        $tempDatetime = strtotime($tempData['datetime']) * 1000;

        $mQuarterHour = new Model_QuarterHour();
        $sunData = $mQuarterHour->getLastSunlightData($idInstance);
        $sunDatetime = strtotime($sunData['datetime']) * 1000;

        echo json_encode(array(
            'roomTemperature' => array($tempDatetime, floatval($tempData['room_temperature'])),
            'tankTemperature' => array($tempDatetime, floatval($tempData['tank_temperature'])),
            'sunlight' => array($sunDatetime, intval($sunData['sunlight']) * 100 / 1024)
        ));
    }

    public function action_getLiveData() {
        $idInstance = 1;
        $mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData($idInstance);
        $stillAliveStatus = "dead";
        if ($liveData['still_alive']) {
            $stillAliveStatus = "still-alive";
        }
        $pumpStatus = "off";
        $lightStatus = "off";
        $fanStatus = "off";
        $heaterStatus = "off";
        if ($liveData['pump_on']) {
            $pumpStatus = "on";
        }
        if ($liveData['light_on']) {
            $lightStatus = "on";
        }
        if ($liveData['fan_on']) {
            $fanStatus = "on";
        }
        if ($liveData['heater_on']) {
            $heaterStatus = "on";
        }
        echo json_encode(array(
            'stillAliveStatus' => $stillAliveStatus,
            'lastCommunication' => $liveData['last_communication'],
            'pumpStatus' => $pumpStatus,
            'lightStatus' => $lightStatus,
            'fanStatus' => $fanStatus,
            'heaterStatus' => $heaterStatus
        ));
    }
    
    public function action_updateToDo() {
        $id = $this->request->param('id');
        $mToDo = new Model_Todo();
        $mToDo->updateTodo($id);
        echo json_encode(array('id' => $id));
    }

    public function action_postData() {
        if ( isset($_POST['pass']) && isset($_POST['action']) && isset($_POST['datetime']) ) {
            $idInstance = $this->getInstanceId(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS));
            $datetime = filter_input(INPUT_POST, 'datetime', FILTER_SANITIZE_SPECIAL_CHARS);
            switch ($_POST['action']) {
                case 'still-alive':
                    // Do nothing here!
                    break;
                case 'heaterAndFanStatus':
                    $this->saveHeaterAndFanStatus($idInstance);
                    break;
                case 'temperature':
                    $this->saveTemperatureData($idInstance, $datetime);
                    break;
                case 'sunlight':
                    $this->saveSunlightData($idInstance, $datetime);
                    break;
                case 'pumpStatus':
                    $this->savePumpStatus($idInstance);
                    break;
                case 'sunrise':
                    $mDay = new Model_Day();
                    $mDay->updateSunrise($idInstance, $datetime);

                    break;
                case 'sunset':
                    $mDay = new Model_Day();
                    $mDay->updateSunset($idInstance, $datetime);
                    
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

    private function savePumpStatus($idInstance) {
        if (isset($_POST['pumpStatus'])) {
            $mInstance = new Model_Instance();

            $pumpStatus = filter_input(INPUT_POST, 'pumpStatus', FILTER_SANITIZE_SPECIAL_CHARS);
            $mInstance->updatePumpStatus($pumpStatus);
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

    private function saveTemperatureData($idInstance, $datetime) {
        if (isset($_POST['roomTemperature']) && isset($_POST['tankTemperature'])) {
            $mHour = new Model_Hour();
            $idCurrentDay = $this->getCurrentDayId($idInstance);

            $roomTemperature = filter_input(INPUT_POST, 'roomTemperature', FILTER_SANITIZE_SPECIAL_CHARS);
            $tankTemperature = filter_input(INPUT_POST, 'tankTemperature', FILTER_SANITIZE_SPECIAL_CHARS);
            $mHour->insertHour($idInstance, $idCurrentDay, $datetime, $roomTemperature, $tankTemperature);
        } else {
            throw new HTTP_Exception_403;
        }
    }

    private function saveSunlightData($idInstance, $datetime) {
        if (isset($_POST['sunlight'])) {
            $mQuarterHour = new Model_QuarterHour();
            $idCurrentDay = $this->getCurrentDayId($idInstance);

            $sunlight = filter_input(INPUT_POST, 'sunlight', FILTER_SANITIZE_SPECIAL_CHARS);
            $mQuarterHour->insertQuarterHour($idInstance, $idCurrentDay, $datetime, $sunlight);
        } else {
            throw new HTTP_Exception_403;
        }
    }

    private function getCurrentDayId($idInstance) {
        $model_day = new Model_Day();
        return $model_day->getCurrentDayId($idInstance);
    }
}
