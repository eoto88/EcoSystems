<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_Status {

//    public function getTemperatureStatus($data) {
//        $data['datetime'];
//        $data['room_temperature'];
//
//        $title = "Time: ". $data['datetime'];
//        $tempIcon = 'wi wi-thermometer';
//        $roomTemperature = (isset($data['room_temperature']) ? $data['room_temperature'] : '--') .' °C';
//        $tankTemperature = (isset($data['tank_temperature']) ? $data['tank_temperature'] : '--') .' °C';
//        $humidity = (isset($data['humidity']) ? $data['humidity'] : '--') .' %';
//
//        $humidity = $this->formatLiveStatus($title, __('Humidity'), 'humidity', 'wi wi-sprinkles', $humidity);
//        $roomTemperature = $this->formatLiveStatus($title, __('Room temperature'), 'room-temperature', $tempIcon, $roomTemperature);
//        $tankTemperature = $this->formatLiveStatus($title, __('Tank temperature'), 'tank-temperature', $tempIcon, $tankTemperature);
//
//        return $humidity . $roomTemperature . $tankTemperature;
//    }

    public function getDataStatus($data) {
        $data['datetime'];
        $data['room_temperature'];

        $title = "Time: ". $data['datetime'];
        $tempIcon = 'wi wi-thermometer';
        $roomTemperature = (isset($data['room_temperature']) ? $data['room_temperature'] : '--') .' °C';
        $tankTemperature = (isset($data['tank_temperature']) ? $data['tank_temperature'] : '--') .' °C';
        $humidity = (isset($data['humidity']) ? $data['humidity'] : '--') .' %';

        $humidity = $this->formatLiveStatus($title, __('Humidity'), 'humidity', 'wi wi-sprinkles', $humidity);
        $roomTemperature = $this->formatLiveStatus($title, __('Room temperature'), 'room-temperature', $tempIcon, $roomTemperature);
        $tankTemperature = $this->formatLiveStatus($title, __('Tank temperature'), 'tank-temperature', $tempIcon, $tankTemperature);

        return $humidity . $roomTemperature . $tankTemperature;
    }

    private function formatLiveStatus($title, $label, $class, $icon, $value) {
        return '<div class="live-status" title="'. $title .'"><span class="live-label">'. $label .'</span><span class="live-value '. $class .'"><i class="'. $icon .'"></i>'. $value .'</span></div>';
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
        return '<span class="'. $relayId .'-status" title="'. $title .'"><i class="status-icon fa '. $icon .' '. $class .'"></i></span>';
    }
}