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
        var me = this;
        me._super('widget-params', {
            items: [
                {
                    cmpType: 'table',
                    url: BASE_URL + "api/instances/"+ id_instance +"/params",
                    columns: [
                        {
                            title: 'Title',
                            name: 'title'
                        },
                        {
                            title: 'Alias',
                            name: 'alias'
                        },
                        {
                            title: 'Type',
                            name: 'typeTitle'
                        }
                    ]
                },
                {
                    cmpType: 'form',
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
                        //{
                        //    cmpType: ''
                        //},
                        {
                           cmpType: 'button',
                           text: 'Save'
                        }
                    ]

                }
            ],
            listeners: [

            ]
        });
    }
});