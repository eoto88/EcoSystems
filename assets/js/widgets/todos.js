App.WidgetTodos = App.Widget.extend({
    init: function() {
        this._super( 'widget-todos' );

        var me = this;

        $('#' + me.cssId + ' span.todo').click(function() {
            var $todo = $(this).parent(),
                done = 0;
            if( $todo.parent().attr('id') == 'unchecked-todos') {
                done = 1;
            }

            $todo.find('.check').addClass('done');
            var id = $todo.data('id'),
                data = { 'id': id, 'done': done };

            $.ajax({
                url: BASE_URL + "ajax/updateToDo/",
                data: JSON.stringify( data ),
                type: 'POST',
                cache: false,
                dataType: 'json',
                contentType:"application/json; charset=utf-8"
            }).done(function() {
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
                    $todo.animate({'height': 38, 'opacity': 1}, 500, function() {
                        var uncheckedTodosCount = $("#unchecked-todos li").length;
                        $('#unchecked-todos-count').html( uncheckedTodosCount );
                        $('#checked-todos-count').html( $("#checked-todos li").length );

                        if( uncheckedTodosCount === 0 ) {
                            var tmplNoTodo = Handlebars.compile( $("#no-todo-tmpl").html() );
                            $("#unchecked-todos").append(tmplNoTodo);
                            $("#no-todo").animate({'height': 38, 'opacity': 1}, 500);
                        }
                    });
                });
            }).fail(function() {
                alert('Error! Update todo');
            });
        });

        $('#' + me.cssId + ' .edit').click(function() {
            $('#todo-form').slideDown();
            var id_instance = getCurrentInstanceId(),
                id_todo = $(this).closest('li').data('id'),
                url = BASE_URL + "api/instances/" + id_instance + "/todos/" + id_todo;

            $.getJSON(url, function(data) {
                $('#todo-form form').populate(data[0]);
            });
        });

        $('#' + me.cssId + ' .delete').click(function() {
            var $todo = $(this).closest('li')

            BootstrapDialog.show({
                title: 'Delete Todo',
                type: BootstrapDialog.TYPE_WARNING,
                message: 'Are you sure you want to delete this todo?',
                buttons: [{
                    label: 'Delete',  // TODO Translate
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var id_instance = getCurrentInstanceId(),
                            id_todo = $todo.data('id'),
                            url = BASE_URL + "api/instances/" + id_instance + "/todos/" + id_todo;

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
        });

        $('#' + me.cssId + ' .btn-new-todo').click(function() {
            $('#todo-form').slideDown();
            // TODO Empty the form
        });

        $('#todo-form form').submit(function(e) {
            e.preventDefault();

            var $form = $(this),
                id_instance = getCurrentInstanceId(),
                url = BASE_URL + "api/instances/" + id_instance + "/todos/",
                method = 'POST',
                jsonTodo = $form.serializeFormJSON(),
                newEntity = jsonTodo.id_todo ? false : true;

            if( newEntity ) {
                jsonTodo.id_instance = id_instance;
            } else {
                url += jsonTodo.id_todo;
                method = 'PUT';
            }

            me.clearErrors();

            $.ajax({
                url: url,
                type: method,
                contentType: 'application/json',
                data: JSON.stringify(jsonTodo),
                success: function(data) {
                    var todo = data.entities[0];
                    if( newEntity ) {
                        if( isDashboard() ) {
                            todo.title += ' ('+ todo.instance_title +')';
                        }
                        var tmplNewTodo = Handlebars.compile( $("#new-todo-tmpl").html() );
                        $("#unchecked-todos").append( tmplNewTodo(todo) );
                    } else {
                        var $li = $('#'+ me.cssId +' li[data-id='+ jsonTodo.id_todo +']'),
                            title = data[0].title;

                        if( isDashboard() ) {
                            title += ' ('+ data[0].instance_title +')';
                        }

                        $li.find('.todo-title').html(title);
                    }
                },
                dataType: 'json'
            }).fail(function(data) {
                $.each(data.responseJSON.errors, function(index, value) {
                    $field = $form.find('[name='+ index +']');
                    if( $field ) {
                        var $formGroup = $field.closest('.form-group');
                        $formGroup.addClass('has-error');
                        $formGroup.append('<span class="help-block">'+ value +'</span>')
                    }
                });
            });
        });
    },

    clearErrors: function() {
        var $form = $('#todo-form form'),
            formGroups = $form.find('.form-group');

        $.each(formGroups, function() {
            if( $(this).hasClass('has-error') ) {
                $(this).removeClass('has-error');
                $(this).find('.help-block').remove();
            }
        });
    }
});

/*$(document).ready(function() {
    if( $("#widget-todos").length == 1 ) {
        var wTodos = new WidgetTodos();
    }
});*/