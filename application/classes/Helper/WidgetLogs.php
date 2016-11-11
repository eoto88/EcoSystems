<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetLogs {

    public function getViewLogs() {
        $vLogs = View::factory("widgets/logs")->set(array());

        return $vLogs->render();
    }

    public function getViewSingleInstanceLogs($id_instance) {
        $vLogs = View::factory("widgets/logs")->set(array(
            'id_instance' => $id_instance
        ));

        return $vLogs->render();
    }
}