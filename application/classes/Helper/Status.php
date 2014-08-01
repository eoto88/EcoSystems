<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_Status {

    public function getSunStatus($day) {
        $title = "";
        $icon = "fa-moon-o";
        if(isset($day)) {
            if(isset($day['sunrise'])) {
                $title = "Sunrise: ". $day['sunrise'];
                $icon = "fa-sun-o";
            }
            if(isset($day['sunset'])) {
                $title .= " | Sunset: ". $day['sunset'];
                $icon = "fa-moon-o";
            }
        }
        return '<span id="sun_status" title="'. $title .'"><i class="fa '. $icon .'"></i></span>';
    }

    public function getCommunicationStatus($last_communication) {
        $title = "";
        $icon = "fa-exclamation-triangle error";
        if(isset($last_communication)) {
            $title = "Last communication: ". $last_communication['last_communication'];
            $icon = "fa-check success";
        }
        return '<span id="still_alive" title="'. $title .'"><i class="fa '. $icon .'"></i></span>';
    }
}