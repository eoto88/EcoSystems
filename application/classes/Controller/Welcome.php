<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }
        
        $user = Auth::instance()->get_user();
        
        //var_dump($user);
        
        $this->template->title = "Garduinoponics";

        $hStatus = new Helper_Status();

        $mDay = new Model_Day();
        $day = $mDay->getCurrentDay();
        if( ! $day ) {
            $date = new DateTime();
            $date->sub(new DateInterval('P1D'));

            $day = $mDay->getDayByDate($date->format('Y-m-d'));
        }
        $this->template->sun_status = $hStatus->getSunStatus($day);

        $mLive = new Model_Live();
        $liveData = $mLive->getLiveData();
        $this->template->communication_status = $hStatus->getCommunicationStatus($liveData);
        $this->template->pump_status = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $this->template->light_status = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $this->template->fan_status = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $this->template->heater_status = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);
    }

    public function after() {
        if( $this->auto_render ) {
            $styles = array(
                "assets/css/main.css" => "screen",
                "assets/css/normalize.css" => "screen"
            );
            $scripts  = array(
                "assets/js/plugins.js",
                "assets/js/main.js",
                "http://code.highcharts.com/modules/exporting.js",
                "http://code.highcharts.com/highcharts.js"
            );

            $this->template->styles = array_reverse(
                $styles // array_merge( $this->template->styles, $styles )
            );
            $this->template->scripts = array_reverse(
                $scripts // array_merge( $this->template->scripts, $scripts )
            );
        }
        parent::after();
    }

    public function action_index() {
        $mHour = new Model_Hour();
        $temparatureData = $mHour->getTemperatureData();
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($temparatureData as $temp) {
            $datetime = strtotime($temp['datetime']. ' GMT') * 1000; //
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
        
        /*$mDay = new Model_Day();
        var_dump($mDay->verifyCurrentDay());*/

        $view = View::factory( "index" )->set(array(
            'roomTemperatureData' => $roomTemperature,
            'tankTemperatureData' => $tankTemperature,
            'sunlightData' => $sunlight
        ));
        $this->template->content = $view->render();
    }

} // End Welcome
