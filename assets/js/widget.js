/**
 * Created by eoto on 31/10/14.
 */
App.Widget = Class.extend({
    init: function(cssId) {
        this.cssId = cssId;

        var $component = $('#' + this.cssId);
        $component.find('.widget-expand').click(function() {
            $component.find('.widget-body').slideToggle();
            $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
        });
    },

    onClick: function(selector, fn) {
        var me = this,
            $element = (selector instanceof jQuery) ? selector : $(selector);

        $element.on( "click", {
            context: me
        }, function(event) {
            event.preventDefault();
            fn(event, this);
        });
    },

    /**
     *
     * @param {Object} params
     * @param {string} params.url
     * @param {string} [params.method]
     * @param {Object} params.data
     * @param {Function} params.success
     * @param {Function} [params.failure]
     */
    ajax: function(params) {
        if( ! params.method ) {
            params.method = 'POST';
        }
        // TODO Default values for success and fail methods
        $.ajax({
            url: params.url,
            type: params.method,
            contentType: 'application/json',
            data: JSON.stringify(params.data),
            success: params.success,
            dataType: 'json'
        }).fail(function(data) {
            if( params.failure ) {
                params.failure(data);
            }
        });
    },

    /**
     *
     * @param {Object} params
     * @param {string} params.form
     * @param {string} params.url
     * @param {Object} params.data
     * @param {string} [params.method]
     * @param {Function} params.success
     * @param {Function} [params.failure]
     */
    submitForm: function(params) {
        var me = this,
            $form = (params.form instanceof jQuery) ? params.form : $(params.form);

        me.clearErrors($form);

        me.ajax({
            url: params.url,
            method: params.method,
            data: params.data,
            success: function(data) {
                $form.prepend('<div class="message message-success"><p>Successfully saved</p></div>'); // TODO Translate
                setTimeout(function() {
                    $form.find('.message-success').remove();
                }, 10000); // 10 seconds
                params.success(data);

                me.emptyForm($form);
            },
            failure: function(data) {
                // TODO if 404
                // TODO if 406
                $.each(data.responseJSON.errors, function(index, value) {
                    $field = $form.find('[name='+ index +']');
                    if( $field ) {
                        var $formGroup = $field.closest('.form-group');
                        $formGroup.addClass('has-error');
                        $formGroup.append('<span class="help-block">'+ value +'</span>')
                    }
                });
                if( params.failure ) {
                    params.failure(data);
                }
            }
        });
    },

    /**
     * Function for removing values and messages in a form
     *
     * @param form Form to empty
     */
    emptyForm: function(form) {
        var me = this,
            $form = (form instanceof jQuery) ? form : $(form);

        $form.find('.message-success').remove();
        $.each($form.find('input'), function() { // TODO Add textareas and others...
            $(this).val('');
        });
        me.clearErrors($form);
    },

    /**
     * Function for clearing error messages in form
     *
     * @param form Form to clean error messages
     */
    clearErrors: function(form) {
        var $form = (form instanceof jQuery) ? form : $(form),
            formGroups = $form.find('.form-group');

        $.each(formGroups, function() {
            if( $(this).hasClass('has-error') ) {
                $(this).removeClass('has-error');
                $(this).find('.help-block').remove();
            }
        });
    }
});

$(document).ready(function() {
    $(".widget").each(function() {
        var widget = WidgetList[$(this).attr('id')];
        if( widget ) {
            App.factory(WidgetList[$(this).attr('id')]);
        }
    });
});




/*App.WidgetLogs = App.Widget.extend({
    init: function() {
        this._super( 'widget-logs' );
    }
});

App.WidgetLive = App.Widget.extend({
    init: function() {
        this._super( 'widget-live' );
    }
});*/