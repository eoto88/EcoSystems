<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template {

    public $template = 'template'; // Default template

    public function before() {
        parent::before();
        $this->template->title = "Garduinoponics";
    }

    public function after() {
        if( $this->auto_render ) {
            $styles = array(
                "assets/css/normalize.css" => "screen",
                "assets/css/main.css" => "screen"
            );
            $scripts  = array(
                //"http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js",
                "assets/js/plugins.js",
                "assets/js/main.js"
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
        $data = array();
        //$this->template->title = "Garduinoponics";
        $view = View::factory( "index" )->render();
        $this->template->content = $view;
	}

} // End Welcome
