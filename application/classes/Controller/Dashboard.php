<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_AuthenticatedPage {

    public function before() {
        parent::before();
        $this->jsTranslations = array(
            // Temperature
            'temperature'       => __('Temperature'),
            'roomTemperature'   => __('Room temperature'),
            'tankTemperature'   => __('Tank temperature'),
            'hot'               => __('Hot'),
            'cold'              => __('Cold'),
            'valueInCelsius'    => __('Value in Celsius (°C)'),
            // Humidity
            'humidity'          => __('Humidity'),
            'low'               => __('Low'),
            'high'              => __('High'),
            'humidityPercent'   => __('Humidity (%)'),
            // Sunlight
            'sunlight'          => __('Sunlight'),
            'sunlightPercent'   => __('Sunlight (%)'),
            'day'               => __('Day'),
            'night'             => __('Night'),
            'valueInPercent'    => __('Value in percent (%)')
        );
    }

    public function action_index() {
        $this->template->title = __('Dashboard');
        $this->template->icon = 'fa-tachometer';

        $mHour = new Model_Hour();
        $mToDo = new Model_Todo();
        //$mQuarterHour = new Model_QuarterHour();
        $mDay = new Model_Day();
        $hStatus = new Helper_Status();
        $mInstance = new Model_Instance();

        $instances = array();
        foreach($this->instances as $instance) {
            $liveData = $mInstance->getLiveData($instance['id']);
            $instance['communication_status'] = $hStatus->getCommunicationStatus($liveData);
            $instance['pump_status'] = $hStatus->getStatus('pump', 'Pump', $instance['pump_on']);
            $instance['light_status'] = $hStatus->getStatus('light', 'Light', $instance['light_on']);
            $instance['fan_status'] = $hStatus->getStatus('fan', 'Fan', $instance['fan_on']);
            $instance['heater_status'] = $hStatus->getStatus('heater', 'Heater', $instance['heater_on']);
            $currentDay = $mDay->getCurrentDay($instance['id']);
            $temperatureData = $mHour->getLastTemperatureData($instance['id']);
            $instance['temperature_status'] = $hStatus->getTemperatureStatus($temperatureData);

            $instances[] = $instance;
        }

        $hWidgetTodos = new Helper_WidgetTodos();
        $vTodos = $hWidgetTodos->getView();

        $mLog = new Model_Log();

        $dashboardData = array(
            'instances'     => $instances,
            'logs'          => $mLog->getLastLogs(),
            'widget_todos'  => $vTodos
        );

        $view = View::factory( "dashboard" )->set($dashboardData);
        $this->template->content = $view->render();
    }

    public function action_live() {
        $this->template->title = __('Live');
        $this->template->icon = 'fa-bar-chart-o';

        $view = View::factory( "live" )->set( $this->getLiveData() );
        $this->template->content = $view->render();
    }

    private function getLiveStatus($idInstance) {
        $mDay = new Model_Day();
        $day = $mDay->getCurrentDay( $idInstance );

        $hStatus = new Helper_Status();

        $mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData( $idInstance );

        $mHour = new Model_Hour();
        $temperatureData = $mHour->getLastTemperatureData( $idInstance );

        return array(
            'communication_status' => $hStatus->getCommunicationStatus($liveData),
            'pump_status' => $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']),
            'light_status' => $hStatus->getStatus('light', 'Light', $liveData['light_on']),
            'fan_status' => $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']),
            'heater_status' => $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']),
            'temperature_status' => $hStatus->getTemperatureStatus($temperatureData)
        );
    }
    
    private function getLiveData() {
        $mHour = new Model_Hour();
        $hourData = $mHour->getHourData( $this->currentInstanceId );

        $mInstance = new Model_Instance();
        $instance = $mInstance->getInstance( $this->currentInstanceId, $this->user['id_user'] );

        $humidity = array();
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($hourData as $hour) {
            $datetime = strtotime($hour['datetime']) * 1000;
            $humidity[] = array( $datetime, floatval($hour['humidity']) );
            $roomTemperature[] = array( $datetime, floatval($hour['room_temperature']) );
            $tankTemperature[] = array( $datetime, floatval($hour['tank_temperature']) );
        }
        
        return array(
            'instance'              => $instance,
            'humidityData'          => $humidity,
            'roomTemperatureData'   => $roomTemperature,
            'tankTemperatureData'   => $tankTemperature,
            'liveStatus'            => $this->getLiveStatus( $this->currentInstanceId )
        );
    }
    
    public function action_history() {
        $this->template->title = __('History');
        $this->template->icon = 'fa-history';

        $this->template->translations = array();
        $mDay = new Model_Day();
        $lastDaysData = $mDay->getLastDays( $this->currentInstanceId );

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

    public function action_todos() {
        $this->template->title = __('ToDo\'s');
        $this->template->icon = 'fa-check';

        $config = Kohana::$config->load('app');

        $mTodo = new Model_Todo();

        $instancesData = array(
            'toDos' => array(), //$mTodo->getTodos(),
            'instance_types' => $config['instance_types']
        );

        $view = View::factory( "todos" )->set( $instancesData );;
        $this->template->content = $view->render();
    }

    public function action_logs() {
        $this->template->title = __('Logs');
        $this->template->icon = 'fa-file-text-o';

        $view = View::factory( "logs" )->set( array('instances' => $this->instances) );;
        $this->template->content = $view->render();
    }

} // End Welcome
