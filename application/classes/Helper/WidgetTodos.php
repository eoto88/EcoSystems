<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetTodos {

    public function getView($id_instance = null) {
        $hForm = new Helper_Form();
        $mTodo = new Model_Todo();
        $config = Kohana::$config->load('app');
        $vTodos = null;

        $form = $hForm->createForm(array(
            'name' => 'form-todo',
            'api-url' => 'todos',
            'fields' => array(
                array(
                    'name' => 'id_todo',
                    'type' => 'hidden'
                ),
                array(
                    'name' => 'title',
                    'label' => __('Title'),
                    'maxlength' => 25
                ),
                array(
                    'type' => 'select',
                    'values' => $config['time_units'],
                    'name' => 'time_unit',
                    'label' => __('Time unit')
                ),
                array(
                    // TODO numberfield
                    'name' => 'interval_value',
                    'label' => __('Interval value')
                ),
                array(
                    'type' => 'submit'
                )
            )
        ));

        $todos = null;
        if( $id_instance ) {
            $todos = $mTodo->getTodosByIdInstance($id_instance);
        } else {
            $todos = $mTodo->getTodosWithState();
        }

        $vTodos = View::factory("widgets/todos")->set(array(
            'toDos' => $todos,
            'form' => $form
        ));

        return $vTodos->render();
    }
}