/**
 * Created by eoto88 on 15/01/17.
 */
ES.WidgetFormData = ES.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },
    params: [],
    selectedParamsAlias: [],

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
                            titleField: 'title',
                            load: me.loadParams()
                        }
                    ],
                    buttons: [
                        {
                            cmpType: 'button',
                            name: 'selectParams',
                            text: 'Select'
                        }
                    ]
                }
            ],
            listeners: [
                {
                    sel: 'form button.selectParams',
                    event: 'click',
                    fn: me.addFields
                },
                {
                    sel: 'form button.saveButton',
                    event: 'click',
                    fn: me.saveData
                },
                {
                    sel: 'form button.cancelButton',
                    event: 'click',
                    fn: me.cancelData
                }
            ]
        });
    },

    addFields: function(event) {
        event.preventDefault();
        var me = event.data.context,
            form = me.getCmpByIndex('FormData'),
            fieldParams = form.getFieldByName('params'),
            values = fieldParams.getValue();

        me.showFormFirstPart(false);

        var createDatepicker = ES.isEmpty(form.getFieldByName('datetime'));
        if(createDatepicker) {
            form.addField({
                cmpType: 'datepicker',
                label: 'Date & time',
                name: 'datetime'
            });
        }

        $.each(values, function(key, idParam) {
            var param = me.params[idParam];

            if( param ) {
                var field = {};
                field.name = param.alias;
                field.label = param.title;
                if(param.dataType == 'float') {
                    field.cmpType = 'spinner';
                    field.initVal = 7;
                    field.minVal = 0;
                    field.maxVal = 14;
                }

                me.selectedParamsAlias.push(param.alias);

                form.addField(field);
            }
        });

        form.addField({
            cmpType: 'button',
            name: 'saveButton',
            text: 'Save'
        });

        form.addField({
            cmpType: 'button',
            name: 'cancelButton',
            text: 'Cancel'
        });
    },

    loadParams: function() {
        var me =this,
            idInstance = ES.getActiveInstanceId();

        return function(query, callback) {
            ES.ajax({
                url: BASE_URL + "api/instances/" + idInstance + "/params",
                success: function (data) {
                    $.each(data.entities, function(key, entity) {
                        me.params[entity.id] = entity;
                    });
                    callback(data.entities);
                }
            });
        }
    },

    saveData: function(event) {
        event.preventDefault();
        var me = event.data.context;
    },

    cancelData: function(event) {
        event.preventDefault();
        var me = event.data.context,
            form = me.getCmpByIndex('FormData');

        me.showFormFirstPart(true);
        me.removeFormSecondPart();
    },

    showFormFirstPart: function(visible) {
        var me = this,
            form = me.getCmpByIndex('FormData'),
            fieldParams = form.getFieldByName('params'),
            butSelectParams = form.getFieldByName('selectParams');

        if(visible) {
            fieldParams.show();
            butSelectParams.show();
        } else {
            fieldParams.hide();
            butSelectParams.hide();
        }
    },

    removeFormSecondPart: function() {
        var me = this,
            form = me.getCmpByIndex('FormData'),
            fieldDatetime = form.getFieldByName('datetime');

        fieldDatetime.hide();

        $.each(me.selectedParamsAlias, function(key, alias) {
            // var field = form.getFieldByName(alias);
            form.removeField(alias);
        });
    }
});