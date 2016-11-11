<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstances {

    public function getViewInstances() {
        $vInstances = View::factory("widgets/instances")->set(array());

        return $vInstances->render();
    }

    public function getViewSingleInstance($id_instance) {
        $vInstances = View::factory("widgets/instances")->set(array(
            'id_instance' => $id_instance
        ));

        return $vInstances->render();
    }
}