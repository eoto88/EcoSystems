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
            'sunlight' => array($sunDatetime, intval($sunData['sunlight']) * 100 / 1024)
        ));
    }

    public function action_getLiveData() {
        $mLive = new Model_Live();
        $liveData = $mLive->getLiveData();
        $stillAliveStatus = "dead";
        if($liveData['still_alive']) {
            $stillAliveStatus = "still-alive";
        }
        $pumpStatus = "off";
        if($liveData['pump_on']) {
            $pumpStatus = "on";
        }
        echo json_encode(array(
            'stillAliveStatus' => $stillAliveStatus,
            'lastCommunication' => $liveData['last_communication'],
            'pumpStatus' => $pumpStatus
        ));
    }
    
    public function action_postData() {
        if( isset($_POST['action']) && isset($_POST['datetime']) ) {
            $datetime = filter_input(INPUT_POST, 'datetime', FILTER_SANITIZE_SPECIAL_CHARS);
            switch($_POST['action']) {
                case 'still-alive':
                    // Do nothing here!
                    break;
                case 'temperature':
                    if( isset($_POST['roomTemperature']) && isset($_POST['tankTemperature']) ) {
                        $mHour = new Model_Hour();
                        //$id_day = $mDay->getCurrentDayId();
                        
                        $roomTemperature = filter_input(INPUT_POST, 'roomTemperature', FILTER_SANITIZE_SPECIAL_CHARS);
                        $tankTemperature = filter_input(INPUT_POST, 'tankTemperature', FILTER_SANITIZE_SPECIAL_CHARS);
                        $mHour->insertHour($datetime, $roomTemperature, $tankTemperature);                 
                    } else {
                        throw new HTTP_Exception_403;
                    }
                    break;
                case 'sunlight':
                    if( isset($_POST['sunlight']) ) {
                        $mQuarterHour = new Model_QuarterHour();
                        //$id_day = $mDay->getCurrentDayId();
                        
                        $sunlight = filter_input(INPUT_POST, 'sunlight', FILTER_SANITIZE_SPECIAL_CHARS);
                        $mQuarterHour->insertQuarterHour($datetime, $sunlight);
                    } else {
                        throw new HTTP_Exception_403;
                    }
                    break;
                case 'pumpStatus':
                    if( isset($_POST['pumpStatus']) ) {
                        $mLive = new Model_Live();
                        
                        $pumpStatus = filter_input(INPUT_POST, 'pumpStatus', FILTER_SANITIZE_SPECIAL_CHARS);
                        $mLive->updatePumpStatus($pumpStatus);
                    } else {
                        throw new HTTP_Exception_403;
                    }
                    break;
                case 'sunrise':
                    $mDay = new Model_Day();
                    $mDay->updateSunrise($datetime);
                    
                    break;
                case 'sunset':
                    break;
            }
            $this->stillAlive();
        } else {
            throw new HTTP_Exception_403;
        }
    }
    
    private function stillAlive() {
        $query = DB::query(Database::UPDATE, "UPDATE live SET last_communication = NOW() WHERE id_live = 1");
        $query->execute();
    }
}