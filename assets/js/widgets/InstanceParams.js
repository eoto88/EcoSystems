/**
 * Created by eoto88 on 15/01/17.
 */
ES.WidgetInstanceParams = ES.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },

    init: function() {
        var me = this,
            idInstance = ES.getActiveInstanceId();

        me._super('widget-params', {
            items: [
                {
                    cmpIndex: 'TableParams',
                    cmpType: 'table',
                    url: BASE_URL + "api/instances/"+ idInstance +"/params",
                    groupBy: 'id_group',
                    groupTitle: 'groupTitle',
                    groupActions: [
                        {
                            title: 'Edit',
                            type: 'edit'
                        },
                        {
                            title: 'Delete',
                            type: 'delete'
                        }
                    ],
                    columns: [
                        {
                            title: 'Title',
                            name: 'title',
                            width: 4
                        },
                        {
                            title: 'Alias',
                            name: 'alias',
                            width: 3
                        },
                        {
                            title: 'Type',
                            name: 'type',
                            width: 3
                        },
                        {
                            title: 'Actions',
                            cmpType: 'columnActions',
                            width: 1,
                            actions: [
                                {
                                    title: 'Edit',
                                    type: 'edit'
                                },
                                {
                                    title: 'Delete',
                                    type: 'delete'
                                }
                            ]
                        }
                    ]
                },
                {
                    cmpIndex: 'FormParam',
                    cmpType: 'form',
                    title: 'Form Parameter',
                    hidden: true,
                    fields: [
                        {
                            cmpType: 'hidden',
                            name: 'id'
                        },
                        {
                            cmpType: 'text',
                            name: 'alias',
                            label: 'Alias',
                            maxLength: 25
                        },
                        {
                            cmpType: 'text',
                            name: 'title',
                            label: 'Title',
                            maxLength: 50
                        },
                        {
                            cmpType: 'combo',
                            name: 'id_type',
                            label: 'Type',
                            url: BASE_URL + "api/param-types/",
                            valField: 'id',
                            titleField: 'title'
                        },
                        {
                            cmpType: 'combo',
                            name: 'id_group',
                            label: 'Group',
                            url: BASE_URL + "api/instances/"+ idInstance +"/param-groups",
                            valField: 'id',
                            titleField: 'title'
                        },
                        {
                            cmpType: 'jsonfields',
                            name: 'options',
                            title: 'Options'
                        }
                    ],
                    buttons: [
                        {
                            cmpType: 'button',
                            text: 'Save'
                        },
                        {
                            cmpType: 'button',
                            text: 'Cancel'
                        }
                    ]
                }
            ],
            listeners: [
                {
                    sel: 'table .action-edit',
                    event: 'click',
                    fn: me.editParam
                }
            ]
        });
    },

    editParam: function(event) {
        event.preventDefault();
        var me = this,
            idInstance = ES.getActiveInstanceId(),
            idParam = $(event.target.closest('tr')).data('id'),
            formParam = ES.getCmp('FormParam');

        ES.ajax({
            url: BASE_URL + "api/instances/"+ idInstance +"/params/"+ idParam,
            success: function(data) {
                var param = data.entities[0];
                formParam.populate(param);
                formParam.show();
                ES.scrollToMe(formParam.getCmp());
                //if(param.options) {
                //    var options = ES.parseData(param.options);
                //    $.each(options, function(key, option) {
                //        formParam.addField(option);
                //    });
                //}
            }
        });
    }
});