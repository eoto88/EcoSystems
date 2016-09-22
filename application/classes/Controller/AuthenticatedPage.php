<?php defined('SYSPATH') or die('No direct script access.');

class Controller_AuthenticatedPage extends Controller_Template {

    public $template = 'template'; // Default template
    public $jsTranslations = array();
    public $user;
    public $currentInstanceId;
    public $instances;

    public function before() {
        parent::before();
        //I18n::lang('fr'); // TODO Translate the application in french
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }

        $this->getAndSetCurrentInstanceId();
        
        $this->user = Auth::instance()->get_user();

        $mInstance = new Model_Instance();
        $this->instances = $mInstance->getInstances($this->user['id_user']);
    }

    private function f_rand($min=0,$max=1,$mul=1000000){
        if ($min>$max) return false;
        return mt_rand($min*$mul,$max*$mul)/$mul;
    }

    private function getAndSetCurrentInstanceId() {
        if( $this->request->param('id') ) {
            $id = $this->request->param('id');
            if( ! is_numeric($id) && $id != 'new' )
                throw new HTTP_Exception_404;
            $this->currentInstanceId = $this->request->param('id');
            Cookie::set('current_instance_id', $this->currentInstanceId);
        } else if( Cookie::get('current_instance_id') ) {
            $this->currentInstanceId = Cookie::get('current_instance_id');
        } else {
            $config = Kohana::$config->load('app');
            $this->currentInstanceId = $config['default_instance'];
            Cookie::set('current_instance_id', $this->currentInstanceId);
        }
    }

    public function after() {
        $this->template->user = $this->user;

        $this->template->current_route_name = Route::name($this->request->route());
        $this->template->current_instance_id = $this->currentInstanceId;

        //$mDay = new Model_Day();
        //$day = $mDay->getCurrentDay( $this->currentInstanceId );
        /*if( ! $day ) {
            $date = new DateTime();
            $date->sub(new DateInterval('P1D'));

            $day = $mDay->getDayByDate( $this->currentInstanceId, $date->format('Y-m-d') );
        }*/
        //$hStatus = new Helper_Status();

        /*$mInstance = new Model_Instance();
        $liveData = $mInstance->getLiveData( $this->currentInstanceId );
        $this->template->communication_status = $hStatus->getCommunicationStatus($liveData);
        $this->template->pump_status = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $this->template->light_status = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $this->template->fan_status = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $this->template->heater_status = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);*/

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
            "assets/css/vendor/bootstrap-dialog.css" => "screen",
            "assets/css/vendor/bootstrap-datetimepicker.css",
            "assets/css/vendor/jquery.bootstrap-touchspin.css" => "screen",
            "assets/css/vendor/bootstrap.min.css" => "screen",
            "assets/css/normalize.css" => "screen",
            "assets/css/weather-icons.min.css" => "screen"
        );
    }

    private function getScripts() {
        return array(
            //"assets/js/plugins.js",
            "assets/js/widgets/form.js",
            "assets/js/widgets/history.js",
            "assets/js/widgets/waterTests.js",
            "assets/js/widgets/live.js",
            "assets/js/widgets/todos.js",
            "assets/js/widgets/instances.js",
            "assets/js/widget.js",
            "assets/js/main.js",
            "assets/js/vendor/handlebars-v2.0.0.js",
            "assets/js/vendor/bootstrap-dialog.js",
            "assets/js/vendor/bootstrap-datetimepicker.js",
            "assets/js/vendor/moment.js",
            "assets/js/vendor/jquery.bootstrap-touchspin.js",
            "assets/js/vendor/bootstrap.min.js",
            "assets/js/vendor/jquery.populate.js",
            "http://code.highcharts.com/modules/exporting.js",
            "http://code.highcharts.com/stock/highstock.js", //"http://code.highcharts.com/highcharts.js"
        );
    }

    private function getGlobalJsTranslations() {
        return array(
            'lang'              => I18n::lang(),
            'lastCommunication' => 'Last communication',
            //'noTaskTodoList'    => 'No task in the to do list'
        );
    }
}
