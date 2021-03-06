<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Instance extends Controller_AuthenticatedPage {

    public function before() {
        parent::before();
        $this->jsTranslations = array();
    }

    public function action_index() {
        $this->template->title = __('Instance');
        $this->template->icon = 'fa-list-alt';
        $mInstance = new Model_Instance();
        $instance = null;
        $mInstanceType = new Model_InstanceType();
        $instanceTypes = $mInstanceType->getInstanceTypes();
        $hForm = new Helper_Form();

        if( is_numeric($this->request->param('id')) ) {
            $instance = $mInstance->getInstance($this->request->param('id'), $this->user['id_user']);
            if( ! $instance ) {
                throw new HTTP_Exception_404;
            }
        } else if( $this->request->param('id') == 'new' ) {
            // Default values...
        } else {
            throw new HTTP_Exception_404;
        }

        $form = $hForm->createForm(array(
            'name' => 'form-instance',
            'api-url' => 'instances',
//            'url' => URL::base(TRUE, TRUE) . 'instances',
            'fields' => array(
                array(
                    'name' => 'id',
                    'type' => 'hidden',
                    'value' => $instance ? $instance['id'] : ''
                ),
                array(
                    'name' => 'icon',
                    'type' => 'iconpicker',
                    'label' => __('Icon'),
                    'value' => $instance ? $instance['icon'] : ''
                ),
                array(
                    'name' => 'title',
                    'label' => __('Title'),
                    'maxlength' => 25,
                    'value' => $instance ? $instance['title'] : ''
                ),
                array(
                    'name' => 'code',
                    'label' => __('Code (UUID)'),
                    'maxlength' => 36,
                    'disabled' => true,
                    'value' => $instance ? $instance['code'] : ''
                ),
                array(
                    'type' => 'select',
                    'values' => $instanceTypes,
                    'name' => 'type',
                    'label' => __('Instance type'),
                    'value' => $instance ? $instance['type'] : ''
                ),
                array(
                    'type' => 'select',
                    'values' => array(),
                    'name' => 'params',
                    'label' => __('Params'),
                    'selectize' => true
                    //'value' => $instance ? $instance['type'] : ''
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'monitored',
                    'label' => __('Monitored'),
//                    'value' => '1',
                    'value' => $instance ? $instance['monitored'] : '0'
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'water_tests',
                    'label' => __('Show water tests page'),
//                    'value' => '1',
                    'value' => $instance ? $instance['water_tests'] : '0'
                ),
                array(
                    'type' => 'submit'
                )
            )
        ));

        $instancesData = array(
            'instances' => $this->instances,
            'instance_types' => $instanceTypes,
            'instance' => $instance,
            'form' => $form
        );

        // If the instance exists
        if( isset($instance) ) {
            $hWidgetInstances = new Helper_WidgetInstances();
            $vInstance = $hWidgetInstances->getViewSingleInstance($instance['id']);

            $hWidgetTodos = new Helper_WidgetTodos();
            $vTodos = $hWidgetTodos->getView($this->user['id_user'], $instance['id']);
            $instancesData['widget_todos'] = $vTodos;
            $instancesData['widget_instances'] = $vInstance;

            $hWidgetInstanceParams = new Helper_WidgetInstanceParameters();
            $vInstanceParams = $hWidgetInstanceParams->getView($instance['id']);
            $instancesData['widget_instanceParameters'] = $vInstanceParams;
        }

        $view = View::factory( "instance" )->set( $instancesData );;
        $this->template->content = $view->render();
    }

} // End Welcome
