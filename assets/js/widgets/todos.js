App.WidgetTodos = App.Widget.extend({
    init: function() {
        this._super( 'widget-todos' );

        $('#' + this.cssId + ' span.todo').click(function() {
            var $todo = $(this).parent(),
                done = 0;
            if( $todo.parent().attr('id') == 'unchecked-todos') {
                done = 1;
            }

            $todo.find('.check').addClass('done');
            var id = $todo.attr('id').replace("todo-", ""),
                data = { 'id': id, 'done': done };

            $.ajax({
                url: BASE_URL + "ajax/updateToDo/",
                data: JSON.stringify( data ),
                type: 'POST',
                cache: false,
                dataType: 'json',
                contentType:"application/json; charset=utf-8"
            }).done(function() {
                $("#todo-" + id).animate({'height': 0, 'opacity': 0}, 500, function() {
                    $todo = $(this).detach();
                    $todo.find('i').toggleClass('fa-square-o').toggleClass('fa-check-square-o');
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
                    $("#todo-" + id).animate({'height': 38, 'opacity': 1}, 500, function() {
                        var uncheckedTodosCount = $("#unchecked-todos li").length;
                        $('#unchecked-todos-count').html( uncheckedTodosCount );
                        $('#checked-todos-count').html( $("#checked-todos li").length );

                        if( uncheckedTodosCount === 0 ) {
                            var tmplNoTodo = Handlebars.compile( $("#no-todo-tmpl").html() );
                            $("#unchecked-todos").append(tmplNoTodo)
                            $("#no-todo").animate({'height': 38, 'opacity': 1}, 500);
                        }
                    });
                });
            }).fail(function() {
                alert('Error!');
            });
        });

        $('#' + this.cssId + ' span.edit').click(function() {
            $('#todo-form').show();
        });

        $('#' + this.cssId + '.bouton-new-todo').click(function() {
            $('#todo-form').show();
        });
    }
});

/*$(document).ready(function() {
    if( $("#widget-todos").length == 1 ) {
        var wTodos = new WidgetTodos();
    }
});*/