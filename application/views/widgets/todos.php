<div id="widget-todos" class="widget">
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-check fa-fw "></i></span>
        <h2><?php echo __('ToDo\'s') ?></h2>
        <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
    </header>
    <div class="widget-body">
        <?php
        $checkedTodos = array();
        $uncheckedTodos = array();
        if( count($toDos) ) {
            foreach( $toDos as $toDo ) {
                if( $toDo['checked'] ) {
                    $checkedTodos[] = $toDo;
                } else {
                    $uncheckedTodos[] = $toDo;
                }
            }
        }
        ?>
        <h4>
            <i class="fa fa-exclamation fa-fw "></i>&nbsp;
            <?php echo __('Uncompleted tasks') ?>&nbsp;
            (<span id="unchecked-todos-count"><?php echo count($uncheckedTodos) ?></span>)
        </h4>
        <ul id="unchecked-todos">
            <?php
            if( count($uncheckedTodos) ) {
                foreach( $uncheckedTodos as $toDo ) {
                    $title = $toDo['title'];
                    if( isset($toDo['instance_title']) ) {
                        $title .= ' ('.$toDo['instance_title'].')';
                    }
                    $edit = '<span class="edit"><i class="fa fa-pencil"></i></span>';
                    $title = '<span class="todo"><i class="fa fa-square-o check-icon"></i><span class="todo-title">'.$title.'</span></span>';
                    echo '<li id="todo-'.$toDo['id_todo'].'">'.$title.$edit.'<span class="clearfix"></span></li>';
                }
            } else {
                echo '<li id="no-todo"><span>'. __('No task in the to do list') .'</span></li>';
            }
            ?>
        </ul>
        <h4><i class="fa fa-check fa-fw "></i>&nbsp;
            <?php echo __('Completed tasks') ?>&nbsp;
            (<span id="checked-todos-count"><?php echo count($checkedTodos) ?></span>)
        </h4>
        <ul id="checked-todos">
            <?php
            if( count($checkedTodos) ) {
                foreach( $checkedTodos as $toDo ) {
                    $title = $toDo['title'];
                    if( isset($toDo['instance_title']) ) {
                        $title .= ' ('.$toDo['instance_title'].')';
                    }
                    $edit = '<span class="edit"><i class="fa fa-pencil"></i></span>';
                    $title = '<span class="todo"><i class="fa fa-check-square-o check-icon"></i><span class="todo-title">'.$title.'</span></span>';
                    echo '<li id="todo-'.$toDo['id_todo'].'">'.$title.$edit.'<span class="clearfix"></span></li>';
                }
            }
            ?>
        </ul>
        <button class="bouton-new-todo btn btn-default"><?php echo __('Add a ToDo') ?></button>

        <div id="todo-form">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-pencil fa-fw"></i></span>
                <h2><?php echo __('Form ToDo') ?></h2>
            </header>
            <div class="widget-body">
                <?php echo $form; ?>
            </div>
        </div>
    </div>
</div>