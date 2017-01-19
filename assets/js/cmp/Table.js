/**
 * Created by eoto88 on 15/01/17.
 */
ES.Table = ES.Cmp.extend({
    initCmp: function(config) {
        var me = this;

        me.cmpId = me._super('table');
        me.appendTo(config.parent);
        var $table = $("#"+ me.cmpId);
        $.each(config.columns, function(key, config) {
            $table.append('<th>'+ config.title +'</th>');
        });
        ES.ajax({
            url: config.url,
            success: function(data) {
                $.each(data.entities, function(key, entity) {
                    var row = '';
                    row += '<td>';
                    $.each(config.columns, function(key, column) {
                        row += '<th>'+ config.title +'</th>';
                    });
                    row += '</td>';
                    $table.append(row);
                });
            }
        });
        //$.each(configs.columns, function(key, config) {
        //    var cmp = ES.create(config.cmpType);
        //    config.parent = $form;
        //    cmp.initCmp(config);
        //});
    },

    /**
     * @override
     * @returns {string}
     */
    getTpl: function() {
        var me = this;
        return '<table id="'+ me.cmpId +'"></table>';
    }

}, 'ES.Table');