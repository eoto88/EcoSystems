/**
 * Created by eoto88 on 15/01/17.
 */
ES.WidgetFormData = ES.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },

    init: function() {
        var me = this,
            idInstance = ES.getActiveInstanceId();

        me._super('widget-formdata', {
            items: [
                {
                    cmpIndex: 'FormData',
                    cmpType: 'form',
                    //title: 'Form Data',
                    fields: [
                        {
                            cmpType: 'tagfield',
                            name: 'params',
                            label: 'Parameters to add data',
                            url: BASE_URL + "api/instances/"+ idInstance +"/params",
                            valField: 'id',
                            titleField: 'title'
                        }
                    ],
                    buttons: [
                        {
                            cmpType: 'button',
                            text: 'Select'
                        }
                    ]
                }
            ],
            listeners: [
                {
                    sel: 'form button',
                    event: 'click',
                    fn: me.addFields
                }
            ]
        });
    },

    addFields: function(event) {
        event.preventDefault();
        var form = event.data.context;

        var $form = form.getCmp();

        $form.find('[name=params]');
    }
});