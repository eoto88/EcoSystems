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

    public function getCommunicationStatus($liveData) {
        $title = "";
        $icon = "fa-exclamation-triangle error";
        if(isset($liveData)) {
            $title = "Last communication: ". $liveData['last_communication'];
            if($liveData['still_alive']) {
                $icon = "fa-check success";
            }
        }
        return '<span id="still_alive" title="'. $title .'"><i class="fa '. $icon .'"></i></span>';
    }
    
    public function getPumpStatus($pumpStatus) {
        $title = $pumpStatus ? "Pump is on" : "Pump is off";
        $class = $pumpStatus ? "pump-on" : "pump-off";
        return '<span id="pump_status" title="'. $title .'"><i class="fa fa-tint '. $class .'"></i></span>';
    }
}