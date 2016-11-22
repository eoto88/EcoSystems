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
        $vInstances = $hWidgetInstances->getViewInstances();

        $hWidgetTodos = new Helper_WidgetTodos();
        $vTodos = $hWidgetTodos->getView($this->user['id_user']);

        $hWidgetLogs = new Helper_WidgetLogs();
        $vLogs = $hWidgetLogs->getViewLogs();

        $dashboardData = array(
            'widget_instances'  => $vInstances,
            'widget_todos'      => $vTodos,
            'widget_logs'       => $vLogs
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
        foreach($data as $row) {
            $datetime = strtotime($row['datetime']) * 1000;
            $humidity[] = array(
                'x' => $datetime,
                'y' => floatval($row['humidity'])
            );
            $roomTemperature[] = array(
                'x' => $datetime,
                'y' => floatval($row['room_temperature'])
            );
            $tankTemperature[] = array(
                'x' => $datetime,
                'y' => floatval($row['tank_temperature'])
            );
        }

        $hWidgetInstances = new Helper_WidgetInstances();
        $vInstances = $hWidgetInstances->getViewSingleInstance($this->currentInstanceId);
        
        return array(
            'widget_instances'      => $vInstances,
//            'instance'              => $instance,
            'humidityData'          => $humidity,
            'roomTemperatureData'   => $roomTemperature,
            'tankTemperatureData'   => $tankTemperature,
        );
    }
    
    public function action_history() {
        $this->template->title = __('History');
        $this->template->icon = 'fa-history';

        $this->template->translations = array();
        $mData = new Model_Data();
        $data =$mData->getDataAverageByDay($this->currentInstanceId);

        $historyData = $this->prepareHistoryData($data);

        $view = View::factory( "history" )->set($historyData);
        $this->template->content = $view->render();
    }
    
    private function prepareHistoryData($temperatureData) {
        $roomTemperatureHistory = array();
        $tankTemperatureHistory = array();
        $humidityHistory = array();
        foreach($temperatureData as $row) {
            $datetime = strtotime($row['date']) * 1000;
            $humidityHistory[] = array(
                'x' => $datetime,
                'y' => floatval($row['avg_room_temp'])
            );
            $roomTemperatureHistory[] = array(
                'x' => $datetime,
                'y' => floatval($row['avg_tank_temp'])
            );
            $tankTemperatureHistory[] = array(
                'x' => $datetime,
                'y' => floatval($row['avg_humidity'])
            );
        }
        
        return array(
            'roomTemperatureHistory' => $roomTemperatureHistory,
            'tankTemperatureHistory' => $tankTemperatureHistory,
            'humidityHistory' => $humidityHistory
        );
    }

    public function action_todos() {
        $this->template->title = __('ToDo\'s');
        $this->template->icon = 'fa-check';

        $mInstanceType = new Model_InstanceType();
        $mTodo = new Model_Todo();

        $instancesData = array(
            'toDos' => array(), //$mTodo->getTodos(),
            'instance_types' => $mInstanceType->getInstanceTypes()
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
