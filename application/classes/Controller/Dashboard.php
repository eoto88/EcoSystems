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
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($temparatureData as $temp) {
            $datetime = strtotime($temp['datetime']. ' GMT') * 1000;
            $roomTemperature[] = array( $datetime, floatval($temp['room_temperature']) );
            $tankTemperature[] = array( $datetime, floatval($temp['tank_temperature']) );
        }
        $mQuarterHour = new Model_QuarterHour();
        $sunlightData = $mQuarterHour->getSunlightData();
        $sunlight = array();
        foreach($sunlightData as $sun) {
            $datetime = strtotime($sun['datetime']. ' GMT') * 1000; //
            $sunlight[] = array( $datetime, intval($sun['sunlight']) * 100 / 1024 );
        }

        $view = View::factory( "index" )->set(array(
            'roomTemperatureData' => $roomTemperature,
            'tankTemperatureData' => $tankTemperature,
            'sunlightData' => $sunlight
        ));
        $this->template->content = $view->render();
    }
    
    public function action_history() {
        $mDay = new Model_Day();
        $lastDays = $mDay->getLastDays();
        $roomTemperatureHistory = array();
        $tankTemperatureHistory = array();
        foreach($lastDays as $day) {
            $datetime = strtotime($day['date']. ' GMT') * 1000;
            $roomTemperatureHistory[] = array($datetime, floatval($day['room_tmp_avg']));
            $tankTemperatureHistory[] = array($datetime, floatval($day['tank_tmp_avg']));
        }
        
        $view = View::factory( "history" )->set(array(
            'roomTemperatureHistory' => $roomTemperatureHistory,
            'tankTemperatureHistory' => $tankTemperatureHistory
        ));
        $this->template->content = $view->render();
    }

} // End Welcome
