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
                            groupField: 'id_group',
                            groupValueField: 'id_group',
                            groupLabelField: 'groupTitle',
                            load: me.loadParams()
                        },
                        {
                            cmpType: 'datepicker',
                            label: 'Date & time',
                            name: 'datetime',
                            value: moment().format(ES.datetimeFormat),
                            format: ES.datetimeFormat,
                            hidden: true
                        }
                    ],
                    buttons: [
                        {
                            cmpType: 'button',
                            name: 'selectParams',
                            text: 'Select'
                        },
                        {
                            cmpType: 'button',
                            name: 'saveButton',
                            text: 'Save',
                            hidden: true
                        },
                        {
                            cmpType: 'button',
                            name: 'cancelButton',
                            text: 'Cancel',
                            hidden: true
                        }
                    ]
                }
            ],
            listeners: [
                {
                    sel: 'form button.selectParams',
                    event: 'click',
                    fn: me.selectParams
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

    selectParams: function(event) {
        event.preventDefault();
        var me = event.data.context;
        me.showFormFirstPart(false);
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
    },

    showFormFirstPart: function(visible) {
        var me = this,
            form = me.getCmpByIndex('FormData'),
            fieldParams = form.getFieldByName('params'),
            butSelectParams = form.getFieldByName('selectParams'),
            butSaveData = form.getFieldByName('saveButton'),
            butCancel = form.getFieldByName('cancelButton'),
            fieldDatetime = form.getFieldByName('datetime');

        if(visible) {
            fieldDatetime.hide();
            butSaveData.hide();
            butCancel.hide();
            fieldParams.show();
            butSelectParams.show();

            $.each(me.selectedParamsAlias, function(key, alias) {
                // var field = form.getFieldByName(alias);
                form.removeField(alias);
            });
            me.selectedParamsAlias = [];
        } else {
            butSelectParams.hide();
            fieldParams.hide();
            fieldDatetime.show();
            butSaveData.show();
            butCancel.show();
            me.addFields();
        }
    },

    addFields: function() {
        var me = this,
            form = me.getCmpByIndex('FormData'),
            fieldParams = form.getFieldByName('params'),
            values = fieldParams.getValue();

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
    }
});