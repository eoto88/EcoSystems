<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetInstanceParameters {

    public function getView( $id_instance) {
        $hForm = new Helper_Form();
        $mParam = new Model_Param();
        $vParameters = null;

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

    function getTodoLi($checked = false) {
        $icon = 'fa-square-o';
        if($checked) {
            $icon = 'fa-check-square-o';
        }
        $dropdown ='<div class="dropdown">'.
            '<button class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>'.
            '<ul class="dropdown-menu js-status-update pull-right">'.
            '<li><a href="#" class="action-edit"><i class="fa fa-pencil"></i>&nbsp;'. __('Edit') .'</a></li>'.
            '<li><a href="#" class="action-delete"><i class="fa fa-trash-o"></i>&nbsp;'. __('Delete') .'</a></li>'.
            '</ul></div>';
        $edit = '<span class="actions">'. $dropdown .'</span>';
        $title = '<span><i class="fa '. $icon .' check-icon"></i><span class="todo-title">{{title}}</span>'.$edit.'</span>';
        return '<li class="todo" data-id="{{id}}" data-id-instance="{{id_instance}}">'.$title.'<span class="clearfix"></span></li>';
    }
}