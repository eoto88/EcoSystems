<?php defined('SYSPATH') or die('No direct script access.');

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

    public function action_index() {}

    /*public function action_getTemperatureData() {
        $mHour = new Model_Hour();
        echo json_encode($mHour->getTemperatureData());
    }*/

    public function action_chartLiveData() {
        $mHour = new Model_Hour();
        $tempData = $mHour->getLastTemperatureData();
        $tempDatetime = strtotime($tempData['datetime']. ' GMT') * 1000;

        $mQuarterHour = new Model_QuarterHour();
        $sunData = $mQuarterHour->getLastSunlightData();
        $sunDatetime = strtotime($sunData['datetime']. ' GMT') * 1000;

        echo json_encode(array(
            'roomTemperature' => array( $tempDatetime, floatval($tempData['room_temperature']) ),
            'tankTemperature' => array( $tempDatetime, floatval($tempData['tank_temperature']) ),
            'sunlight' => array($sunDatetime, $sunData['sunlight'])
        ));
    }

    public function action_lastCommunication() {
        $mLive = new Model_Live();
        $last_communication = $mLive->getLastCommunication();
        $status = "dead";
        if($last_communication['still_alive']) {
            $status = "still-alive";
        }
        echo json_encode(array(
            'status' => $status,
            'lastCommunication' => $last_communication['last_communication']
        ));
    }
}