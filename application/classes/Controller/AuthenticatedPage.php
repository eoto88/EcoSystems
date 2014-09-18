<?php defined('SYSPATH') or die('No direct script access.');

class Controller_AuthenticatedPage extends Controller_Template {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }
        
        $user = Auth::instance()->get_user();
                
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
        
        $mToDo = new Model_ToDo();
        $this->template->toDos = $mToDo->checkToDos();
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

}
