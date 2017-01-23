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

        if(me.groupBy) {
            header += '<th class="col-xs-1">Group</th>';
        }
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
        var me = this,
            cls = '';
        if(me.groupBy) {
            cls += "table-grouped";
        }
        return '<div id="'+ me.cmpId +'" class="table-responsive"><table class="table table-hover table-bordered '+ cls +' table-striped"><thead></thead><tbody></tbody></table></div>';
    },

    createRows: function($table, data) {
        var me = this,
            index = 0,
            lastGroup;

        $.each(data.entities, function(key, entity) {
            index++;
            if(me.groupBy && me.groupTitle && lastGroup != entity[me.groupBy]) {
                lastGroup = entity[me.groupBy];
                index = 0;
                var groupTitleWidth = me.columns.length,
                    actions = '';
                if(me.groupActions) {
                    actions += '<td>'+ me.createColumnActions(me.groupActions) +'</td>';
                } else {
                    groupTitleWidth++;
                }
                $table.append('<tr class="groupTitle"><td colspan="'+ groupTitleWidth +'">'+ entity[me.groupTitle] +'</td>'+ actions +'</tr>');
            }

            var trCls = '';
            if(index % 2) {
                trCls += 'odd';
            }

            var row = '';
            row += '<tr class="entity '+ trCls +'" data-id="'+ entity.id +'">';
            if(me.groupBy) {
                row += '<td class="col-xs-1"></td>';
            }
            $.each(me.columns, function(key, column) {
                row += '<td class="col-xs-'+ column.width +'">';
                if(column.name) {
                    row += entity[column.name];
                } else if(column.cmpType ==  'columnActions') {
                    row += me.createColumnActions(column.actions);
                }
                row += '</td>';
            });
            row += '</tr>';
            $table.append(row);
            $table.find('.dropdown-toggle').dropdown();
        });
    },

    createColumnActions: function(actions) {
        var actionsTpl = '<div class="ddActionsWrapper"><div class="ddActions"><button class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button><ul class="dropdown-menu js-status-update pull-right">';
        $.each(actions, function(key, action) {
            var icon = '';

            if(action.type == 'edit') {
                icon = '<i class="fa fa-pencil"></i>';
            } else if(action.type == 'delete') {
                icon = '<i class="fa fa-trash-o"></i>';
            }

            actionsTpl += '<li><a href="#" class="action-'+ action.type +'">'+ icon +'&nbsp;'+ action.title +'</a></li>';
        });
        return actionsTpl += '</ul></div></div>';
    }

}, 'ES.Table');