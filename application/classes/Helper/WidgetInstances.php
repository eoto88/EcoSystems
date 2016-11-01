<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstances {

    public function getViewInstances($instances) {
        $mWater = new Model_WaterTest();
        $mData = new Model_Data();
        $hStatus = new Helper_Status();
        $mInstance = new Model_Instance();

        $instancesView = array();
        foreach($instances as $instance) {
            $liveData = $mInstance->getLiveData($instance['id']);
            $instance['communication_status'] = $hStatus->getCommunicationStatus($liveData);
            $instance['pump_status'] = $hStatus->getStatus('pump', 'Pump', $instance['pump_on']);
            $instance['light_status'] = $hStatus->getStatus('light', 'Light', $instance['light_on']);
            $instance['fan_status'] = $hStatus->getStatus('fan', 'Fan', $instance['fan_on']);
            $instance['heater_status'] = $hStatus->getStatus('heater', 'Heater', $instance['heater_on']);
            $lastData = $mData->getLastData($instance['id']);
            $instance['data_status'] = $hStatus->getDataStatus($lastData);
            $instance['water_test_status'] = $hStatus->getWaterTestStatus($mWater->getLastWaterTest( $instance['id'] ));

            $instancesView[] = $instance;
        }

        $vInstances = View::factory("widgets/instances")->set(array(
            'instances' => $instancesView,
            'instanceLi' => $this->getInstanceLi()
        ));

        return $vInstances->render();
    }

    public function getViewSingleInstance($id_instance, $id_user) {
        $hStatus = new Helper_Status();

        $mInstance = new Model_Instance();
        $instance = $mInstance->getInstance($id_instance, $id_user);
        $liveData = $mInstance->getLiveData( $id_instance );

        $mWater = new Model_WaterTest();
        $mData = new Model_Data();
        $lastData = $mData->getLastData( $id_instance );

        $instance['communication_status'] = $hStatus->getCommunicationStatus($liveData);
        $instance['pump_status'] = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $instance['light_status'] = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $instance['fan_status'] = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $instance['heater_status'] = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);
        $instance['data_status'] = $hStatus->getDataStatus($lastData);
        $instance['water_test_status'] = $hStatus->getWaterTestStatus($mWater->getLastWaterTest( $id_instance ));

        $vInstances = View::factory("widgets/instances")->set(array(
            'instances' => array($instance),
            'instanceLi' => $this->getInstanceLi()
        ));

        return $vInstances->render();
    }

    function getInstanceLi() {
        $header = '<div class="instance-header col-sm-12 col-md-12 col-lg-6">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-12">
                    <h3>
                        <a href="'. URL::base(TRUE, TRUE) .'live/{{id}}">
                            {{title}}
                        </a>
                        <a class="instance-expand"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></a>
                    </h3>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-12">
                    {{communication_status}}
                    {{pump_status}}
                    {{light_status}}
                    {{fan_status}}
                    {{heater_status}}
                </div>
            </div>
        </div>';
        $body = '<div class="instance-body col-sm-12 col-md-12 col-lg-6">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    {{data_status}}
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    {{water_test_status}}
                </div>
            </div>
        </div>';
        return '<li class="instance" data-id="{{id}}">'.$header.$body.'</li>';
    }
}