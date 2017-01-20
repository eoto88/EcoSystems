/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Spinner = ES.Field.Text.extend({
    initVal: null,
    minVal: null,
    maxVal: null,
    step: 1,
    decimals: 0,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('text');
        me.appendTo(config.parent);
        var $spinner = me.getCmp(),
            initval = $spinner.data('initval'),
            min = $spinner.data('min'),
            max = $spinner.data('max'),
            step = $spinner.data('step'),
            decimals = $spinner.data('decimals');

        $spinner.TouchSpin({
            initval: initval,
            min: min,
            max: max,
            step: step,
            decimals: decimals,
            boostat: 5,
            maxboostedstep: 10
        });
    },

    getTpl: function() {
        var me = this,
            label = me.getLabel();

        if( ! ES.isEmpty(me.initval) ) {
            me.attr += ' data-initval="'+ me.initval +'"';
        }
        if( ! ES.isEmpty(me.minVal) ) {
            me.attr += ' data-min="'+ me.minVal +'"';
        }
        if( ! ES.isEmpty(me.maxVal) ) {
            me.attr += ' data-max="'+ me.maxVal +'"';
        }
        if( ! ES.isEmpty(me.step) ) {
            me.attr += ' data-step="'+ me.step +'"';
        }
        if( ! ES.isEmpty(me.decimals) ) {
            me.attr += ' data-decimals="'+ me.decimals +'"';
        }

        return me._super();
    }
}, 'ES.Field.Spinner');