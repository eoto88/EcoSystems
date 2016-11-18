<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Helper_WidgetTodos {

    public function getView($id_user, $id_instance = null) {
        $hForm = new Helper_Form();
        $mTodo = new Model_Todo();
        $mInstance = new Model_Instance();
        $config = Kohana::$config->load('app');
        $vTodos = null;

        $selectInstanceValues = [];
        foreach($mInstance->getInstances($id_user) as $key => $instance) {
            $selectInstanceValues[$instance['id']] = $instance['title'];
        }

        $form = $hForm->createForm(array(
            'name' => 'form-todo',
//            'api-url' => 'todos',
            'fields' => array(
                array(
                    'name' => 'id',
                    'type' => 'hidden'
                ),
                array(
                    'type' => 'select',
                    'values' => $selectInstanceValues,
                    'name' => 'id_instance',
                    'label' => __('Instance')
                ),
                array(
                    'name' => 'title',
                    'label' => __('Title'),
                    'maxlength' => 50
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
            $todos = $mTodo->getTodos();
        }

        $checkedTodos = array();
        $uncheckedTodos = array();
        if( count($todos) ) {
            foreach( $todos as $toDo ) {
                if( $toDo['checked'] ) {
                    $checkedTodos[] = $toDo;
                } else {
                    $uncheckedTodos[] = $toDo;
                }
            }
        }

        $vTodos = View::factory("widgets/todos")->set(array(
            'uncheckedTodos' => $uncheckedTodos,
            'checkedTodos' => $checkedTodos,
            'form' => $form,
            'todoLi' => $this->getTodoLi(),
            'todoLiChecked' => $this->getTodoLi(true)
        ));

        return $vTodos->render();
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