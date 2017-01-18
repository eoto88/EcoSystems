<?php defined('SYSPATH') or die('No direct script access.');

class Controller_AuthenticatedPage extends Controller_Template {

    public $template = 'template'; // Default template
    public $jsTranslations = array();
    public $user;
    public $currentInstanceId = null;
    public $instances;

    public function before() {
        parent::before();
        //I18n::lang('fr'); // TODO Translate the application in french
        
        if ( ! Auth::instance()->logged_in()) {
            HTTP::redirect(URL::base(TRUE, TRUE) . 'login');
        }

        if( $this->request->param('id') ) {
            $this->currentInstanceId = $this->request->param('id');
        }

//        $this->getAndSetCurrentInstanceId();
        
        $this->user = Auth::instance()->get_user();

        $mInstance = new Model_Instance();
        $this->instances = $mInstance->getInstances($this->user['id_user']);
    }

//    private function getAndSetCurrentInstanceId() {
//        // TODO Not necessary anymore ...
//        if( $this->request->param('id') ) {
//            $id = $this->request->param('id');
//            if( ! is_numeric($id) && $id != 'new' )
//                throw new HTTP_Exception_404;
//            $this->currentInstanceId = $this->request->param('id');
//            Cookie::set('current_instance_id', $this->currentInstanceId);
//        } else if( Cookie::get('current_instance_id') ) {
//            $this->currentInstanceId = Cookie::get('current_instance_id');
//        }
//    }

    public function after() {
        $this->template->user = $this->user;

        $this->template->current_route_name = Route::name($this->request->route());
        $this->template->current_instance_id = $this->currentInstanceId;

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
            "bower_components/selectize/dist/css/selectize.default.css" => "screen",
            "bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" => "screen",
            "bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" => "screen",
            "bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" => "screen",
            "bower_components/bootstrap/dist/css/bootstrap.min.css" => "screen",
            "bower_components/weather-icons/css/weather-icons.min.css" => "screen",
            "bower_components/components-font-awesome/css/font-awesome.min.css" => "screen",
            "bower_components/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" => "screen",
            "assets/css/normalize.css" => "screen"
        );
    }

    private function getScripts() {
        return array(
            "assets/js/widgets/form.js",
            "assets/js/widgets/history.js",
            "assets/js/widgets/waterTests.js",
            "assets/js/widgets/live.js",
            "assets/js/widgets/logs.js",
            "assets/js/widgets/todos.js",
            "assets/js/widgets/InstanceParams.js",
            "assets/js/widgets/instances.js",
            "assets/js/cmp/Button.js",
            "assets/js/cmp/field/Iconpicker.js",
            "assets/js/cmp/field/Spinner.js",
            "assets/js/cmp/field/Hidden.js",
            "assets/js/cmp/field/Combo.js",
            "assets/js/cmp/field/Text.js",
            "assets/js/cmp/Field.js",
            "assets/js/cmp/Table.js",
            "assets/js/cmp/Form.js",
            "assets/js/Cmp.js",
            "assets/js/widget.js",
            "assets/js/ES.js",
            "assets/js/main.js",
            "bower_components/selectize/dist/js/standalone/selectize.min.js",
            "bower_components/justgage-toorshia/justgage.js",
            "bower_components/justgage-toorshia/raphael-2.1.4.min.js",
            "bower_components/handlebars/handlebars.min.js",
            "bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js",
            "bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js",
            "bower_components/moment/min/moment.min.js",
            "bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js",
            "bower_components/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js",
            "bower_components/bootstrap/dist/js/bootstrap.min.js",
            "bower_components/jquery.populate/jquery.populate.js",
            "bower_components/highstock/js/modules/exporting.src.js",
            "bower_components/highstock/js/highstock.src.js",
            "bower_components/salvattore/dist/salvattore.min.js",
            "bower_components/jquery-migrate/jquery-migrate.min.js",
            "bower_components/jquery/dist/jquery.min.js"
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
