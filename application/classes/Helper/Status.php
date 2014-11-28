<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_Status {

    public function getTemperatureStatus($data) {
        $data['datetime'];
        $data['room_temperature'];

        $title = "Time: ". $data['datetime'];
        $icon = 'wi wi-thermometer';
        $roomTemperature = (isset($data['room_temperature']) ? $data['room_temperature'] : '--') .' °C';
        $tankTemperature = (isset($data['tank_temperature']) ? $data['tank_temperature'] : '--') .' °C';

        $roomTemperature = $this->formatLiveStatus($title, __('Room temperature'), 'room-temperature', $icon, $roomTemperature);
        $tankTemperature = $this->formatLiveStatus($title, __('Tank temperature'), 'tank-temperature', $icon, $tankTemperature);

        return $roomTemperature . $tankTemperature;
    }

    private function formatLiveStatus($title, $label, $class, $icon, $value) {
        return '<div class="live-status" title="'. $title .'"><span class="live-label">'. $label .'</span><span class="live-value '. $class .'"><i class="'. $icon .'"></i>'. $value .'</span></div>';
    }

    public function getSunStatus($day) {
        $sunrise = "";
        $sunset = "";
        if(isset($day)) {
            $sunriseTime = isset($day['sunrise']) ? date('H:i:s', strtotime($day['sunrise'])) : '--:--:--';
            $icon = "fa fa-sun-o";
            $title = "Sunrise: ". $day['sunrise'];
            $sunrise = $this->formatLiveStatus( $title, __('Sunrise'), 'sunrise', $icon, $sunriseTime );

            $sunsetTime = isset($day['sunset']) ? date('H:i:s', strtotime($day['sunset'])) : '--:--:--';
            $title = "Sunset: ". $day['sunset'];
            $icon = "fa fa-moon-o";
            $sunset = $this->formatLiveStatus( $title, __('Sunset'), 'sunset', $icon, $sunsetTime );
        }
        return $sunrise . $sunset;
    }

    public function getCommunicationStatus($liveData) {
        $title = "";
        $icon = "fa-exclamation-triangle error";
        if(isset($liveData)) {
            $title = __('Last communication') .": ". $liveData['last_communication'];
            if($liveData['still_alive']) {
                $icon = "fa-check success";
            }
        }
        return '<span id="still_alive" title="'. $title .'"><i class="status-icon fa '. $icon .'"></i></span>';
    }
    
    public function getStatus($relayId, $relayName, $status) {
        switch ($relayId) {
            case 'pump':
                $icon = 'fa-tint';
                break;
            case 'light':
                $icon = 'fa-lightbulb-o';
                break;
            case 'fan':
                $icon = 'fa-refresh';
                break;
            case 'heater':
                $icon = 'fa-bolt';
                break;
        }
        $title = $status ? $relayName ." is on" : $relayName ." is off";
        $class = $status ? $relayId ."-on" : "";
        return '<span id="'. $relayId .'_status" title="'. $title .'"><i class="status-icon fa '. $icon .' '. $class .'"></i></span>';
    }
}