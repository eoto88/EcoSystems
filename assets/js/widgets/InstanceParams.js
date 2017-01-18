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
                    cmpType: 'table'
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
                        //{
                        //    cmpType: 'formSep',
                        //    title: 'Options'
                        //},
                        //{
                        //    cmpType: ''
                        //},
                        //{
                        //    cmpType: 'button',
                        //    text: 'Save'
                        //}
                    ]

                }
            ],
            listeners: [

            ]
        });
    }
});