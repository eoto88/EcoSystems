<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstances {

    public function getViewInstances($instances) {
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

            $instancesView[] = $instance;
        }

        $vInstances = View::factory("widgets/instances")->set(array(
            'instances' => $instancesView
        ));

        return $vInstances->render();
    }

    public function getViewSingleInstance($id_instance, $id_user) {
        $hStatus = new Helper_Status();

        $mInstance = new Model_Instance();
        $instance = $mInstance->getInstance($id_instance, $id_user);
        $liveData = $mInstance->getLiveData( $id_instance );

        $mData = new Model_Data();
        $lastData = $mData->getLastData( $id_instance );

        $instance['communication_status'] = $hStatus->getCommunicationStatus($liveData);
        $instance['pump_status'] = $hStatus->getStatus('pump', 'Pump', $liveData['pump_on']);
        $instance['light_status'] = $hStatus->getStatus('light', 'Light', $liveData['light_on']);
        $instance['fan_status'] = $hStatus->getStatus('fan', 'Fan', $liveData['fan_on']);
        $instance['heater_status'] = $hStatus->getStatus('heater', 'Heater', $liveData['heater_on']);
        $instance['data_status'] = $hStatus->getDataStatus($lastData);

        $vInstances = View::factory("widgets/instances")->set(array(
            'instances' => array($instance)
        ));

        return $vInstances->render();
    }
}