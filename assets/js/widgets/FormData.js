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
                            obligatory: true,
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
        var me = event.data.context,
            idInstance = ES.getActiveInstanceId(),
            form = me.getCmpByIndex('FormData'),
            fieldDatetime = form.getFieldByName('datetime'),
            data = {"params":[]};

        data.datetitme = fieldDatetime.getValue();

        $.each(me.selectedParamsAlias, function(key, alias) {
            var field = form.getFieldByName(alias);
            data.params.push({"a":alias,"v":field.getValue()});
        });
        ES.ajax({
            url: BASE_URL + "api/instances/" + idInstance + "/data",
            method: 'POST',
            data: data,
            success: function(data) {
                // TODO Feedback
                me.showFormFirstPart(true);
            }
        });
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
            fieldDatetime = form.getFieldByName('datetime'),
            butSaveData = form.getFieldByName('saveButton'),
            butCancel = form.getFieldByName('cancelButton');

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
                field.obligatory = true;
                if(param.dataType == 'float' || param.dataType == 'int') {
                    field.cmpType = 'spinner';
                }
                var valueOptions = ES.parseData(param.valueOptions);
                $.each(valueOptions, function(key, option) {
                    field[key] = option;
                });

                me.selectedParamsAlias.push(param.alias);

                form.addField(field);
            }
        });
    }
});