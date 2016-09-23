<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_Status {

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

    public function getWaterTestStatus($data) {
        $title = "Date: ". $data['date'];
        $waterIcon = 'fa fa-flask';
        $ph = (isset($data['ph']) ? $data['ph'] : '--');
        $phClasses = 'ph';
        $ammonia = (isset($data['ammonia']) ? $data['ammonia'] : '--') .' ppm';
        $ammoniaClasses = 'ammonia';
        $nitrite = (isset($data['nitrite']) ? $data['nitrite'] : '--') .' ppm';
        $nitriteClasses = 'nitrite';
        $nitrate = (isset($data['nitrate']) ? $data['nitrate'] : '--') .' ppm';
        $nitrateClasses = 'nitrate';

        $floatPh = floatval($ph);

        switch($floatPh) {
            case $floatPh > 10.0:
                $phClasses .=' very-alkaline';
                break;
            case $floatPh > 7.5:
                $phClasses .=' alkaline';
                break;
            case $floatPh > 6.5:
                $phClasses .=' neutral';
                break;
            case $floatPh > 4.0:
                $phClasses .=' acidic';
                break;
            default:
                $phClasses .=' very-acidic';
        }

        $floatAmmonia = floatval($ammonia);

        switch($floatAmmonia) {
            case $floatAmmonia > 4.0:
                $ammoniaClasses .=' high-danger';
                break;
            case $floatAmmonia > 2.0:
                $ammoniaClasses .=' medium-danger';
                break;
            default:
                $ammoniaClasses .=' low-danger';
        }

        $floatNitrite = floatval($nitrite);

        switch($floatAmmonia) {
            case $floatNitrite > 0.8:
                $nitriteClasses .=' high-danger';
                break;
            case $floatNitrite > 0.3:
                $nitriteClasses .=' medium-danger';
                break;
            default:
                $nitriteClasses .=' low-danger';
        }

        $floatNitrate = floatval($nitrate);

        switch($floatAmmonia) {
            case $floatNitrate > 50.0:
                $nitrateClasses .=' high-danger';
                break;
            case $floatNitrate > 20.0:
                $nitrateClasses .=' medium-danger';
                break;
            default:
                $nitrateClasses .=' low-danger';
        }

        $ph = $this->formatLiveStatus($title, __('pH'), $phClasses, $waterIcon, $ph);
        $ammonia = $this->formatLiveStatus($title, __('Ammonia (NH<sub>3</sub>)'), $ammoniaClasses, $waterIcon, $ammonia);
        $nitrite = $this->formatLiveStatus($title, __('Nitrite (NO<sub>2</sub>)'), $nitriteClasses, $waterIcon, $nitrite);
        $nitrate = $this->formatLiveStatus($title, __('Nitrate (NO<sub>3</sub>)'), $nitrateClasses, $waterIcon, $nitrate);

        return $ph . $ammonia . $nitrite . $nitrate;
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