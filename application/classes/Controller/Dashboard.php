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

        $hWidgetInstances = new Helper_WidgetInstances();
        $vInstances = $hWidgetInstances->getViewInstances($this->instances);

        $hWidgetTodos = new Helper_WidgetTodos();
        $vTodos = $hWidgetTodos->getView();

        $mLog = new Model_Log();

        $dashboardData = array(
            'widget_instances'  => $vInstances,
            'widget_todos'      => $vTodos,
            'logs'              => $mLog->getLastLogs()
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
    
    private function getLiveData() {
        $mData = new Model_Data();
        $data = $mData->getData( $this->currentInstanceId );

        $mInstance = new Model_Instance();
        $instance = $mInstance->getInstance( $this->currentInstanceId, $this->user['id_user'] );

        $humidity = array();
        $roomTemperature = array();
        $tankTemperature = array();
        foreach($data as $hour) {
            $datetime = strtotime($hour['datetime']) * 1000;
            $humidity[] = array(
                'x' => $datetime,
                'y' => floatval($hour['humidity'])
            );
            $roomTemperature[] = array(
                'x' => $datetime,
                'y' => floatval($hour['room_temperature'])
            );
            $tankTemperature[] = array(
                'x' => $datetime,
                'y' => floatval($hour['tank_temperature'])
            );
        }

        $hWidgetInstances = new Helper_WidgetInstances();
        $vInstances = $hWidgetInstances->getViewSingleInstance($this->currentInstanceId, $this->user['id_user']);
        
        return array(
            'widget_instances'      => $vInstances,
            'instance'              => $instance,
            'humidityData'          => $humidity,
            'roomTemperatureData'   => $roomTemperature,
            'tankTemperatureData'   => $tankTemperature,
        );
    }
    
    public function action_history() {
        $this->template->title = __('History');
        $this->template->icon = 'fa-history';

        $this->template->translations = array();
//        $mDay = new Model_Day();
//        $lastDaysData = $mDay->getLastDays( $this->currentInstanceId );

//        $historyData = $this->prepareHistoryData($lastDaysData);

//        $view = View::factory( "history" )->set($historyData);
//        $this->template->content = $view->render();
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
