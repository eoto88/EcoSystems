<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstanceParameters {

    public function getView( $id_instance) {
        $hForm = new Helper_Form();
        $mParam = new Model_Param();
        $mParamType = new Model_ParamType();
        $vParameters = null;
        $arrParamTypes = array();

        foreach($mParamType->getParamTypes() as $paramType) {
            $arrParamTypes[$paramType['id']] = $paramType['title'];
        }

        $form = $hForm->createForm(array(
            'name' => 'form-parameter',
            'fields' => array(
                array(
                    'name' => 'id',
                    'type' => 'hidden'
                ),
                array(
                    'name' => 'alias',
                    'label' => __('Alias'),
                    'maxlength' => 25
                ),
                array(
                    'name' => 'title',
                    'label' => __('Title'),
                    'maxlength' => 100
                ),
                array(
                    'type' => 'select',
                    'name' => 'id_type',
                    'label' => __('Type'),
                    'values' => $arrParamTypes
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'options',
                    'label' => __('Options')
                ),
                array(
                    'type' => 'submit'
                )
            )
        ));

        $vParameters = View::factory("widgets/instanceParameters")->set(array(
            'instance_params' => $mParam->getInstanceParams($id_instance),
            'form' => $form
        ));

        return $vParameters->render();
    }
}