<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstanceParameters {

    public function getView( $id_instance) {
        $vParameters = null;

        $vParameters = View::factory("widgets/instanceParameters")->set(array(
//            'instance_params' => $mParam->getInstanceParams($id_instance),
        ));

        return $vParameters->render();
    }
}