/**
 * Created by eoto on 09/11/16.
 */
ES.WidgetLogs = ES.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },

    init: function() {
        var me = this;
        me._super('widget-logs');

        me.registerHelpers();

        me.createLogs();
    },

    refresh: function() {
        var me = this,
            $component = $('#'+ me.cssId);

        $component.find('.widget-body').empty();

        // TODO Improve this...
        this.createLogs();
    },

    createLogs: function() {
        var me = this,
            $component = $('#'+ me.cssId),
            id_instance = 0;

        if( ES.isInt($component.data('single-instance')) ) {
            id_instance = $component.data('single-instance');
        }

        me.startLoading();


        ES.ajax({
            // api/instances/1/logs (All logs from 1 instance)
            // api/logs (All logs from all instances)
            url: BASE_URL + "api/" + (id_instance > 0 ? "instances/" + id_instance : "") + "logs",
            success: function(data) {
                $component.find('.widget-body').append('<ul></ul>');;

                if(data.entities.length == 0) {
                    $component.find('.widget-body ul').append('<li id="no-logs">No logs</li>');
                } else {
                    var lastIdInstance = 0;
                    $.each(data.entities, function(key, logData) {
                        if(logData.id_instance != lastIdInstance) {
                            lastIdInstance = logData.id_instance;
                            $component.find('.widget-body ul').append('<li><h4>'+ logData.instance_title +'</h4></li>');
                        }
                        me.compileTpl({
                            tplId: 'log-tmpl',
                            appendTo: $component.find('.widget-body ul'),
                            data: logData
                        });
                    });
                }

                me.stopLoading();
            }
        });
    },

    registerHelpers: function() {
        Handlebars.registerHelper('logIcon', function() {
            var cls;
            if(this.type == 'info') {
                cls = 'fa fa-check success';
            } else {
                cls = 'fa fa-exclamation-triangle error';
            }
            return '<i class="'+ cls +'"></i>';
        });
    }
});