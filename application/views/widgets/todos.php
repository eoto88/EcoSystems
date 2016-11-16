<div id="widget-todos" class="widget">
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-check fa-fw "></i></span>
        <h2><?php echo __('ToDo\'s') ?></h2>
        <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
    </header>
    <div class="widget-body">
        <h4>
            <i class="fa fa-exclamation fa-fw "></i>&nbsp;
            <?php echo __('Uncompleted tasks') ?>&nbsp;
            (<span id="unchecked-todos-count"><?php echo count($uncheckedTodos) ?></span>)
        </h4>
        <ul id="unchecked-todos">
            <?php
            if( count($uncheckedTodos) ) {
                $lastIdInstance = 0;
                foreach( $uncheckedTodos as $toDo ) {
                    if($lastIdInstance != $toDo['id_instance']) {
                        $lastIdInstance = $toDo['id_instance'];
                        echo '<li class="instance-group-title" data-id="'. $toDo['id_instance'] .'">'. $toDo['instance_title'] .'</li>';
                    }

                    echo Str::format($todoLi, array(
                        'id' => $toDo['id'],
                        'id_instance' => $toDo['id_instance'],
                        'title' => $toDo['title']
                    ));
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
                $lastIdInstance = 0;
                foreach( $checkedTodos as $toDo ) {
                    if($lastIdInstance != $toDo['id_instance']) {
                        $lastIdInstance = $toDo['id_instance'];
                        echo '<li class="instance-group-title" data-id="'. $toDo['id_instance'] .'">'. $toDo['instance_title'] .'</li>';
                    }

                    echo Str::format($todoLiChecked, array(
                        'id' => $toDo['id'],
                        'id_instance' => $toDo['id_instance'],
                        'title' => $toDo['title']
                    ));
                }
            }
            ?>
        </ul>
        <button class="btn-new-todo btn btn-default"><?php echo __('Add a ToDo') ?></button>

        <div id="todo-form">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-pencil fa-fw"></i></span>
                <h2><?php echo __('Form ToDo') ?></h2>
                <span class="close-form"><i class="fa fa-times"></i></span>
            </header>
            <div class="widget-body">
                <?php echo $form; ?>
            </div>
        </div>
    </div>

    <script type="text/x-handlebars-template" id="no-todo-tmpl">
        <li id="no-todo" style="opacity: 0; height: 0;"><span><?php echo __('No task in the to do list') ?></span></li>
    </script>
    <script type="text/x-handlebars-template" id="new-todo-tmpl">
        <?php echo $todoLi; ?>
    </script>
</div>