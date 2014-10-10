<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_AuthenticatedPage {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        $this->template->title = "Dashboard | Garduinoponics";
    }

    public function action_index() {
        $mHour = new Model_Hour();
        $temparatureData = $mHour->getTemperatureData();

        $mQuarterHour = new Model_QuarterHour();
        $sunlightData = $mQuarterHour->getSunlightData();
        
        $dashboardData = $this->prepareDashbordData($temparatureData, $sunlightData);

        $view = View::factory( "dashboard" )->set($dashboardData);
        $this->template->content = $view->render();
    }
    
    private function prepareDashbordData($temparatureData, $sunlightData) {
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($temparatureData as $temp) {
            $datetime = strtotime($temp['datetime']. ' GMT') * 1000;
            $roomTemperature[] = array( $datetime, floatval($temp['room_temperature']) );
            $tankTemperature[] = array( $datetime, floatval($temp['tank_temperature']) );
        }

        $sunlight = array();
        foreach($sunlightData as $sun) {
            $datetime = strtotime($sun['datetime']. ' GMT') * 1000; //
            $sunlight[] = array( $datetime, intval($sun['sunlight']) * 100 / 1024 );
        }
        
        return array(
            'roomTemperatureData' => $roomTemperature,
            'tankTemperatureData' => $tankTemperature,
            'sunlightData' => $sunlight
        );
    }
    
    public function action_history() {
        $mDay = new Model_Day();
        $lastDaysData = $mDay->getLastDays();

        $historyData = $this->prepareHistoryData($lastDaysData);
        
        $view = View::factory( "history" )->set($historyData);
        $this->template->content = $view->render();
    }
    
    private function prepareHistoryData($temparatureData) {
        $roomTemperatureHistory = array();
        $tankTemperatureHistory = array();
        $sunlightHistory = array();
        foreach($temparatureData as $day) {
            $datetime = strtotime($day['date']. ' GMT') * 1000;
            $roomTemperatureHistory[] = array($datetime, floatval($day['room_tmp_avg']));
            $tankTemperatureHistory[] = array($datetime, floatval($day['tank_tmp_avg']));
            
            $sunlightHistory[] = array($datetime, intval($day['light_hour']));
        }
        
        return array(
            'roomTemperatureHistory' => $roomTemperatureHistory,
            'tankTemperatureHistory' => $tankTemperatureHistory,
            'sunlightHistory' => $sunlightHistory
        );
    }

} // End Welcome
