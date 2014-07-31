<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        $this->template->title = "Garduinoponics";

        $mDay = new Model_Day();
        $this->template->day = $mDay->getCurrentDay();
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
        $view = View::factory( "index" )->render();
        $this->template->content = $view;
    }

} // End Welcome
