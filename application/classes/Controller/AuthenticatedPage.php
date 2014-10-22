<?php defined('SYSPATH') or die('No direct script access.');

class Controller_AuthenticatedPage extends Controller_Template {

    public $template = 'template'; // Default template
    public $jsTranslations = array();
    public $user;
    public $instances;

    public function before() {
        parent::before();
        I18n::lang('fr');
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }
        
        $this->user = Auth::instance()->get_user();

        $mInstance = new Model_Instance();
        $this->instances = $mInstance->getInstances();

        $mToDo = new Model_Todo();
        View::set_global('toDos', $mToDo->checkToDos());
    }

    public function after() {
        $idInstance = 1;

        $hStatus = new Helper_Status();

        $mDay = new Model_Day();
        $day = $mDay->getCurrentDay($idInstance);
        if( ! $day ) {
            $date = new DateTime();
            $date->sub(new DateInterval('P1D'));

            $day = $mDay->getDayByDate($idInstance, $date->format('Y-m-d'));
        }
        $this->template->sun_status = $hStatus->getSunStatus($day);

        $mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData($idInstance);
        $this->template->communication_status = $hStatus->getCommunicationStatus($liveData);
        $this->template->pump_status = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $this->template->light_status = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $this->template->fan_status = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $this->template->heater_status = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);

        $this->template->instances = $this->instances;

        $this->template->translations = array_merge( $this->jsTranslations, $this->getGlobalJsTranslations() );

        if( $this->auto_render ) {
            $this->template->styles = array_reverse(
                $this->getStyles() // array_merge( $this->template->styles, $styles )
            );
            $this->template->scripts = array_reverse(
                $this->getScripts() // array_merge( $this->template->scripts, $scripts )
            );
        }
        parent::after();
    }

    private function getStyles() {
        return array(
            "assets/css/main.css" => "screen",
            "assets/css/bootstrap.min.css" => "screen",
            "assets/css/normalize.css" => "screen",
            "assets/css/weather-icons.min.css" => "screen"
        );
    }

    private function getScripts() {
        return array(
            "assets/js/plugins.js",
            "assets/js/history.js",
            "assets/js/dashboard.js",
            "assets/js/main.js",
            "http://code.highcharts.com/modules/exporting.js",
            "http://code.highcharts.com/highcharts.js"
        );
    }

    private function getGlobalJsTranslations() {
        return array(
            'lang'              => I18n::lang(),
            'lastCommunication' => 'Last communication',
            'noTaskTodoList'    => 'No task in the to do list'
        );
    }
}
