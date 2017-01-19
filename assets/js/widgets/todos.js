ES.WidgetTodos = ES.Widget.extend({
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

                        me.addTodoInList({
                            checked: false,
                            id_instance: todoData.id_instance,
                            todo: tmplNewTodo(todoData)
                        });

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

    scrollToForm: function() {
        $('html, body').animate({
            scrollTop: ($("#todo-form form").offset().top - 130)
        }, 1500);
    },

    /**
     * Function called when checkbox icon is clicked
     *
     * @param event
     * @param element Element trigering the event
     */
    onCheckTodoClick: function(event, element) {
        var me = event.data.context,
            $component = $('#'+ me.cssId),
            $todo = $(element).closest('li.todo'),
            done = 0;

        if( $todo.parent().attr('id') == 'unchecked-todos') {
            done = 1;
        }

        var id = $todo.data('id'),
            id_instance = $todo.data('id-instance'),
            data = { 'id': id, 'done': done };

        ES.ajax({
            url: BASE_URL + "api/instances/" + id_instance + "/todos/" + id,
            method: 'PUT',
            data: data,
            success: function(data) {
                $todo.animate({'height': 0, 'opacity': 0}, 500, function() {
                    $todo = $(this).detach();
                    var id_instance = $todo.data('id-instance');
                    $todo.find('.check-icon').toggleClass('fa-square-o').toggleClass('fa-check-square-o');

                    me.addTodoInList({
                        checked: (done == 1),
                        id_instance: id_instance,
                        todo: $todo
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

        me.scrollToForm();
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

        me.scrollToForm();

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
                    var id_instance = $todo.data('id-instance'),
                        id = $todo.data('id'),
                        url = BASE_URL + "api/instances/" + id_instance + "/todos/" + id,
                        $form = $('#todo-form');

                    if($form) {
                        if(id == $form.find('[name=id]').val()) {
                            me.emptyForm($form);
                            $form.slideUp();
                        }
                    }

                    ES.ajax({
                        url: url,
                        method: 'DELETE',
                        success: function(data) {
                            $todo.remove();
                            me.updateTodoCounts();
                            dialog.close();
                        }
                    });
                }
            }, {
                label: 'Close', // TODO Translate
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    },

    /**
     * Function to add a todo in a list
     *
     * @param params    Parameters object
     * @param params.checked    true is checked list or false for unchecked list
     * @param params.id_instance    Todo instance id
     * @param params.todo   Todo element
     */
    addTodoInList: function(params) {
        var me = this,
            $list,
            $todo = ES.getJQueryObject(params.todo);

        $todo.css({
            height: 0,
            opacity: 0
        });

        if(params.checked) {
            $list = $("#checked-todos");
        } else {
            $list = $("#unchecked-todos");
        }

        if( ES.isDashboard() ) {
            var $instanceGroup = $list.find('.instance-group-title[data-id="'+ params.id_instance +'"]');
            if($instanceGroup.length > 0) {
                // Get all todos in the group
                var instanceTodos = $list.find('[data-id-instance="'+ params.id_instance +'"]');
                if(instanceTodos.length > 0) {
                    // If instance group is empty
                    instanceTodos.last().after( params.todo );
                } else {
                    $instanceGroup.after( params.todo );
                }
            } else {
                // If instance group doesn't exist
                ES.ajax({
                    url: BASE_URL + "api/instances/" + params.id_instance,
                    success: function(data) {
                        if(data[0]) {
                            $list.append('<li class="instance-group-title" data-id="'+ params.id_instance +'">'+ data[0].title +'</li>');
                            me.addTodoInList(params);
                        }
                    }
                });
            }
            if( $list.find('#no-todo') ) {
                $list.find('#no-todo').animate({'height': 0, 'opacity': 0}, 500, function() {
                    $(this).remove();
                });
            }
        } else {
            $list.append( params.todo );
        }
        $todo.animate({'height': '100%', 'opacity': 1}, 500, function() {
            me.updateTodoCounts();
        });
    }
});