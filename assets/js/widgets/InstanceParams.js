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
                    cmpType: 'table',
                    url: BASE_URL + "api/instances/"+ idInstance +"/params",
                    groupBy: 'id_group',
                    groupTitle: 'groupTitle',
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
                    cmpType: 'form',
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
                            name: 'type',
                            label: 'Type',
                            valField: 'id',
                            values: []
                        },
                        {
                            cmpType: 'iconpicker',
                            name: 'number',
                            label: 'Iconpicker'
                        },
                        //{
                        //    cmpType: 'formSep',
                        //    title: 'Options'
                        //},
                        {
                           cmpType: 'button',
                           text: 'Save'
                        }
                    ]

                }
            ],
            listeners: [
                {
                    sel: 'table .action-edit',
                    event: 'click',
                    fn: function() {
                        debugger;
                    }
                }
            ]
        });

        me.onClick($('#widget-params').find('table .action-edit'), function() {
            debugger;
        });
    }
});