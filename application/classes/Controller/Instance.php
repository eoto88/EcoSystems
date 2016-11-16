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
        $config = Kohana::$config->load('app');
        $post = $this->request->post();
        $hForm = new Helper_Form();

        if( is_numeric($this->request->param('id')) ) {
            $instance = $mInstance->getInstance($this->request->param('id'), $this->user['id_user']);
        } else if( $this->request->param('id') == 'new' ) {
            // Nothing yet...
        } else {
            throw new HTTP_Exception_404;
        }


        if($post) {
            if( isset($post['title']) && isset($post['code']) && isset($post['type']) ) {
                if( !isset($post['monitored']) ) {
                    $post['monitored'] = 0;
                }
                if( !isset($post['water_tests']) ) {
                    $post['water_tests'] = 0;
                }

                $mInstance->insertInstance($this->user['id_user'], $post['title'], $post['type'], $post['monitored'], $post['water_tests']);
            }
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
                    'values' => $config['instance_types'],
                    'name' => 'type',
                    'label' => __('Instance type'),
                    'value' => $instance ? $instance['type'] : ''
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'monitored',
                    'label' => __('Monitored'),
                    'value' => 1,
                    'value' => $instance ? $instance['monitored'] : ''
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'water_tests',
                    'label' => __('Show water tests page'),
                    'value' => 1,
                    'value' => $instance ? $instance['water_tests'] : ''
                ),
                array(
                    'type' => 'submit'
                )
            )
        ));

        $instancesData = array(
            'instances' => $this->instances,
            'instance_types' => $config['instance_types'],
            'instance' => $instance,
            'form' => $form
        );

        // If the instance exists
        if( isset($instance) ) {
            $hWidgetTodos = new Helper_WidgetTodos();
            $vTodos = $hWidgetTodos->getView($this->user['id_user'], $instance['id']);
            $instancesData['widget_todos'] = $vTodos;
        }

        $view = View::factory( "instance" )->set( $instancesData );;
        $this->template->content = $view->render();
    }

} // End Welcome
