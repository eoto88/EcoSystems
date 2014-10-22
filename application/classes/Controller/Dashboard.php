<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_AuthenticatedPage {

    //public $template = 'template'; // Default template

    public function before() {
        parent::before();
        $this->jsTranslations = array(
            'temperature'       => __('Temperature'),
            'roomTemperature'   => __('Room temperature'),
            'tankTemperature'   => __('Tank temperature'),
            'hot'               => __('Hot'),
            'cold'              => __('Cold'),
            'valueInCelsius'    => __('Value in Celsius (°C)'),
            'sunlight'          => __('Sunlight'),
            'sunlightPercent'   => __('Sunlight (%)'),
            'day'               => __('Day'),
            'night'             => __('Night'),
            'valueInPercent'    => __('Value in percent (%)')
        );
    }

    public function action_index() {
        $this->template->title = __('Dashboard') ." | Garduinoponics";


        $mHour = new Model_Hour();
        //$mQuarterHour = new Model_QuarterHour();
        $mDay = new Model_Day();
        $hStatus = new Helper_Status();
        $mInstance = new Model_Instance();

        $instances = array();
        foreach($this->instances as $instance) {
            $liveData = $mInstance->getLiveData($instance['id_instance']);
            $instance['communication_status'] = $hStatus->getCommunicationStatus($liveData);
            $instance['pump_status'] = $hStatus->getStatus('pump', 'Pump', $instance['pump_on']);
            $instance['light_status'] = $hStatus->getStatus('light', 'Light', $instance['light_on']);
            $instance['fan_status'] = $hStatus->getStatus('fan', 'Fan', $instance['fan_on']);
            $instance['heater_status'] = $hStatus->getStatus('heater', 'Heater', $instance['heater_on']);
            $currentDay = $mDay->getCurrentDay($instance['id_instance']);
            $instance['sun_status'] = $hStatus->getSunStatus($currentDay);
            $temperatureData = $mHour->getLastTemperatureData($instance['id_instance']);
            $instance['temperature_status'] = $hStatus->getTemperatureStatus($temperatureData);

            $instances[] = $instance;
        }

        $dashboardData = array(
            'instances' => $instances
        );

        $view = View::factory( "dashboard" )->set($dashboardData);
        $this->template->content = $view->render();
    }

    public function action_live() {
        $idInstance = $this->request->param('id');
        $this->template->title = __('Live') ." | Garduinoponics";
        $mHour = new Model_Hour();
        $temperatureData = $mHour->getTemperatureData($idInstance);

        $mQuarterHour = new Model_QuarterHour();
        $sunlightData = $mQuarterHour->getSunlightData($idInstance);

        $dashboardData = $this->prepareDashbordData($temperatureData, $sunlightData);

        $view = View::factory( "live" )->set($dashboardData);
        $this->template->content = $view->render();
    }
    
    private function prepareDashbordData($temparatureData, $sunlightData) {
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($temparatureData as $temp) {
            $datetime = strtotime($temp['datetime']) * 1000;
            $roomTemperature[] = array( $datetime, floatval($temp['room_temperature']) );
            $tankTemperature[] = array( $datetime, floatval($temp['tank_temperature']) );
        }

        $sunlight = array();
        foreach($sunlightData as $sun) {
            $datetime = strtotime($sun['datetime']) * 1000; //
            $sunlight[] = array( $datetime, intval($sun['sunlight']) * 100 / 1024 );
        }
        
        return array(
            'roomTemperatureData' => $roomTemperature,
            'tankTemperatureData' => $tankTemperature,
            'sunlightData' => $sunlight
        );
    }
    
    public function action_history() {
        $this->template->title = __('History') ." | Garduinoponics";
        $this->template->translations = array();
        $mDay = new Model_Day();
        $idInstance = 1;
        $lastDaysData = $mDay->getLastDays($idInstance);

        $historyData = $this->prepareHistoryData($lastDaysData);
        
        $view = View::factory( "history" )->set($historyData);
        $this->template->content = $view->render();
    }
    
    private function prepareHistoryData($temperatureData) {
        $roomTemperatureHistory = array();
        $tankTemperatureHistory = array();
        $sunlightHistory = array();
        foreach($temperatureData as $day) {
            $datetime = strtotime($day['date']) * 1000;
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
