<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Task extends Controller_AuthenticatedPage {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }

        $idInstance = 1; // FIXME Why?

        $this->template->title = "Garduinoponics";

        $hStatus = new Helper_Status();

        $mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData($idInstance);
        $this->template->communication_status = $hStatus->getCommunicationStatus($liveData);
        $this->template->pump_status = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $this->template->light_status = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $this->template->fan_status = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $this->template->heater_status = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);
        
        $mToDo = new Model_ToDo();
        $this->template->toDos = $mToDo->checkToDos();
    }

    public function action_index() {
        $idInstance = 1;
        $mHour = new Model_Hour();
        $temparatureData = $mHour->getTemperatureData($idInstance);
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($temparatureData as $temp) {
            $datetime = strtotime($temp['datetime']. ' GMT') * 1000; //
            $roomTemperature[] = array( $datetime, floatval($temp['room_temperature']) );
            $tankTemperature[] = array( $datetime, floatval($temp['tank_temperature']) );
        }

        $view = View::factory( "index" )->set(array(
            'roomTemperatureData' => $roomTemperature,
            'tankTemperatureData' => $tankTemperature,
        ));
        $this->template->content = $view->render();
    }

} // End Welcome
