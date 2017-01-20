/**
 * Created by eoto88 on 15/01/17.
 */
ES.Table = ES.Cmp.extend({
    groupBy: null,
    groupTitle: null,
    columns: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('table');
        me.appendTo(config.parent);
        var $table = $("#"+ me.cmpId),
            header = '<tr>';
        $.each(me.columns, function(key, column) {
            header += '<th class="col-xs-'+ column.width +'">'+ column.title +'</th>';
        });
        $table.find('thead').append(header + '</tr>');
        ES.ajax({
            url: config.url,
            success: function(data) {
                me.createRows($table.find('tbody'), data);
            }
        });
    },

    /**
     * @override
     * @returns {string}
     */
    getTpl: function() {
        var me = this;
        return '<table id="'+ me.cmpId +'" class="table table-hover table-fixed table-striped table-bordered"><thead></thead><tbody></tbody></table>';
    },

    createRows: function($table, data) {
        var me = this,
            lastGroup = 0;

        $.each(data.entities, function(key, entity) {
            if(me.groupBy && me.groupTitle && lastGroup != entity[me.groupBy]) {
                lastGroup = entity[me.groupBy];
                $table.append('<tr class="groupTitle"><td colspan="'+ me.columns.length +'">'+ entity[me.groupTitle] +'</td></tr>');
            }

            var row = '';
            row += '<tr data-id="'+ entity.id +'">';
            $.each(me.columns, function(key, column) {
                row += '<td class="col-xs-'+ column.width +'">';
                if(column.name) {
                    row += entity[column.name];
                } else if(column.cmpType ==  'columnActions') {
                    var actionsTpl = '<div class="ddActionsWrapper"><div class="ddActions"><button class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button><ul class="dropdown-menu js-status-update pull-right">';
                    $.each(column.actions, function(key, action) {
                        var icon = '';

                        if(action.type == 'edit') {
                            icon = '<i class="fa fa-pencil"></i>';
                        } else if(action.type == 'delete') {
                            icon = '<i class="fa fa-trash-o"></i>';
                        }

                        actionsTpl += '<li><a href="#" class="action-'+ action.type +'">'+ icon +'&nbsp;'+ action.title +'</a></li>';
                    });
                    actionsTpl += '</ul></div></div>';
                    row += actionsTpl;
                }
                row += '</td>';
            });
            row += '</tr>';
            $table.append(row);
            $table.find('.dropdown-toggle').dropdown();
        });
    }

}, 'ES.Table');