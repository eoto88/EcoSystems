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
                    title: 'Form Data',
                    hidden: true,
                    fields: [
                        {
                            cmpType: 'tagfield',
                            name: 'id_group',
                            label: 'Group',
                            url: BASE_URL + "api/instances/"+ idInstance +"/groups",
                            valField: 'id',
                            titleField: 'title'
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