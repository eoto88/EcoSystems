App.WidgetTodos = App.Widget.extend({
    init: function() {
        this._super( 'widget-todos' );

        var me = this;

        me.onClick('#'+ me.cssId +' .check-icon', me.onCheckTodoClick);
        me.onClick('#'+ me.cssId +' .action-edit', me.onEditTodoClick);
        me.onClick('#'+ me.cssId +' .action-delete', me.onDeleteTodoClick);
        me.onClick('#'+ me.cssId +' .btn-new-todo', me.onAddTodoClick);

        me.onClick('#'+ me.cssId +' .close-form', me.onCloseFormClick);

        me.initFormSubmit();
    },

    initFormSubmit: function() {
        var me = this;

        $('#todo-form form').submit(function(e) {
            e.preventDefault();

            var $form = $(this),
                id_instance = $form.find('[name="id_instance"]').val(),
                url = BASE_URL + "api/instances/" + id_instance + "/todos/",
                method = 'POST',
                jsonTodo = $form.serializeFormJSON(),
                newEntity = jsonTodo.id ? false : true;

            if( newEntity ) {
                jsonTodo.id_instance = id_instance;
            } else {
                url += jsonTodo.id;
                method = 'PUT';
            }

            me.submitForm({
                form: $form,
                url: url,
                method: method,
                data: jsonTodo,
                success: function(data) {
                    var todoData = data.entities[0];

                    if( newEntity ) {
                        var tmplNewTodo = Handlebars.compile( $("#new-todo-tmpl").html() );

                        if( isDashboard() ) {
                            var $instanceGroup = $("#unchecked-todos").find('.instance-group-title[data-id="'+ todoData.id_instance +'"]');
                            if($instanceGroup.length > 0) {
                                var instanceTodos = $("#unchecked-todos").find('[data-id-instance="'+ todoData.id_instance +'"]');
                                if(instanceTodos.length > 0) {
                                    // If instance group is empty
                                    instanceTodos.last().after( tmplNewTodo(todoData) );
                                } else {
                                    $instanceGroup.after( tmplNewTodo(todoData) );
                                }
                            } else {
                                // If instance group doesn't exist
                                $("#unchecked-todos").append('<li class="instance-group-title" data-id="'+ todoData.id_instance +'">'+ todoData.instance_title +'</li>');
                                $("#unchecked-todos").append( tmplNewTodo(todoData) );
                            }
                        } else {
                            $("#unchecked-todos").append( tmplNewTodo(todoData) );
                        }

                        var $todo = $('#'+ me.cssId +' li[data-id='+ todoData.id +']');

                        // Bind events on new buttons
                        me.onClick($todo.find('.action-edit'), me.onEditTodoClick);
                        me.onClick($todo.find('.action-delete'), me.onDeleteTodoClick);
                        me.onClick($todo.find('.check-icon'), me.onCheckTodoClick);
                        // Create dropdown after binding events on buttons
                        $todo.find('.dropdown-toggle').dropdown();

                        me.updateTodoCounts();
                    } else {
                        var $todo = $('#'+ me.cssId +' li[data-id='+ todoData.id +']');

                        $todo.find('.todo-title').html(todoData.title);
                    }
                }
            });
        });
    },

    /**
     * Function called when checkbox icon is clicked
     *
     * @param event
     * @param element Element trigering the event
     */
    onCheckTodoClick: function(event, element) {
        var me = event.data.context,
            $todo = $(element).closest('li.todo'),
            done = 0;

        if( $todo.parent().attr('id') == 'unchecked-todos') {
            done = 1;
        }

        var id = $todo.data('id'),
            id_instance = $todo.data('id-instance'),
            data = { 'id': id, 'done': done };

        me.ajax({
            url: BASE_URL + "api/instances/" + id_instance + "/todos/" + id,
            method: 'PUT',
            data: data,
            success: function(data) {
                $todo.animate({'height': 0, 'opacity': 0}, 500, function() {
                    $todo = $(this).detach();
                    $todo.find('.check-icon').toggleClass('fa-square-o').toggleClass('fa-check-square-o');
                    var $listTo = null;
                    if( done == 1 ) {
                        $listTo = $("#checked-todos");
                    } else {
                        if( $('#no-todo') ) {
                            $('#no-todo').animate({'height': 0, 'opacity': 0}, 500, function() {
                                $(this).remove();
                            });
                        }
                        $listTo = $("#unchecked-todos");
                    }

                    $listTo.append($todo);
                    $todo.animate({'height': '100%', 'opacity': 1}, 500, function() {
                        me.updateTodoCounts();
                    });
                });
            }
        });
    },

    /**
     * Function to update counts on top of tasks lists
     */
    updateTodoCounts: function() {
        var uncheckedTodosCount = $("#unchecked-todos li.todo").length;
        $('#unchecked-todos-count').html( uncheckedTodosCount );
        var checkedTodosCount = $("#checked-todos li.todo").length;
        $('#checked-todos-count').html( checkedTodosCount );

        if( uncheckedTodosCount === 0 ) {
            var tmplNoTodo = Handlebars.compile( $("#no-todo-tmpl").html() );
            $("#unchecked-todos").append(tmplNoTodo);
            $("#no-todo").animate({'height': 38, 'opacity': 1}, 500);
        }
    },

    /**
     * Function called when add button is clicked
     *
     * @param event
     * @param element Element trigering the event
     */
    onAddTodoClick: function(event, element) {
        var me = event.data.context,
            $form = $('#todo-form');

        me.emptyForm($form);

        $form.slideDown();
    },

    onCloseFormClick: function(event, element) {
        var $form = $('#todo-form');

        $form.slideUp();
    },

    /**
     * Function called when edit button is clicked
     *
     * @param event Click event
     * @param element Element trigering the event
     */
    onEditTodoClick: function(event, element) {
        var me = event.data.context,
            $todo = $(element).closest('li.todo'),
            id_instance = $todo.data('id-instance'),
            id = $todo.data('id'),
            url = BASE_URL + "api/instances/" + id_instance + "/todos/" + id;

        $('#todo-form').slideDown();

        $.getJSON(url, function(data) {
            $('#todo-form form').populate(data[0]);
        });
    },

    /**
     * Function called when delete button is clicked
     *
     * @param event Click event
     * @param element Element trigering the event
     */
    onDeleteTodoClick: function(event, element) {
        var me = event.data.context,
            $todo = $(element).closest('li.todo');

        BootstrapDialog.show({
            title: 'Delete Todo',
            type: BootstrapDialog.TYPE_WARNING,
            message: 'Are you sure you want to delete this todo?',
            buttons: [{
                label: 'Delete',  // TODO Translate
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var id_instance = getCurrentInstanceId(),
                        id = $todo.data('id'),
                        url = BASE_URL + "api/instances/" + id_instance + "/todos/" + id;

                    // FIXME Verify if todo in form and if this todo has this id
                    // FIXME if todo in form has this id emptyForm()

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(data) {
                            $todo.remove();
                            dialog.close();
                        }
                    }).fail(function() {
                        alert('error');
                    });
                }
            }, {
                label: 'Close', // TODO Translate
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
});