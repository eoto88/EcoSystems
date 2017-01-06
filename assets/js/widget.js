/**
 * Created by eoto on 31/10/14.
 */
App.Widget = Class.extend({
    config: {
        collapsible: true,
        refreshable: false
    },

    init: function(cssId) {
        this.cssId = cssId;
        var me = this,
            $component = $('#' + this.cssId);

        if(this.config.collapsible) {
            $component.find('header').append('<span class="widget-expand"><i class="fa fa-chevron-down"></i></span>');

            $component.find('.widget-expand').click(function() {
                $component.find('.widget-body').slideToggle();
                $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
            });
        }

        if(this.config.refreshable) {
            $component.find('header').append('<span class="widget-refresh"><i class="fa fa-refresh"></i></span>');

            $component.find('.widget-refresh').click(function() {
                if(ES.isFunction(me.refresh)) {
                    me.refresh();
                } else {
                    console.warn('No refresh function found.');
                }
            });
        }
    },

    onClick: function(selector, fn, data) {
        var me = this,
            $element = (selector instanceof jQuery) ? selector : $(selector),
            eventData = { context: me };

        if(data) {
            eventData.entityData = data;
        }

        $element.on( "click", eventData, function(event) {
            event.preventDefault();
            fn(event, this);
        });
    },

    startLoading: function() {
        var $component = $('#' + this.cssId);
        $component.find('.widget-body').append('<div class="overlay"></div><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>');
    },

    stopLoading: function() {
        var $component = $('#' + this.cssId);
        $component.find('.overlay').remove();
        $component.find('.spinner').remove();
    },

    compileTpl: function(params) {
        var me = this,
            tpl = null,
            $appendTo = null,
            data = (params.data ? params.data : {});

        if(params.tplId) {
            tpl = Handlebars.compile($("#"+ params.tplId).html());
        } else {
            console.error("tplId is necessary");
        }

        if(params.appendTo) {
            $appendTo = ES.getJQueryObject(params.appendTo);
        } else {
            console.error("appendTo is necessary");
        }

        if(params.empty) {
            $appendTo.empty();
        }

        $appendTo.append(tpl(data));
    },

    asyncCalls: function(params) {
        var me = this;

        if( ! params.calls instanceof Array) {
            console.error("calls must be an Array");
        }

        $.when.apply($, params.calls).then(function() {
            if(ES.isFunction(params.callback)) {
                if (arguments.length) {
                    params.callback.apply(this, arguments);
                } else {
                    // TODO What to do here?
                    // <debug>
                    console.warn('no arguments...')
                    // </debug>
                }
            } else {
                console.error("callback must be an function");
            }
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
            params.method = 'GET';
        }
        // TODO Default values for success and fail methods
        return $.ajax({
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
                if(data.responseJSON) {
                    $.each(data.responseJSON.errors, function(index, value) {
                        $field = $form.find('[name='+ index +']');
                        if( $field ) {
                            var $formGroup = $field.closest('.form-group');
                            $formGroup.addClass('has-error');
                            $formGroup.append('<span class="help-block">'+ value +'</span>')
                        }
                    });
                }
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